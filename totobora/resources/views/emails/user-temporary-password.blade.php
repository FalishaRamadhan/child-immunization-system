<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your TotoBora Account Password</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827;">
    <h2>Your TotoBora Account Has Been Updated</h2>

    <p>Hello {{ $user->first_name }},</p>

    <p>
        Your TotoBora account details have been updated. A new temporary password has been generated for you.
    </p>

    <p>
        <strong>Email:</strong> {{ $user->email }}
    </p>

    <p>
        <strong>Temporary Password:</strong>
        <span style="font-size: 18px; font-weight: bold;">
            {{ $plainPassword }}
        </span>
    </p>

    <p>
        Please log in using this password and change it as soon as possible.
    </p>

    <p>
        Thank you,<br>
        TotoBora Team
    </p>
</body>
</html>