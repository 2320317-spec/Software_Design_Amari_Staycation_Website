# 🏨 Amari Staycation - Alabang
**Internal Management Platform (Computer Engineering Group Project)**

Welcome to the team! This repository contains the full source code for the Amari Staycation booking system.

---

## 🛠️ System Requirements
* **Server Stack:** XAMPP (Apache & MySQL/MariaDB)
* **PHP Version:** 8.0 or higher
* **Database Port:** 3306 (Default)

---

## 🚀 Getting Started (Setup Instructions)

Follow these steps exactly to mirror the development environment on your local machine:

### 1. Clone the Repository
Open your terminal in `C:/xampp/htdocs/` and run:
`git clone https://github.com/[YOUR_USERNAME]/[YOUR_REPO_NAME].git`

### 2. Initialize the Database 🗄️
1. Start **XAMPP Control Panel** and turn on **Apache** and **MySQL**.
2. Go to [localhost/phpmyadmin](http://localhost/phpmyadmin).
3. Create a new database named `amari_db`.
4. Click on the `amari_db` database, go to the **Import** tab.
5. Choose the file located in: `/db/amari_db.sql` and click **Go**.

### 3. Configure Database Connection
1. Open `includes/db-config.php`.
2. Ensure your credentials match your local setup (Username: `root`, Password: ``).
3. If your MySQL is running on a custom port, update the `$servername` to `localhost:YOURPORT`.

---

## 📜 The Git Protocol (Team Rules)
To avoid breaking the "Main Blueprint," please follow these rules:
1. **PULL FIRST:** Always run `git pull origin main` before you start typing.
2. **COMMIT SMALL:** Commit every time you finish a small feature (e.g., "Styled the login button").
3. **PUSH OFTEN:** Push your work at the end of every session.
