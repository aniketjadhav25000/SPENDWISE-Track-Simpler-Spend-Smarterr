# SPENDWISE
### "Personal Finance Architect"

**🔗 Live Demo:** [https://daily-expense.fwh.is/](https://daily-expense.fwh.is/)

SpendWise is a high-performance, responsive expense management system designed to provide deep financial clarity. By combining a robust PHP/MySQL backend with a modern, "app-like" frontend, SpendWise helps users track outflows, visualize spending habits via heatmaps, and monitor their wallet health in real-time.

---

## ✨ Key Features

### 📊 Visual Analytics
* **Activity Flow (Heatmap):** A GitHub-inspired 31-day activity map that tracks spending intensity using color-coded logic.
* **Category Clusters:** Dynamic, floating bubble visualizations that represent spending distribution across various categories.

### 📱 Mobile-First Experience
* **Adaptive Layout:** Optimized for desktop and mobile, with a persistent bottom navigation bar for mobile users.
* **Quick Actions:** Strategic placement of "Add Expense" and "History" buttons at the top of the mobile view for rapid entry.

### 🛠️ Core Functionality
* **Wallet Health Monitor:** Real-time status alerts (Good, Warning, Critical) based on custom spending thresholds.
* **Full CRUD History:** A centralized history tab with desktop-responsive tables and mobile-responsive card views.
* **Smart Sidebar:** A professional dashboard sidebar featuring a dynamic User Profile avatar and secure session management.

---

## 🛠️ Tech Stack

* **Frontend:** Tailwind CSS, Lucide-JS Icons, Google Fonts (Inter).
* **Backend:** PHP (Procedural).
* **Database:** MySQL.
* **Security:** Session-based authentication and secure logout workflows.

---

## 🚀 Installation & Local Setup

1.  **Clone the Repository**
    ```bash
    git clone https://github.com/aniketjadhav25000/SPENDWISE-Track-Simpler-Spend-Smarter.git
    cd spendwise
    ```

2.  **Database Configuration**
    * Create a database named `spendwise`.
    * Import your SQL schema or create the `users` and `expenses` tables.
    * Ensure the `expenses` table contains: `id`, `user_id`, `title`, `amount`, `category`, and `expense_date`.

3.  **Environment Setup**
    * Navigate to `config/db.php`.
    * Update the `$conn` variables with your local database credentials (host, user, password, db_name).

4.  **Launch**
    * Move the project to your local server directory (e.g., `htdocs` for XAMPP).
    * Access the app at `http://localhost/spendwise`.

---

## 📂 Project Structure

* `/auth`: Login and Registration logic.
* `/config`: Database connection and global settings.
* `/expenses`: CRUD operations (List, Add, Edit, Delete, Reports).
* `dashboard.php`: The main visual analytics hub.
* `profile.php`: User management and profile settings.

---

**Developed for SPENDWISE — Achieving financial clarity, one transaction at a time.**
