# Resupply Rocket

**Live Test Site:** https://test.resupplyrocket.com  
**Repository:** https://github.com/quarrytileshop/resupply

**Professional B2B Multi-Vendor Reorder Platform** for Quarry Tile Shop.  
Vendors provide custom shopping lists to their repeat business customers. Orders are placed instantly with professional Purchase Order emails.

## Billing & Usage Model (Important)

**Vendors are billed monthly by Super Admin based on the following rules:**

- Each Vendor receives **2 free organization customers**.
- For every **additional organization** beyond the first 2, the Vendor is charged a **flat monthly fee**.
- The fee is only charged if the organization **actually uses the application** during the calendar month (any activity such as placing an order, editing a shopping list, logging in, viewing reports, etc.).
- Whether the organization uses the app once or 100 times in the month, the fee is the **same flat rate**.
- If an organization has **no activity** in a given calendar month, **no fee** is generated for that organization.
- Super Admin has full visibility into all usage across the platform and handles billing to vendors.
- Vendor Admins can monitor usage of the organizations they serve.

## Platform Roles

| Role                  | Primary Responsibilities |
|-----------------------|--------------------------|
| **Super Admin**       | Platform oversight, usage monitoring, **billing vendors**, vendor onboarding, catalog support, user & organization management. |
| **Vendor Admin**      | Maintains private master catalog, creates suggested shopping lists, assigns lists to customers, monitors organization usage. |
| **Organization Admin**| Manages company users, company profile, views orders. |
| **Regular User**      | Places orders from assigned shopping lists. |

## Core Principles & Must-Preserve Behaviors

**These rules must not be weakened or removed without explicit approval:**

- **Mobile-first UX** — Extremely easy to use on phones with large tap targets.
- **Instant editing everywhere** — Quantity fields, delivery address, PO#, notes, etc. auto-save on blur, type, or focus loss. **No "Save" buttons**.
- **"Send It!" button** — Triggers rocket animation during processing.
- **Professional HTML emails** — All orders send clean, well-formatted Purchase Order emails that mirror the cart/shopping list layout.
- **Four distinct order types** with specific behaviors (General, Propane, Paint, Checkbox).
- Vendors own and maintain their **private master catalogs**.
- Shopping lists displayed as **scrollable tabs**.
- Dashboard shows dismissable admin messages and recent orders.
- Registration supports joining existing companies or creating new ones (pending approval).

## Current Architecture (v2.0 – Professional Rewrite)

- Clean, professional plain-PHP structure optimized for shared GoDaddy cPanel hosting.
- No frameworks or Composer (keeps deployment simple).
- Key folders: `includes/`, `admin/`, `orders/`, `vendor/`, `ajax/`
- Secure session handling, CSRF protection, multi-tenant data isolation.
- External database config loaded from outside web root.
- Modern PDO database layer with utf8mb4 support.

## Tech Stack

- PHP 8.x + MySQL (PDO with prepared statements)
- Bootstrap 5 + Font Awesome
- PHPMailer for professional emails
- GoDaddy shared cPanel hosting

## GoDaddy Deployment Instructions

1. Upload the entire `resupply` folder to your public_html (or subdomain folder).
2. Create a MySQL database and user in cPanel.
3. Import the latest database schema (outside public web root recommended for security).
4. Place database credentials in `../../../resupply_db_config.php` (outside web root).
5. Set proper folder permissions (755 for folders, 644 for .php files).
6. Update `BASE_URL` in `includes/config.php` if needed.

## Future Enhancements

- Full vendor self-service catalog management
- Advanced reporting and invoicing
- API endpoints for future integrations

---

**Last updated:** Friday, May 15, 2026  
**Version:** v2.0 – Complete Professional Overhaul