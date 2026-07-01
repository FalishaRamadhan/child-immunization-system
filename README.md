# TotoBora

TotoBora is a web-based child immunization and growth monitoring system designed to improve continuity of pediatric healthcare in resource-constrained healthcare facilities.

The platform centralizes child health records, tracks immunization schedules, monitors growth indicators such as height and weight, and provides automated reminders for upcoming or missed vaccinations.

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

## System Architecture

TotoBora follows a three-layer architecture:

```text
Presentation Layer (UI)
        ↓
Application Logic Layer (Laravel)
        ↓
Database Layer (MySQL)
```

The system uses an object-oriented and MVC-based architecture for scalability and maintainability.

---

## Core Modules

### 1. Child Management Module
Handles:
- Child registration
- Guardian information
- Child profile management

### 2. Immunization Module
Handles:
- Vaccine scheduling
- Immunization records
- Follow-up tracking

### 3. Growth Monitoring Module
Handles:
- Height tracking
- Weight tracking
- Growth trend monitoring

### 4. Reminder Module
Handles:
- SMS notifications
- Missed appointment alerts
- Caregiver engagement

### 5. Authentication Module
Handles:
- Login/logout
- Role management
- Secure system access

### 6. Reporting Module
Handles:
- Growth summaries
- Vaccination reports
- Follow-up monitoring

---

## Intended Users

### Healthcare Workers
Can:
- Register children
- Record vaccinations
- Update growth measurements
- Generate reports

### Administrators
Can:
- Manage users
- Configure system settings
- Monitor system usage

### Caregivers / Parents
Can:
- Receive appointment reminders
- Stay informed on immunization schedules

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
❌ National HIS integration  
❌ Mobile application support

---

## Project Status

🚧 In Development

This project is currently under development as an undergraduate Informatics and Computer Science project.

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

## Acknowledgements

Special thanks to:

- Strathmore University
- School of Computing and Engineering Sciences
- Project Supervisor
- Healthcare professionals contributing domain insights

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



