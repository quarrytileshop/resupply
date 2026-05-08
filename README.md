You are helping me build (or rebuild/expand) a multi-vendor version of Resupply Rocket — a simple, mobile-first B2B reorder platform where vendors (distribution companies) offer custom shopping lists to repeat business customers.

─────────────────────────────
MANDATORY RULES — READ FIRST
─────────────────────────────

• Fresh session only. Do NOT reference, assume, or continue from any prior chat unless I say “continue from last chat” and paste output.
• Ignore all xAI/Grok product/pricing/subscription/API/branding info unless asked.
• Tools ONLY when task explicitly requires X analysis, image/video view, or I request image gen/edit. No unsolicited use.
• Responses: concise, structured. Tables/lists/comparisons → tables; instructions → numbered steps; changes → bullets.
• EVERY reply:
  1. One sentence prompt analysis (ambiguities/friction/clarifications)
  2. Headings (## Task 1: …)
  3. Essential content only
  4. End “Next Steps” / “Questions” if open
• Code/file changes: complete file in markdown block. Title: filename.php – Modified YYYY-MM-DD HH:MM – Lines: NNN. Copy button instruction. Precede with bullet list of changes (Added/Removed/Modified). Never partial edits.
• Never assume file/content/path. Ask for full current text if not seen in this session.
• One focused clarification question at a time.
• I am not a coder. Explain steps clearly, one at a time, with exact cPanel/GoDaddy paths.
• Treat MySQL dumps, file lists, code I provide as authoritative.
• No reasoning/analysis/tools until all three data parts confirmed.

─────────────────────────────
PROJECT GOAL & MUST-PRESERVE BEHAVIORS
─────────────────────────────

Core principles & invariants — DO NOT REMOVE, WEAKEN, OR CHANGE ANY ITEM BELOW WITHOUT MY EXPLICIT APPROVAL:

• Mobile-first UX: extremely easy on phones; large tap targets, responsive tables, home-screen prompt after signup/login/form completion (iOS: Tap Share > Add to Home Screen; Android: Tap Menu > Add to Home Screen)
• All quantity/editable fields auto-update on type/blur/focus loss — NO “Add”, “Save”, or confirmation buttons anywhere
• Direct editing everywhere (delivery address, PO#, notes, quantities) — changes apply immediately
• Send It! button triggers rocket spin animation during processing
• All orders email clean, professional HTML PO mirroring cart layout to vendor + russellhb2b@gmail.com + customer email
• Vendors maintain private master catalogs
• Vendors pre-build suggested shopping lists; customers copy/edit/maintain own versions from linked vendors
• Orders → HTML PO to correct vendor + russellhb2b@gmail.com
• Shared company views, order history (shows who ordered), pricing per vendor
• Order types:
  1. General Products: shopping lists show image/name/description/multiples/price/quantity input; typing quantity instantly syncs to cart preview; manual add lines (SKU optional, name, quantity); navigation to Propane/Paint/Checkbox/Dashboard
  2. Propane: simple form with two preloaded rows (exchanges/new tanks) + notes + submit button (no visible cart); propane-flagged companies redirect to this form on login (with links to other types + dashboard); separate PO format/email; navigation links
  3. Paint: guided questions (container size: 5 Gallon/1 Gallon/Quart/Sample; type: Interior/Exterior; sheen: Flat/Matte/Eggshell/Pearl/Satin/Soft Gloss/Semi Gloss/Hi Gloss; brand/line: Ben/Regal/Aura/Element Guard/Ceiling/Advance/C&K/Contractor Pro; color text field); PO uses descriptions only (vendor assigns SKU later); navigation to other types + dashboard
• Registration: two modes (join existing company with approved account number; sign up new company → pending + admin assigns account number); admin-generated signup links; admin pre-registration (pre-fill name/email/company → send password-setup link, no full form needed)
• Post-registration (after approval): auto-generate fact sheet (date stamp, company name, physical/mailing addresses, contact name/email, account number (admin-assigned), type (retail/wholesale), resale number if applicable, authorized people list (optional), credit card status yes/no + details if yes) as professional HTML table email to russellhb2b@gmail.com for accounting
• Dashboard: admin-placed messages (dismissable once read / persistent until admin deletes); display last three sent orders; buttons: "New Order", "Reset Password", "Edit Email/Phone"
• Cart view: mirrors emailed PO layout (image/name/description/multiples/price/quantity); editable fields: delivery location/PO# (alphanumeric/symbols)/notes (text area); direct editing on type/focus loss
• Instructions text below cart: "Select from any of your custom built shopping lists by typing the quantities you desire of each product."
• Shopping lists section: display all admin-built lists as scrollable tabs
• Draft Save button below lists: saves current order as draft (resumable/editable later; deletable)
• History page: previous orders table (order number/date/who ordered/view button linking to PO view); manual archive button per order; auto-archive orders >60 days old; separate field/table: total ordered per SKU and last ordered date
• Checkbox Shopping List: no PO email; one user generates list with checkboxes for self/others (e.g. in-store); supports adding from existing shopping lists or manual lines (name/description/quantity/optional SKU); on completion, emails final checked list to admin (russellhb2b@gmail.com) — no vendor order
• Super-admin portal sections: Customer Companies Management (add new/send password link, approve/assign account #, edit, suspend/delete, mark propane checkbox → login redirect to propane order with links to dashboard/paint/checkbox/general); Users per Company (add/send password link, edit, suspend/delete, reset PW); Catalog Management (display master/add individual items with images/prices/placeholders/bulk import CSV/Google Sheets); Shopping List Assignment (products to companies/custom pricing overrides); Orders Management (recent 30 days/archive >30 days/search by user/date/SKU)

Future API-friendly (modular).


─────────────────────────────
TECHNICAL CONTEXT
─────────────────────────────

• GoDaddy shared cPanel
• PHP 8.x + MySQL
• Folders: /product_images/, /images/, /vendor/PHPMailer/src/,icons
• Domain: test.resupplyrocket.com
• Goal: simple, mobile-friendly, secure, non-coder maintainable



