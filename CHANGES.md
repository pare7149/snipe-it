# Changes Between Custom Version (my_version) and Upstream (upstream_version)

This document describes all modifications made in the custom version compared to the upstream Snipe-IT codebase.

---

## Table of Contents

1. [New Files (Custom Only)](#1-new-files-custom-only)
2. [LDAP Sync Modifications](#2-ldap-sync-modifications)
3. [Accessory Checkout/Checkin Date & Expected Checkin](#3-accessory-checkoutcheckin-date--expected-checkin)
4. [Bulk Accessory Management](#4-bulk-accessory-management)
5. [Operator Overdraw Email Notification](#5-operator-overdraw-email-notification)
6. [Zabbix Stats Integration](#6-zabbix-stats-integration)
7. [Asset Status Auto-Update](#7-asset-status-auto-update)
8. [Company-Scoped Checkin Alerts](#8-company-scoped-checkin-alerts)
10. [Dashboard Access Control Change](#10-dashboard-access-control-change)
11. [User Model & Controller Changes](#11-user-model--controller-changes)
15. [Redirect Behavior Change](#15-redirect-behavior-change)
19. [Users View: Accessories Tab](#19-users-view-accessories-tab)
23. [Scheduled Commands](#23-scheduled-commands)
24. [Database Migrations](#24-database-migrations)

---

## 1. New Files (Custom Only)

The following files exist only in `my_version` and are entirely custom additions:

| File | Purpose |
|------|---------|
| `app/Console/Commands/GatherZabbixStats.php` | Artisan command to gather statistics from Zabbix monitoring |
| `app/Console/Commands/LdapSyncAndDelete.php` | Extended LDAP sync command that also deletes users |
| `app/Console/Commands/SendOperatorOverdrawEmail.php` | Sends email notifications when operators have overdrawn assets |
| `app/Console/Commands/UpdateAssetStatus.php` | Automatically updates asset statuses (runs every 10 seconds via scheduler) |
| `app/Mail/OperatorOverdrawEmail.php` | Mailable class for operator overdraw notifications |
| `app/Http/Controllers/Accessories/BulkAssignedAccessoriesController.php` | Controller for bulk editing/updating assigned accessories |
| `resources/views/accessories/bulk-update.blade.php` | View for bulk updating accessories |
| `resources/views/accessories/update.blade.php` | View for updating individual accessory checkout (expected checkin date) |
| `resources/views/partials/assigned-accessories-bulk-actions.blade.php` | Partial view for bulk action toolbar on assigned accessories |
| `resources/views/partials/forms/redirect_submit_options.blade.php` | Custom form partial for redirect/submit options |
| `resources/views/mail/markdown/operator-overdraw.blade.php` | Email template for operator overdraw notifications |
| `database/migrations/2025_06_02_132955_add_checkin_checkout_date_to_accessories_checkout_table.php` | Migration adding `expected_checkin` and `last_checkout` columns to accessories_checkout |
| `database/migrations/2025_06_11_123930_create_sessions_table.php` | Migration creating a sessions table |

---

## 2. LDAP Sync Modifications

**File:** `app/Console/Commands/LdapSync.php`

### Changes:
- **Job title filtering**: Custom version skips LDAP users whose `jobtitle` is `"Funktion"` or is null/empty. This filters out non-person entries during sync.
  ```php
  // Custom addition (not in upstream):
  if($item['jobtitle'] == "Funktion") continue;
  if($item['jobtitle'] == null || $item['jobtitle'] == "") continue;
  ```
- **Auto-activation**: Custom version forces `$user->activated = 1` on every LDAP sync update, ensuring all synced users are always activated. Upstream does not set this.

---

## 3. Accessory Checkout/Checkin Date & Expected Checkin

The custom version adds `expected_checkin` and `checkout_at` (checkout date) fields to accessory checkouts. This is a cross-cutting feature affecting multiple files:

### Modified Files:

- **`app/Http/Controllers/Accessories/AccessoryCheckoutController.php`**
  - Custom version sets `created_at` from `$request->input("checkout_at")` instead of `Carbon::now()`
  - Custom version adds `expected_checkin` from request input to `AccessoryCheckout`
  - Custom version adds `view_update()` and `update()` methods to allow editing the expected checkin date after checkout

- **`app/Models/AccessoryCheckout.php`**
  - Custom version adds `expected_checkin` and `last_checkout` to the `$fillable` array

- **`app/Http/Transformers/AccessoriesTransformer.php`**
  - Custom version adds `expected_checkin` field to the transformer output
  - Custom version adds `transformAssignedAccessories()` and `transformAssignedAccessory()` methods for a custom API endpoint
  - Custom version adds `'extend' => true` to `available_actions` for checked-out accessories

- **`app/Presenters/AccessoryPresenter.php`**
  - Custom version adds `expected_checkin` column (labeled "Rückgabedatum" — German for "return date") to multiple data table layouts
  - Custom version adds an entirely new `userAssignedDataTableLayout()` static method for the user-assigned accessories table

- **`app/Http/Controllers/Api/AccessoriesController.php`**
  - Custom version adds `expected_checkin` to the allowed sort columns

- **`resources/views/accessories/checkout.blade.php`**
  - Custom version adds checkout date picker and expected checkin date picker fields to the checkout form

---

## 4. Bulk Accessory Management

The custom version adds the ability to bulk-edit assigned accessories:

- **New controller:** `app/Http/Controllers/Accessories/BulkAssignedAccessoriesController.php`
- **New views:** `resources/views/accessories/bulk-update.blade.php`, `resources/views/partials/assigned-accessories-bulk-actions.blade.php`
- **Routes added in** `routes/web/accessories.php`:
  - `POST accessories/bulkedit` → `BulkAssignedAccessoriesController@edit`
  - `POST accessories/bulkupdate` → `BulkAssignedAccessoriesController@update`

---

## 5. Operator Overdraw Email Notification

A custom feature to notify operators when they have overdrawn (too many assets checked out):

- **Command:** `app/Console/Commands/SendOperatorOverdrawEmail.php`
- **Mailable:** `app/Mail/OperatorOverdrawEmail.php`
- **Email template:** `resources/views/mail/markdown/operator-overdraw.blade.php`
- **Scheduled** weekly on Wednesdays at 8:00 for company IDs 1 and 2 (see [Scheduled Commands](#23-scheduled-commands))

---

## 6. Zabbix Stats Integration

- **Command:** `app/Console/Commands/GatherZabbixStats.php`
- Gathers statistics from Zabbix monitoring system and integrates them with Snipe-IT asset data.

---

## 7. Asset Status Auto-Update

- **Command:** `app/Console/Commands/UpdateAssetStatus.php`
- Automatically updates asset statuses based on some criteria.
- **Scheduled** to run every 10 seconds via the Laravel scheduler (see [Scheduled Commands](#23-scheduled-commands)).

---

## 8. Company-Scoped Checkin Alerts

**File:** `app/Console/Commands/SendExpectedCheckinAlerts.php`

The custom version fundamentally changes how expected checkin alerts work:

| Aspect | Custom Version | Upstream |
|--------|---------------|----------|
| Command signature | `snipeit:expected-checkin {company_id}` | `snipeit:expected-checkin {--with-output}` |
| Asset query | Filtered by `company_id` | All assets (no company filter) |
| Admin notification recipient | `$company->email` | `$settings->alert_email` |
| Output option | Not available | `--with-output` flag for console display |

The custom version requires a `company_id` argument and sends notifications to the company's email address rather than the global alert email.

## 10. Dashboard Access Control Change

**File:** `app/Http/Controllers/DashboardController.php`

| Aspect | Custom Version | Upstream |
|--------|---------------|----------|
| Dashboard access check | `hasAccess('assets.create')` | `hasAccess('admin')` |
| User count | `auth()->user()->count()` (all users) | `Company::scopeCompanyables(auth()->user())->count()` (company-scoped) |

The custom version allows any user with asset creation permission to see the admin dashboard, while upstream requires full admin access. The user count in the custom version is not company-scoped.

---

## 11. User Model & Controller Changes

### `app/Models/User.php`
- Custom version adds a `fullName()` convenience method that calls `getFullNameAttribute()`. Not present in upstream.

### `app/Http/Controllers/Users/UsersController.php`
- Custom version adds `get_accessories_checked_out_to()` method — an API endpoint that returns accessories checked out to a specific user (used by the custom accessories table in the user view).
- Custom version imports `AccessoriesTransformer` (not needed in upstream).

### `routes/api.php`
- Custom version adds route: `GET user/accessories/checked_out` → `UsersController@get_accessories_checked_out_to`

---

## 15. Redirect Behavior Change

**File:** `app/Http/Middleware/RedirectIfAuthenticated.php`

| Custom Version | Upstream |
|---------------|----------|
| `redirect()->intended(config('app.url'))` | `redirect()->intended('/')` |
| Imports `App\Models\Settings` (unused) | No unused import |

The custom version redirects to the full `APP_URL` (which can cause issues with proxies), while upstream redirects to `/`.

---

## 19. Users View: Accessories Tab

**File:** `resources/views/users/view.blade.php`

| Custom Version | Upstream |
|---------------|----------|
| Uses API-driven bootstrap-table with `data-url` pointing to custom API endpoint | Uses server-side rendered `@foreach` loop |
| Includes bulk action toolbar (`assigned-accessories-bulk-actions` partial) | No bulk actions |
| Uses custom `userAssignedDataTableLayout()` | Simple HTML table headers |
| Shows "Update" button linking to accessory update view | No update button |
| Shows `expected_checkin` column | No expected checkin column |

---

## 23. Scheduled Commands

**File:** `routes/console.php`

The custom version adds scheduled commands:
```php
Schedule::command("pascal:update-asset-status")->everyTenSeconds()->runInBackground();
Schedule::command("pascal:send-operator-overdraw-email 1")->weeklyOn(3, "8:00");
Schedule::command("pascal:send-operator-overdraw-email 2")->weeklyOn(3, "8:00");
```

These are not present in upstream.

---

## 24. Database Migrations

Custom-only migrations:
1. **`2025_06_02_132955_add_checkin_checkout_date_to_accessories_checkout_table.php`** — Adds `expected_checkin` and `last_checkout` columns to the `accessories_checkout` table.
2. **`2025_06_11_123930_create_sessions_table.php`** — Creates a `sessions` table (likely for database session driver).

---

## Summary of Key Custom Features

1. **Accessory expected checkin dates** — Track when accessories should be returned
2. **Bulk accessory management** — Bulk edit/update assigned accessories
3. **Operator overdraw alerts** — Weekly email notifications for overdrawn operators
4. **Zabbix integration** — Gather monitoring stats from Zabbix
5. **Asset status auto-update** — Automatic status updates every 10 seconds
6. **Company-scoped checkin alerts** — Per-company expected checkin notifications
9. **LDAP filtering** — Skip "Funktion" entries and null job titles during sync
