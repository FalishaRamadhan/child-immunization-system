# TotoBora: A Web-Based Child Immunization and Growth Monitoring System for Improving Continuity of Care in Resource-Constrained Healthcare Facilities

## Problem Statement

Many healthcare facilities in resource-constrained environments still rely on paper-based systems for pediatric health records. This often results in:

- Misplaced or incomplete records
- Missed immunization schedules
- Poor growth monitoring
- Delayed medical interventions
- Weak continuity of care

TotoBora addresses these challenges by digitizing child healthcare records into a centralized and accessible system.

---

## Features

### Child Registration
- Register new child profiles
- Store biodata and guardian information
- Maintain centralized pediatric records

### Immunization Tracking
- Record administered vaccines
- Track immunization history
- Generate upcoming vaccination schedules
- Identify missed immunization appointments

### Growth Monitoring
- Record height and weight measurements
- Track child growth progress over time
- Generate growth reports and summaries

### Appointment Management
- Schedule clinic visits
- Track follow-up appointments
- Monitor immunization adherence

### Automated SMS Reminders
- Notify caregivers about upcoming appointments
- Send reminders for missed vaccinations
- Improve continuity of care

### Authentication and Role-Based Access
- Secure login system
- Healthcare worker access control
- Administrator management features

### Reporting
- Generate immunization reports
- Monitor growth trends
- Support healthcare decision-making

---

## System Objectives

The main objective of TotoBora is:

> To develop a web-based child immunization and growth monitoring system for resource-constrained healthcare facilities.

Specific objectives include:

- Improving child immunization tracking
- Supporting early growth monitoring and intervention
- Reducing missed vaccination appointments
- Enhancing continuity of pediatric care
- Improving healthcare record accessibility

---

## Tech Stack

### Backend
- Laravel (PHP Framework)

### Frontend
- HTML5
- CSS3
- JavaScript

### Database
- MySQL

### Development Tools
- Visual Studio Code
- Git & GitHub
- Draw.io / Lucidchart

---

## Core Modules
### Login
<img width="344" height="383" alt="Screenshot 2026-06-29 122804" src="https://github.com/user-attachments/assets/cd4ec4d6-236a-411f-955d-663398bb6494" />

### Dashboard
<img width="959" height="284" alt="Screenshot 2026-06-29 122839" src="https://github.com/user-attachments/assets/1d8f84e1-4eb3-4900-8484-0bf25520096b" />

### Children records
<img width="958" height="397" alt="Screenshot 2026-06-29 122855" src="https://github.com/user-attachments/assets/a18e8fc8-e1a3-441a-95f0-7b0db78b4760" />

### Child Profile
<img width="958" height="307" alt="Screenshot 2026-06-29 123035" src="https://github.com/user-attachments/assets/d9bdf19e-3651-4170-b086-2eeaa76e9e1e" />

### Reminders
<img width="959" height="317" alt="Screenshot 2026-06-29 123054" src="https://github.com/user-attachments/assets/6abfa04f-671b-4286-b585-9fb5a178807f" />

### Reports
<img width="946" height="473" alt="image" src="https://github.com/user-attachments/assets/118a4fe2-65eb-43e9-abd5-f6dc91fbc210" />

---

## Installation Guide

### Prerequisites

Ensure you have installed:

- PHP >= 8.x
- Composer
- MySQL
- Node.js & npm
- Laravel CLI

### Clone the Repository

```bash
git clone https://github.com/yourusername/totobora.git
cd totobora
```

### Install Dependencies

```bash
composer install
npm install
```

### Configure Environment

Copy the environment file:

```bash
cp .env.example .env
```

Update database credentials in `.env`.

### Generate Application Key

```bash
php artisan key:generate
```

### Run Database Migrations

```bash
php artisan migrate
```

### Seed Database (Optional)

```bash
php artisan db:seed
```

### Start Development Server

```bash
php artisan serve
```

Visit:

```text
http://127.0.0.1:8000
```

---

## Testing

Run automated tests using PHPUnit:

```bash
php artisan test
```

Testing includes:

- Unit Testing
- Integration Testing
- System Testing
- User Acceptance Testing (UAT)

---

## Usage Instructions
### Administrator
1. Log in to the system.
2. Manage healthcare worker accounts.
3. Configure system settings where applicable.
4. Monitor registered children, appointments, and reports.
5. Manage role-based access permissions.

### Healthcare Worker
1. Log in using authorized credentials.
2. Register a child and enter guardian details.
3. Record administered vaccines.
4. Add height and weight measurements.
5. Generate immunization and growth reports.
6. Trigger or manage SMS reminders where applicable.

### Caregiver / Parent
Caregivers do not directly manage the system. They receive SMS reminders for:
> Upcoming vaccination appointments
> Missed immunization appointments

---
## Folder Structure

totobora/
├── app/
│   ├── Http/
│   ├── Models/
│   ├── Providers/
│   └── Services/
├── config/
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
├── routes/
├── storage/
├── tests/
├── artisan
├── composer.json
├── package.json
└── vite.config.js

---

## API Documentation

TotoBora is primarily a web-based Laravel application. However, it uses an external API integration through Africa’s Talking for SMS notifications.

Provider: Africa’s Talking
Purpose: Sending SMS notifications to caregivers
Authentication: API Key
Usage: Appointment reminders and missed vaccination alerts
SMS Reminder Use Cases

The Africa’s Talking SMS API is used when:
> A child has an upcoming vaccination appointment
> A child has missed a scheduled immunization visit

---

## Scope

TotoBora focuses on:

✅ Child immunization tracking  
✅ Growth monitoring  
✅ Appointment scheduling  
✅ SMS reminders  
✅ Role-based authentication  
✅ Basic reporting

The system does **not** include:

❌ Hospital billing  
❌ Pharmacy management  
❌ AI diagnosis  
❌ National health information systems integration  
❌ Management of healthcare services outside pediatric immunization and growth monitoring

---

## Contributors

**Ramadhan Falisha Achieng**  
**Onyimbo Daisy Lydiah**  
Supervisor: **Kevin Ochieng’ Omondi**

---

## License

This project is developed for academic purposes.

You may modify and extend it for educational and research purposes.

---

## Keywords

Child Immunization • Growth Monitoring • Pediatric Health • Continuity of Care • Healthcare Information System • Laravel • MySQL • Web-Based Health System

## Authentication Flow


TotoBora uses session-based authentication built on Laravel's authentication 
system. Access is restricted to administrators and healthcare workers only. 
Caregivers do not log in - they interact with the system through SMS reminders.


### Entry and login


Every protected route is guarded by Laravel's `auth` middleware. Any 
unauthenticated request is redirected to `/login` regardless of the URL 
attempted.


On login, the system first checks rate limiting - a maximum of five attempts 
per IP address per minute. Exceeding this blocks further attempts temporarily. 
Within the limit, credentials are verified against the `users` table. 
Passwords are stored as bcrypt hashes and are never stored or compared as 
plain text. Failed attempts return a generic error message that does not 
reveal whether the email or password was wrong.


After correct credentials are verified, the system checks that the account's 
`is_active` status is `true`. Deactivated accounts are rejected even with 
valid credentials. On success, the session ID is regenerated to prevent 
session fixation, and the rate limiter is cleared.


### Role-based redirection


After login, users are redirected based on their role:

 Admin:
 Redirected to: `/reports`
 Access: Can access all facilities, all records, and manage users.

Healthcare Worker:
Redirected to: `/children`
Access:Can only access records for their assigned facility.

This is enforced on every request via the `EnsureRole` middleware - not just 
at the point of login. A healthcare worker navigating directly to `/reports` 
or `/users` receives a `403 Forbidden` response.


### Session and CSRF protection


Sessions are stored server-side. Only an encrypted session ID travels to the 
browser via an HTTP-only cookie. All forms include a `@csrf` token that 
Laravel validates on every state-changing request, blocking cross-site 
request forgery.


### Logout


Logout calls `Auth::logout()`, invalidates the session, and regenerates the 
CSRF token. The browser back button does not restore access after logout.


### Password reset


Users request a reset via the *Forgot password?* link on the login page. 
The system generates a random 64-character token, stores it hashed in 
`password_reset_tokens`, and it expires after 60 minutes. On submission, 
the token is verified and checked for expiry. If valid, the password is 
updated with a new bcrypt hash and the token is deleted, making it 
single-use.


### Account creation


Accounts are not self-registered. Only an administrator can create user 
accounts through `/users/create`, assigning the role, facility, and initial 
password. This reflects the system's design , healthcare workers are 
provisioned staff, not self-signing members of the public.



