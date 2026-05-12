# Resupply Rocket

**Live Site:** test.resupplyrocket.com  
**Repo:** https://github.com/quarrytileshop/resupply  
**Description:** Multi-vendor B2B reorder platform where distribution companies (vendors) offer custom shopping lists to repeat business customers.

## Project Goal
A simple, mobile-first B2B reorder platform. Vendors own and maintain their private master catalogs (with super-admin assisted onboarding for new vendors). Customers copy/edit/maintain their own versions from linked vendors. All interactions are instant (no save buttons), mobile-optimized, and designed for non-coders.

## Platform Roles
| Role                  | Primary Responsibilities |
|-----------------------|--------------------------|
| **Super Admin**       | Monitors platform usage & analytics, bills vendors, assists with new vendor onboarding and initial catalog setup, handles overall platform oversight and support. |
| **Vendor Admin**      | Owns and maintains private master catalog (products, images, pricing, order multiples), pre-builds suggested shopping lists, assigns lists to customers. |
| **Organization Admin**| Manages company profile, invites/deletes users, requests account changes (e.g. credit card), views orders. |
| **Regular User**      | Places orders from assigned shopping lists. |

## Core Principles & Must-Preserve Behaviors
**DO NOT REMOVE, WEAKEN, OR CHANGE ANY ITEM BELOW WITHOUT EXPLICIT APPROVAL:**

- Mobile-first UX: extremely easy on phones; large tap targets, responsive tables, home-screen prompt after signup/login/form completion (iOS: Tap Share > Add to Home Screen; Android: Tap Menu > Add to Home Screen).
- All quantity/editable fields auto-update on type/blur/focus loss — NO “Add”, “Save”, or confirmation buttons anywhere.
- Direct editing everywhere (delivery address, PO#, notes, quantities) — changes apply immediately.
- “Send It!” button triggers rocket spin animation during processing.
- All orders email clean, professional HTML PO mirroring cart layout to the correct vendor + russellhb2b@gmail.com + customer email.
- Vendors own and maintain private master catalogs (super-admin provides assisted onboarding only).
- Vendors pre-build suggested shopping lists; customers copy/edit/maintain own versions from linked vendors.
- Shared company views, order history (shows who ordered), pricing per vendor.
- **Order Types**:
  1. **General Products**: Shopping lists show image/name/description/multiples/price/quantity input; typing quantity instantly syncs to cart preview; manual add lines (SKU optional, name, quantity). Navigation to Propane/Paint/Checkbox/Dashboard.
  2. **Propane**: Simple form with two preloaded rows (exchanges/new tanks) + notes + submit button (no visible cart). Propane-flagged companies redirect to this form on login (with links to other types + dashboard). Separate PO format/email.
  3. **Paint**: Guided questions (container size, type, sheen, brand/line, color). PO uses descriptions only (vendor assigns SKU later). Navigation to other types + dashboard.
  4. **Checkbox Shopping List**: No PO email; one user generates list with checkboxes for self/others (e.g. in-store). Supports adding from existing lists or manual lines. On completion, emails final checked list to russellhb2b@gmail.com — no vendor order.

- **Registration**: Two modes (join existing company with approved account number; sign up new company → pending + admin assigns account number). Admin-generated signup links; admin pre-registration (pre-fill name/email/company → send password-setup link).
- **Post-registration (after approval)**: Auto-generate fact sheet as professional HTML table email to russellhb2b@gmail.com for accounting.
- **Dashboard**: Admin-placed messages (dismissable once read / persistent until deleted); last three sent orders; buttons: “New Order”, “Reset Password”, “Edit Email/Phone”.
- **Cart view**: Mirrors emailed PO layout; editable delivery location/PO#/notes; direct editing on type/focus loss. Instructions text: “Select from any of your custom built shopping lists by typing the quantities you desire of each product.”
- **Shopping lists**: Display all assigned lists as scrollable tabs.
- **Draft Save**: Button below lists saves current order as draft (resumable/editable/deletable).
- **History page**: Previous orders table (order number/date/who ordered/view PO); manual archive button; auto-archive >60 days old; separate table for total ordered per SKU and last ordered date.
- **Super-admin portal**: Usage monitoring, billing, vendor assistance (catalog assist mode), organization & user management, bulk import assistance.

Future: API-friendly and modular.

## Current Architecture
- Flat PHP structure (all .php files in root for simplicity on shared hosting — no Composer, no frameworks, no MVC).
- Key folders: `css/`, `icons/`, `images/`, `product-images/`, `vendor/PHPMailer/src/`.
- Database: MySQL (via `database-setup.sql`) with tables for `vendors`, `organizations` (customers), `users` (role-based), `catalog_items` (scoped by `vendor_id`), shopping lists, orders, etc.
- Admin layers:
  - Super-admin (`admin_*.php` files): monitoring, billing, assisted catalog help.
  - Vendor self-service (planned next): full catalog & list ownership.
  - Organization admins (`organization_admin.php`): company self-service.
- Goal: simple, secure, mobile-first, non-coder maintainable on GoDaddy shared cPanel.

## Tech Stack & Context
- GoDaddy shared cPanel
- PHP 8.x + MySQL (PDO)
- **GoDaddy Setup Instructions** (follow exactly):
  1. Log in to cPanel → File Manager → upload entire repo to `public_html` (or a subdomain folder).
  2. Go to MySQL Databases → create a new database + database user (grant full privileges).
  3. In phpMyAdmin, import the file `database-setup.sql`.
  4. Edit `config.php` (in cPanel File Manager) with your exact DB credentials, email settings, and domain — **never commit config.php to Git**.
  5. Set folder permissions: 755 for folders, 644 for .php files.
  6. Point your domain/subdomain to the upload location via cPanel Domains or Zone Editor.
- Domain: test.resupplyrocket.com

## Development Workflow
See DEVELOPMENT.md for Grok-specific guidelines when requesting code changes.

---
**Last updated:** Tuesday, May 12, 2026
