Event Registration (Drupal 10) — Custom Module
 
## Overview
A lightweight event registration system built for **Drupal 10** using **core APIs only** (no contributed modules). It provides:
 
- **Admin event setup** (dates, names, registration window)
- **User registration form** with validation and duplicate checks
- **AJAX-driven** category → date → event selection
- **Custom tables** for event configuration and registrations
- **Admin listing** + CSV export
 
The registration form UI has been customized with module-scoped styling (CSS library) for a more modern layout.
 
## Tech
- Drupal: 10.x
- PHP: 8.1+
- Database: MySQL 5.7 / 8
- Dependencies: Composer
 
## Module path
`web/modules/custom/event_registration`

## Local setup
1. **Install PHP/Composer dependencies**
 
    ```bash
    composer install
    ```
 
2. **Create the database + import schema**
 
    ```sql
    CREATE DATABASE event_portal;
    ```
 
    ```bash
    mysql -u root event_portal < database.sql
    ```
 
3. **Start the project**
 
    If you want a quick local server:
 
    ```bash
    php -S localhost:8080 -t web/
    ```
 
4. **Enable the module**
 
    In the Drupal admin UI:
 
    - Enable **Event Registration** at `/admin/modules`
    - Clear cache at `/admin/config/development/performance`

## URLs
- **Event Registration Form**
  - `/event/register`
- **Event Configuration (Admin)**
  - `/admin/config/event-registration/config`
- **Admin Settings**
  - `/admin/config/event-registration/settings`
- **Admin Registration Listing**
  - `/admin/event-registrations`
## Database tables
### `event_config`
- `id` (PK)
- `reg_start`, `reg_end`
- `event_date`
- `event_name`
- `category`
 
### `event_registration`
- `id` (PK)
- `full_name`, `email`, `college`, `department`
- `event_config_id` (FK)
- `created`
## Form flow (AJAX)
- Select **Category**
- The form loads valid **Event Dates**
- Select **Event Date**
- The form loads valid **Event Names** (stored as `event_config_id`)

## Validation
- Email format validation
- Text-only fields accept letters/spaces
- Duplicate prevention using **Email + Event**

## Email notes
Email notifications use Drupal’s Mail API. On a typical localhost setup, mail may fail unless you configure an SMTP / mail catcher (MailHog, Mailpit, etc.).

## Configuration
Settings are stored using Drupal Config API:
`config/install/event_registration.settings.yml`

## Permissions
- Permission: `view event registrations`
- Used for: `/admin/event-registrations`
- Assign via: `/admin/people/permissions`

## Admin listing + CSV
The admin page supports filtering and CSV export.

## Repo structure (high level)
```
.
├── composer.json
├── composer.lock
├── database.sql
└── web/
    └── modules/custom/event_registration/
```

## Notes
- Core-only implementation (no contributed modules)
- DI + PSR-4 conventions followed
- Ready for environments with proper SMTP configuration
