# Database Setup — Amari Staycation

This folder contains the full database export (`amari_db.sql`) needed to run the site.

## How to import (choose one)

### Option A — phpMyAdmin (easiest, XAMPP)
1. Start **Apache** and **MySQL** in the XAMPP Control Panel.
2. Go to <http://localhost/phpmyadmin>.
3. Click the **Import** tab (top menu).
4. Click **Choose File**, select `database/amari_db.sql`, then click **Go** / **Import**.
5. The `amari_db` database and all its tables will be created automatically.

### Option B — Command line
```bash
# From the project root, in the XAMPP shell or terminal:
mysql -u root < database/amari_db.sql
```
(If your MySQL root account has a password, add `-p`.)

## Connection settings
The app reads these in `includes/db-config.php` (defaults for a standard XAMPP install):

| Setting  | Value       |
|----------|-------------|
| Host     | `localhost` |
| User     | `root`      |
| Password | *(empty)*   |
| Database | `amari_db`  |

If your MySQL uses a different user/password, update `includes/db-config.php` to match.

## Re-exporting (after you change data and want to update the repo copy)
```bash
"C:\xampp\mysql\bin\mysqldump.exe" -u root --databases amari_db --add-drop-database --routines --events > database/amari_db.sql
```
