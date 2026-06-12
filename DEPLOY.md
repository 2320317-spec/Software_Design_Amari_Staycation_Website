# Deploying Amari Staycation to Hostinger

A step-by-step checklist for publishing this PHP + MySQL site on Hostinger (hPanel).

---

## Prerequisites
- A Hostinger plan with PHP + MySQL (Premium/Business shared hosting or any hPanel plan).
- A domain (or use the free temporary subdomain to test first).

---

## 1. Upload the files
1. hPanel → **Files → File Manager** → open **`public_html`**.
2. Upload the **contents** of this project into `public_html` so that `index.php`
   sits at `public_html/index.php` (NOT `public_html/amari-staycation/index.php`).
   - Tip: zip the project locally, upload the zip, then **Extract** in File Manager.

## 2. Create the database
1. hPanel → **Databases → MySQL Databases**.
2. Create a database + user. Note the generated **name**, **user**, **password**
   (Hostinger prefixes them, e.g. `u123456_amari`).
3. Open **phpMyAdmin** for that database → **Import** tab.
4. Upload **`database/amari_db_hostinger.sql`** (the table-only file — it imports
   into the database you selected). Click **Go**.
   - Do NOT use `database/amari_db.sql` here — that one contains `CREATE DATABASE amari_db`
     and is meant for local XAMPP.

## 3. Configure credentials (IMPORTANT)
1. In File Manager, go to `includes/`.
2. Copy **`secrets.example.php`** to a new file named **`secrets.php`**.
3. Edit `secrets.php` with your Hostinger DB credentials from step 2, and your
   Gmail App Password:
   ```php
   return [
       'db' => [
           'host' => 'localhost',
           'user' => 'u123456_amari',
           'pass' => 'your-db-password',
           'name' => 'u123456_amari',
       ],
       'mail' => [
           'user'      => 'amaristaycation08@gmail.com',
           'pass'      => 'your-gmail-app-password',
           'from_name' => 'Amari Alabang',
       ],
   ];
   ```
   `db-config.php` and `mailer.php` read from this file automatically — no other code changes needed.

## 4. Set the PHP version
hPanel → **Advanced → PHP Configuration** → select **PHP 8.0 or higher**.
(`mysqli` is enabled by default — no extra config required.)

## 5. Point the domain & go live
- Assign your domain to the site in hPanel (or use the temporary subdomain).
- Open the site and test:
  - [ ] Homepage loads (images, fonts, advisory modal)
  - [ ] Booking wizard works end-to-end (Stay → Rooms → Rates → Checkout)
  - [ ] A test booking saves
  - [ ] Admin login works (`/admin`)
  - [ ] Approving a booking sends the confirmation email

---

## Security must-dos
- [ ] **Delete the SQL files from the live server** after import — otherwise they're
      downloadable at `yoursite.com/database/amari_db.sql` and expose guest data.
      (Delete the whole `database/` folder on the server, or block it.)
- [ ] **Regenerate the Gmail App Password.** The old one was committed to git history,
      so treat it as compromised: Google Account → Security → App Passwords → revoke the
      old one, create a new one, put it in `secrets.php` only.
- [ ] Confirm **`includes/secrets.php` is never committed** (it's gitignored). It exists
      only on each machine/server, created by hand.
- [ ] Make sure the **admin password** is strong (the panel is public on the internet).

---

## Updating the live database later
If you change data locally and want the live DB to match, re-export and re-import:
```bash
"C:\xampp\mysql\bin\mysqldump.exe" -u root amari_db --routines > database/amari_db_hostinger.sql
```
Then import that file via phpMyAdmin on Hostinger (it will overwrite the existing tables).
