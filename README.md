# Resupply Rocket

**Live Site:** test.resupplyrocket.com  
**Repo:** https://github.com/quarrytileshop/resupply  
**Description:** Multi-vendor B2B reorder platform where distribution companies (vendors) offer custom shopping lists to repeat business customers.

## Project Goal
A simple, mobile-first B2B reorder platform. Vendors maintain private master catalogs and pre-build suggested shopping lists. Customers copy/edit/maintain their own versions from linked vendors. All interactions are instant (no save buttons), mobile-optimized, and designed for non-coders.

## Core Principles & Must-Preserve Behaviors
**DO NOT REMOVE, WEAKEN, OR CHANGE ANY ITEM BELOW WITHOUT EXPLICIT APPROVAL:**

- Mobile-first UX: extremely easy on phones; large tap targets, responsive tables, home-screen prompt after signup/login/form completion (iOS: Tap Share > Add to Home Screen; Android: Tap Menu > Add to Home Screen).
- All quantity/editable fields auto-update on type/blur/focus loss — NO “Add”, “Save”, or confirmation buttons anywhere.
- Direct editing everywhere (delivery address, PO#, notes, quantities) — changes apply immediately.
- “Send It!” button triggers rocket spin animation during processing.
- All orders email clean, professional HTML PO mirroring cart layout to the correct vendor + russellhb2b@gmail.com + customer email.
- Vendors maintain private master catalogs.
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
- **Shopping lists**: Display all admin-built lists as scrollable tabs.
- **Draft Save**: Button below lists saves current order as draft (resumable/editable/deletable).
- **History page**: Previous orders table (order number/date/who ordered/view PO); manual archive button; auto-archive >60 days old; separate table for total ordered per SKU and last ordered date.
- **Super-admin portal**: Customer Companies Management, Users per Company, Catalog Management (with images/prices/bulk CSV import), Shopping List Assignment (custom pricing), Orders Management.

Future: API-friendly and modular.

## Tech Stack & Context
- GoDaddy shared cPanel
- PHP 8.x + MySQL (PDO)
- Folders: `/product_images/`, `/images/`, `/vendor/PHPMailer/src/`, `/icons/`, `/css/`
- Domain: test.resupplyrocket.com
- Goal: simple, mobile-friendly, secure, non-coder maintainable

## Development Workflow
See DEVELOPMENT.md for Grok-specific guidelines when requesting code changes.

---

**Last updated:** 2026-05-08
