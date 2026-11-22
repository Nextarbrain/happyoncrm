# HappyOn CRM — project context

Living handoff for coding agents (Antigravity and others). Read this before changing code. After each work round, append a dated entry under **Round log**. Do not commit secrets. Do not push unless the owner explicitly asks.

## Project goal

Turn the existing HappyOn CRM into a **multi-tenant SaaS POS for kids’ play areas, indoor play zones, and party venues** — “Petpooja for play areas.”

HappyOn (happyonplay.com) is a kids’ party / play venue. The CRM already runs their front desk: timed walk-in tickets, memberships, birthday-party bookings, add-on / café-style open billing, GST invoices, coupons, multi-branch reporting, and SMS.

The product should eventually serve **HappyOn and any other play area**: tenant onboarding, white-label invoices and messaging, subscription billing, and outlet-level POS.

Primary code: GitHub [Nextarbrain/happyoncrm](https://github.com/Nextarbrain/happyoncrm). Local clone: `/workspace/happyoncrm`.

## Stack and versions

| Piece | Version / notes |
| --- | --- |
| Framework | Laravel 8.83.29 (`laravel/framework ^8.0`) — EOL (security support ended Jan 2023) |
| PHP | `^8.0` (timezone in config: `Asia/Kolkata`) |
| Auth | Custom `admin` guard (`App\Models\Admin`); Spatie `laravel-permission` ^5.10 |
| UI | Blade under `resources/views/happyView`, Yajra DataTables, Bootstrap-era assets in `public/assets` |
| PDF | `dompdf/dompdf` ^2.0 |
| QR | `simplesoftwareio/simple-qrcode` ^4.2 |
| Front-end build | Laravel Mix 5 (legacy). Day-to-day work uses already-built `public/assets` |
| Queue | `sync` in `.env.example` |
| DB | MySQL |
| Tests | PHPUnit 9 — only the default example tests |

Notable packages: `laravelcollective/html`, `fruitcake/laravel-cors`, `fideloper/proxy`. Dev: `barryvdh/laravel-debugbar`.

## How to run locally

1. PHP 8.0+ and Composer, plus a MySQL database.
2. `composer install`
3. `cp .env.example .env` then `php artisan key:generate`
4. Set `DB_*` in `.env`. Never put SMS, payment, or API credentials in git.
5. **Migrations are incomplete for a fresh database.** `database/migrations/` mostly adds `branch_id` / soft deletes to tables that already existed in production. Original `create_*` migrations live in `database/New folder/` and are **not** auto-run (and include leftovers such as a `flights` table and two `delete_activities` creates). To bootstrap: carefully move/merge those create migrations (dedupe first) **or** import a sanitized schema dump with no credentials or customer PII.
6. `php artisan migrate`
7. `php artisan serve` — web root is `public/`. Login is `GET /`, auth is `POST /auth`.
8. Optional scheduler (hourly thank-you SMS): `php artisan schedule:run` via cron. Command: `users:sendMessage`.

On Hostinger the app has lived under `public_html/crm` on happyonplay.com. Do not use live FTP/SSH credentials in this file.

## Directory map

```
app/Http/Controllers/     Web controllers (Admin, Ticket, Membership, Event, OpenBilling, Branch, Staff, Permission, Trash)
app/Http/Controllers/API/ ProductController, RelloApiController (Reelo loyalty stub)
app/Http/Middleware/      AdminAuth (session check only)
app/Models/               Eloquent models (see modules below)
app/Helpers/helper.php    Global helpers: sendsms(), can(), hasPermission(), tax helpers
app/DataTables/           Yajra tables (Customer, User)
app/Console/Commands/     SendMessage (post-play SMS), MakeDataTable
routes/web.php            All staff UI routes (most behind `admin_auth`)
routes/api.php            Unauthenticated product + Reelo verify routes
resources/views/happyView/ Blade UI + invoices under receipt/
database/migrations/      Later alters (May 2025 onward) + branch table
database/New folder/      Original create-table migrations (not in the default path)
public/assets/            Compiled CSS/JS, HappyOn logo
tests/                    Example Feature/Unit tests only
```

## Existing modules and key files

Staff authenticate as `Admin` (`auth('admin')`). The `users` table is **customers**, not staff.

- Customer `role = 1`: walk-in ticket sale
- Customer `role = 2`: membership purchase
- Super-admin style access: `admins.isSuperAdmin` and/or `admins.branch_id` is null (sees all branches)
- Outlet scoping: `branch_id` on most operational tables (added May 2025, “branch-wise” work)

| Module | What it does | Key paths |
| --- | --- | --- |
| Auth / login | Admin session login/logout | `app/Http/Controllers/AdminController.php`, `app/Http/Middleware/AdminAuth.php`, `resources/views/happyView/login.blade.php`, `config/auth.php` |
| Dashboard | Date- and branch-wise sales, GST totals, payment-method split (Cash / Card / UPI) | `AdminController.php` (`dashboard`, `DatawiseRecord`, `monthWise`), `resources/views/happyView/dashboard.blade.php` |
| Tickets / timed play | Walk-in entry: parent + kids, hours or Unlimited, start/end time, extend play (+30 min / extra time), socks and other add-on products, coupons, invoice PDF, SMS on check-in and extend | `TicketController.php`, `app/Models/Kid.php`, `User.php`, `UserDetail.php`, `UserProduct.php`, `DayPrice.php`, `resources/views/happyView/tickets.blade.php`, `tickets-edit.blade.php`, `resources/views/receipt/index.blade.php` |
| Day pricing | Per-hour prices by weekday (and weekend editor) | `DayPrice` model, `AdminController::editDayPricePost` / `editWeekendPost` |
| Memberships | Plans with credits/entries, sell/renew, OTP to redeem an entry at the desk, history, renewable payments table, invoice PDF, SMS | `MembershipController.php`, `MembershipPlan.php`, `MembershipBuy.php`, `MembershipHistory.php`, `MembershipBuyRenewable.php`. Note typo duplicate model `MembershiBuy.php` |
| Parties / events | Event bookings with time window, package items, deposits and installments, payment status, GST 18/12/5, coupons, invoice PDF | `EventController.php`, `Event.php`, `EventBooking.php`, `EventItem.php`, `DefaultEventItem.php`, `resources/views/happyView/events*.blade.php`, `event-invoice.blade.php` |
| Open billing | Miscellaneous / café-style bills (named bill, line items, GST, coupons, invoice) | `OpenBillingController.php`, `OpenBilling.php`, `OpenBillingItem.php`, `resources/views/happyView/openBilling/` |
| Products & coupons | Product catalog with tax rate and a qty field (qty is not a real stock ledger). Coupons with date window | `AdminController` product/coupon actions, `Product.php`, `Coupon.php` |
| Branches | Multi-outlet CRUD; GSTIN and address on branch; `type` column | `BranchController.php`, `app/Models/Branch.php` (table `branch`), migrations `2025_05_13_130201_create_branch_table.php`, `2025_05_21_102033_add_type_to_branch_table.php` |
| Customers / CRM | Customer list, phone lookup, filters, upcoming birthdays | `TicketController` (`custmerView`, `upcomingBirthdaysView`, …), `UserDetail.php` |
| Reports | Ticket, membership, membership usage, event, open-billing, sales, payment-method filter | `AdminController::reports`, report methods on Ticket / Membership / Event / OpenBilling controllers, `resources/views/happyView/*report*` |
| Staff & roles | Staff are `Admin` rows. Spatie roles/permissions, `can()` / `hasPermission()` helpers, sidebar gates. Coverage in controllers is uneven (some actions unchecked). | `StaffController.php`, `PermissionController.php`, `AdminPermission.php`, `app/Helpers/helper.php`, `resources/views/happyView/includes/sidebar.blade.php` |
| Trash | Soft-deletes + restore/force-delete; `delete_activities` log | `TrashController.php`, `DeleteActivity.php` |
| SMS | DLT SMS via `sendsms()` (kutility). Templates hardcoded with HappyOn copy. Hourly post-play review SMS. Membership OTP. **Credentials are hardcoded in helper.php — move to env and rotate; do not copy them into docs or git.** | `app/Helpers/helper.php`, `app/Console/Commands/SendMessage.php`, Ticket/Membership controllers |
| Invoices / GST | Dompdf invoices; CGST/SGST split in views; stored `gst18` / `gst12` / `gst5`. Fallback legal name, address, and GSTIN are HappyOn-hardcoded when branch fields are empty. Invoice number is often the row id. | `resources/views/receipt/`, `happyView/*invoice*.blade.php` |
| QR | `/code` route; package installed | `AdminController::qrCode`, `resources/views/happyView/qrcode.blade.php` |
| Reelo (loyalty) API | Stub: `POST /api/v1/reelo/verify-integration` compares `merchant_id` to `env('MERCHANT_ID')` and may return `pos_settings.pos_key`. Unauthenticated. `PosSettingController` is empty. | `app/Http/Controllers/API/RelloApiController.php`, `PosSetting.php`, `routes/api.php` |
| Product API | `GET /api/product`, `/api/productByID` — no API auth or throttle | `API/ProductController.php` |

There is **no** payment-gateway integration (Razorpay / UPI collect / cards). Cash, Card, and UPI are **manual method labels** on a bill. There is **no** WhatsApp API (a WhatsApp icon appears in a memberships view only). There is **no** digital waiver, wristband/RFID, kiosk, online booking, or real inventory ledger.

## DB key tables

Inferred from models, later migrations, leftover creates in `database/New folder/`, and controllers. Production schema has drifted; official migrations do not fully describe it.

**Staff / access**

- `admins` — staff login; `branch_id`, `isSuperAdmin`, `name`, `email`, `password`, `phone`, `role`
- `branch` — `name`, `location`, `pincode`, `address`, `phone`, `gstno`, `user_id` (FK admins), `status`, `type`
- Spatie: `roles` (also `branch_id`), `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions`

**Customers and play**

- `users` — customers; `role`, `otp`, `branch_id`, soft deletes
- `user_details` — `phone_no`, `dob`, `number_of_kids`, `branch_id`, soft deletes
- `kids` — `user_id`, `name`, `dob`, `hours`, `end_time` (time string or `Unlimited`), `extra_time` / extra hour fields, `branch_id`, soft deletes
- `day_prices` — hour row + monday…sunday prices, `branch_id`
- `user_products` — add-ons on a visit (`product_name`, `price`, `qty`), `branch_id`, soft deletes
- `payment_methods` — per-user payment label + amount, `branch_id`, soft deletes

**Catalog and billing**

- `products` — `product_name`, `product_price`, `product_qty`, `product_tax` (0/5/12/18), `branch_id`
- `coupons` — `coupon_name`, discount fields, `start_from`, `valid_till`, `branch_id`
- `open_billings` / `open_billing_items` — miscellaneous bills, GST columns, coupons, `branch_id`
- `pos_settings` — Reelo/POS key (single-row assumption)

**Memberships and parties**

- `membership_plans` — `plan_name`, `price`, `credits`, `branch_id`, soft deletes
- `membership_buys`, `membership_histories`, `membership_buy_renewable`
- `events`, `event_bookings` (slot, contact, payment status, advance/installments, `gst18/12/5`, coupons, `branch_id`), `event_items`, `default_event_items`

**Other**

- `delete_activities` — deleted payload log
- `password_resets`, `failed_jobs`

Almost every operational table is branch-scoped, **not** tenant-scoped. One database = one company (HappyOn) with many outlets.

## Conventions

- New staff UI: Blade in `resources/views/happyView/`, routes in `routes/web.php` inside the `admin_auth` group.
- Permission names are slug-like (`tickets-edit`, `reports-membership`). Check with `hasPermission('…')` (aborts 401) or `can('…')` in controllers **and** the sidebar — do not rely on the sidebar alone.
- Scope queries by `auth('admin')->user()->branch_id` unless the user is a super admin with a null branch.
- GST: inclusive tax helpers in `helper.php` (`getTax`, `getAddedItemTotal`); persist `gst18` / `gst12` / `gst5` on bills. Do not hardcode a venue’s GSTIN, legal name, or logo; read them from tenant/branch settings.
- Payments: keep method labels consistent (`Cash`, `Card`, `UPI`) until a real gateway exists.
- Soft-delete operational records; use the trash flow rather than hard deletes where the app already does so.
- Do not add new HappyOn-specific copy, logos, or SMS sender IDs in shared code. Message templates must be tenant-configurable.
- No secrets in the repo, tickets, or this file. If you find credentials in code, move them to env and tell the owner to rotate; do not reprint the values.
- PHP style is informal and inconsistent (fat controllers, typo’d names, `echo` in places). Prefer small, tested changes. Do not rewrite the app in a drive-by.

## Known risks and tech debt

**Security (fix early)**

- Laravel 8 is end-of-life.
- SMS API key, DLT PE ID, sender id, and campaign/template IDs are hardcoded in `app/Helpers/helper.php`. Rotate after moving to env.
- Unauthenticated Artisan cache routes in `routes/web.php`: `/clear-cache`, `/route-cache`, `/config-cache`, `/view-clear`, `/updateapp`.
- API routes have no auth and the `api` middleware group has no throttle. Reelo verify can return a POS key. Product search is public.
- `SendOtpMembership` echoes the OTP back to the browser; OTP is stored in `users.otp`.
- `adminer.php` existed on the live server (removed from this cleaned repo). Do not redeploy it. Debugbar is a dev dependency — keep `APP_DEBUG=false` in production.
- Permission checks are missing on some write/delete paths. Branch users must not be able to edit another branch’s rows by id (IDOR risk on edit/invoice URLs).

**Hardcoded HappyOn tenant**

- Logo `public/assets/images/happyon_logo.png`, page title “Happy On”, fallback email `contact@happyonplay.com`.
- Invoices: “HappyOn PrivateLimited”, Thaltej / Sindhubhavan address fallback, fallback GSTIN, cashier “HappyOn”, footer happyonplay.com.
- SMS and T&C copy name HappyOn / HappyOn Family / “gadget free” play.
- Review SMS uses a HappyOn-specific short link.
- SMS sender is venue-specific.

**Quality / architecture**

- Schema is not fully captured in runnable migrations; `database/New folder/` is a side directory.
- Fat controllers (`AdminController` ~1759 lines, `TicketController` ~1436, `MembershipController` ~900).
- Duplicate / typo model `MembershiBuy.php`. `PosSettingController` is empty. Leftover `default.php` / Hostinger placeholder `index.html` at repo root.
- `product_qty` is not decremented as inventory. No live “who is on the floor” timer board — only stored `kids.end_time`.
- Queue is sync; no real test suite; no payment gateway; no multi-tenancy (`tenant_id` does not exist).
- Repo was public when last checked — keep it private if it might regain dumps, `.env`, or customer data.

## Decisions so far

- Build the SaaS **from this Laravel codebase**, not a greenfield rewrite. Keep HappyOn as the first tenant.
- **Multi-tenancy (recommended for v1):** single MySQL database, a `tenants` (org) table, and `tenant_id` on tenant-owned data, plus a global scope. Keep `branch_id` as the outlet inside a tenant. Do **not** start with `stancl/tenancy` / DB-per-tenant unless isolation or compliance later requires it (heavier ops).
- **Upgrade path:** Laravel 8 → 9 → 10 → 11 (then 12 if needed). Do not jump major versions. Raise PHP toward 8.2+ as you go. Harden and reconstruct migrations before a large feature push.
- **SaaS billing:** Razorpay Subscriptions for tenant plans, separate from in-venue Cash/Card/UPI.
- **Onboarding / white-label:** each tenant supplies legal name, logo, GSTIN, SMS sender/templates, and domain or subdomain.
- Roles stay Spatie-based, but permissions must be enforced in controllers and scoped by tenant + branch.
- Suggested list prices (INR, per outlet / month, exclusive of GST; **suggestions only**): Starter ₹2,499; Growth ₹4,999; Chain ₹8,999+. Revisit after talking to other venues.

## Phased roadmap (effort is a rough week count for a small team)

**Phase 0 — Foundation (3–4 weeks).** Remove public Artisan routes; move secrets to env and rotate; lock down APIs; stop echoing OTPs; confirm GitHub repo is private; reconstruct a clean migration path; add a thin test harness; inventory IDOR/permission gaps.

**Phase 1 — MVP SaaS (8–12 weeks).** `tenants` + `tenant_id` + global scopes; tenant onboarding; white-label invoices/SMS/logo; configurable GST profile; Razorpay subscriptions; a live floor/timer view from existing `end_time`; tighten POS/open billing. Optionally start the Laravel 9/10 upgrade in this phase. HappyOn should run as tenant zero without losing branch behaviour.

**Phase 2 — Growth (8–10 weeks).** Online booking + online payment; digital waivers; WhatsApp (or tenant-owned channel); gift cards / pass wallets; membership auto-renew; audit log; staff permission audit. Aim to land on Laravel 11.

**Phase 3 — v2 (12–16 weeks).** Self-serve kiosks; wristband / colour-band / access-control hooks; real inventory and café modifiers; chain HQ dashboards; loyalty (replace or finish Reelo); public API; e-invoice if needed. Reconsider DB-per-tenant only if a clear requirement appears.

## Round log

_Each work round should add a dated entry: date (Asia/Calcutta), who/what agent, what changed, what is still open. Newest first._

<!--
### YYYY-MM-DD — short title
- Done:
- Open:
-->

## Requested feature: Visual POS ticketing screen (added 23 Nov 2026)
Owner request: a POS-style ticketing/billing screen where staff tap product cards with images to add items (like a Petpooja billing screen).
- Current state: products exist (`app/Models/Product.php`; admin add/edit/delete in `AdminController::addProduct` etc., routes `productPostData`, `edit-products-modal`, `delete-products`; branch-scoped via `branch_id`). Fields: name, price, qty, tax, description. No image, category, sort order, or active flag.
- Ticket screen `resources/views/happyView/tickets.blade.php` (~line 160) lists products as text "+ name" buttons calling `AddItems(...)`.
- Plan: migration adding `image_path`, `category_id` (new `product_categories` table), `sort_order`, `is_active`, `sku`; image upload + preview + validation in the product admin (store on `public` disk, resized thumbnails); new POS grid view: category tabs, search, image cards, cart panel with qty +/-, tax, coupon, payment mode, then existing ticket/invoice flow. Touch-friendly; must work at 768/1024/1440 widths (tablet counters).
