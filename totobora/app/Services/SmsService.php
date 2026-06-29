<?php

namespace App\Services;
// Handles SMS dispatch via Africa's Talking gateway with delivery status tracking

use AfricasTalking\SDK\AfricasTalking;
use App\Models\Reminder;
use Illuminate\Support\Facades\Log;

class SmsService
{
    private $sms;

    public function __construct()
    {
        $at = new AfricasTalking(
            config('services.africastalking.username'),
            config('services.africastalking.api_key')
        );

        $this->sms = $at->sms();
    }

    /**
     * Send an SMS and update the reminder's delivery status.
     */
    public function send(Reminder $reminder): bool
    {
        $guardian    = $reminder->guardian;
        $appointment = $reminder->appointment;
        $child       = $appointment->child;

        $phone = $this->formatPhoneNumber($guardian->phone_number);

        $message = "TotoBora reminder: {$child->first_name} {$child->last_name} "
            . "is due for {$appointment->vaccine_due} on "
            . $appointment->scheduled_date->format('d M Y')
            . ". Please visit {$child->facility->name}.";

        try {
            $payload = [
                'to'      => $phone,
                'message' => $message,
            ];

            /*
             * Only add sender ID if it exists.
             * If your sender ID is not approved by Africa's Talking,
             * including it can cause the SMS to fail.
             */
            if (config('services.africastalking.sender_id')) {
                $payload['from'] = config('services.africastalking.sender_id');
            }

            $result = $this->sms->send($payload);

            Log::info('AfricaTalking SMS result:', $result);

            $recipient = $result['SMSMessageData']['Recipients'][0] ?? null;

            $providerStatus = strtolower($recipient['status'] ?? '');
            $statusCode = $recipient['statusCode'] ?? null;

            /*
             * Africa's Talking success status is usually:
             * status: Success
             * statusCode: 101
             */
            $sent = $providerStatus === 'success' || $statusCode == 101;

            $reminder->update([
                'delivery_status' => $sent ? 'sent' : 'failed',
            ]);

            if ($sent) {
                Log::info("SMS sent to {$phone} for reminder {$reminder->reminder_id}");
                return true;
            }

            Log::error("SMS failed to {$phone} for reminder {$reminder->reminder_id}", [
                'provider_status' => $providerStatus,
                'status_code' => $statusCode,
                'result' => $result,
            ]);

            return false;

        } catch (\Exception $e) {
            $reminder->update([
                'delivery_status' => 'failed',
            ]);

            Log::error("SMS exception for reminder {$reminder->reminder_id}: " . $e->getMessage());

            return false;
        }
    }

    /**
     * Convert Kenyan phone numbers to international format.
     * Example: 0712345678 becomes +254712345678
     */
    private function formatPhoneNumber(string $phone): string
    {
        $phone = preg_replace('/\s+/', '', $phone);

        if (str_starts_with($phone, '+')) {
            return $phone;
        }

        if (str_starts_with($phone, '0')) {
            return '+254' . substr($phone, 1);
        }

        if (str_starts_with($phone, '254')) {
            return '+' . $phone;
        }

        return $phone;
    }
}