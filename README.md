# KopiTarikBn

KopiTarikBn is a PHP coffee shop website with a customer section and an administrator dashboard. This guide explains how to run the project locally using XAMPP on Windows.

## Features

**Customers can:**

- Browse drinks by category.
- Customise drinks and manage their cart.
- Register an account and sign in.
- Place pickup orders and view their order history.
- Submit or update drink reviews.
- Use the drink recommendation quiz.

**Administrators can:**

- Add, edit and delete products.
- Manage product categories, images, stock and availability.
- View orders and their drink customisations.
- Update order statuses.
- View sales summaries and best-selling products.

Customers and administrators use the same login page. After signing in, each account is redirected to the appropriate section.

## Before you start

You will need:

- XAMPP for Windows, with Apache, PHP 8.x and MySQL/MariaDB.
- A web browser.
- The project folder named `KopiTarikBn`.
- The latest **registered-customer-only** `KopiTarikDatabase.sql` file.

PHP must have `pdo_mysql`, `mbstring` and `fileinfo` enabled. These are used for the database connection, text handling and image validation. The database was checked using XAMPP MariaDB 10.4.32.

> **Current version note:** The latest SQL requires every order to belong to a registered customer. Earlier project ZIPs still contain guest buttons and guest checkout code because the last update changed SQL only. Register and sign in before ordering. Removing those buttons and adding a login requirement to the checkout page remain website changes to complete; this database update does not make those changes automatically.

## 1. Put the project in XAMPP

Download or clone the project, then place its folder here:

```text
C:\xampp\htdocs\KopiTarikBn
```

The folder should contain files such as:

```text
KopiTarikBn/
├── actions/
│   └── login_process.php
├── admin/
├── assets/
├── config/
├── include/
│   ├── db_connect.php
│   ├── security.php
│   └── shop.php
├── includes/
├── tools/
├── index.php
├── login.php
├── register.php
├── menu.php
├── drink.php
├── cart.php
├── checkout.php
├── orders.php
├── reviews.php
├── quiz.php
└── KopiTarikDatabase.sql
```

Avoid an extra nested folder such as:

```text
C:\xampp\htdocs\KopiTarikBn\KopiTarikBn\index.php
```

If updating an existing project, back up its folder first. Keep the existing `.git` folder if you want to retain its Git history.

## 2. Start XAMPP

1. Open **XAMPP Control Panel**.
2. Click **Start** beside **Apache**.
3. Click **Start** beside **MySQL**.
4. Confirm both services are running.

Keep them running while using the website.

## 3. Import the database

1. Open [phpMyAdmin](http://localhost/phpmyadmin/).
2. Open the **Import** tab from the server/home page.
3. Select the latest `KopiTarikDatabase.sql` file.
4. Click **Import** or **Go**, depending on the phpMyAdmin version.
5. Wait for the successful import message.
6. Confirm that **KopiTarikDatabase** appears in the left sidebar.

The script creates and selects the database automatically. You do not need to create its tables manually.

The latest dataset contains:

| Table         | Dummy records |
| ------------- | ------------: |
| `admin`       |             1 |
| `customer`    |            24 |
| `category`    |             5 |
| `menu_item`   |            23 |
| `orders`      |           120 |
| `order_items` |           308 |
| `reviews`     |            60 |

All 120 orders belong to registered customers. Customer names and contact details are fictional. Product stock represents the remaining stock in the demo dataset; do not subtract historical order quantities again.

The old `coffee_shop.sql` file is no longer the setup database for this version. Use `KopiTarikDatabase.sql`.

## 4. Check the database connection

Open:

```text
include/db_connect.php
```

For a default local XAMPP installation, its settings should be:

```php
$host = getenv("KOPI_DB_HOST") ?: "localhost";
$dbname = getenv("KOPI_DB_NAME") ?: "KopiTarikDatabase";
$username = getenv("KOPI_DB_USER") ?: "root";
$password = getenv("KOPI_DB_PASSWORD") ?: "";
```

If your MySQL root account has a password, enter that password in the connection configuration or supply it through `KOPI_DB_PASSWORD`.

If the file still uses `coffee_shop` as its default database name, change it to `KopiTarikDatabase`. Environment variables override the defaults, so update `KOPI_DB_NAME` too if you previously configured it.

Both customer and admin pages share this PDO connection. You do not need a separate customer database connection.

## 5. Open the website

With Apache and MySQL running, open:

- [Homepage](http://localhost/KopiTarikBn/)
- [Login](http://localhost/KopiTarikBn/login.php)
- [Customer registration](http://localhost/KopiTarikBn/register.php)

Use these localhost addresses. Opening `index.php` directly from File Explorer will not execute PHP.

If you changed Apache's port, include that port in the URL, for example:

```text
http://localhost:8080/KopiTarikBn/
```

If you previously used the old database, sign out before signing in to the new one. A private browser window is another option for the first test.

## 6. Sign in with a demo account

### Administrator

```text
Email:    admin@kopitarik.test
Password: KopiAdmin2026!
```

The admin username is `demo_admin`, but the login form requires the **email address**. Successful admin login opens the dashboard.

### Customer

```text
Email:    customer01@kopitarik.test
Password: KopiCustomer2026!
```

There are 24 dummy customer accounts, from `customer01@kopitarik.test` through `customer24@kopitarik.test`. They all use the customer password above. You can also create your own account using the registration page.

These credentials are for local demonstrations. Replace the demo accounts before using the project publicly.

## 7. Try the main functions

### Customer walkthrough

1. Register an account or sign in with a demo customer.
2. Open the menu and select an available drink.
3. Choose the size, milk and other customisations.
4. Add the drink to the cart.
5. Add another version of the same drink to check that its customisation stays separate.
6. Update a quantity or remove a cart item.
7. Place a pickup order while signed in.
8. Check **My Orders** for the new order.
9. Submit a review. Submitting another review for the same drink updates your existing review.

### Admin walkthrough

1. Sign out of the customer account.
2. Sign in using the admin email and password.
3. Check the dashboard, products and categories.
4. Open the customer order and check its customisations and total.
5. Update its status.
6. Check the reports. Revenue includes **Completed** orders only.

Orders are pickup requests. The project does not collect online payments.

## Optional: create another administrator

The latest SQL already includes a working demo administrator. To create another one, open PowerShell in the project folder and run:

```powershell
cd "C:\xampp\htdocs\KopiTarikBn"
C:\xampp\php\php.exe tools/create_admin.php myadmin myadmin@example.com
```

Enter a password of 12–72 bytes when prompted. The password is visible while typing in that terminal. Choose a username and email that are not already registered as an administrator.

This tool runs from the terminal only. Do not try to open it through the browser.

## Troubleshooting

### “Database connection failed.”

- Confirm MySQL is running in XAMPP.
- Confirm the database is named exactly `KopiTarikDatabase`.
- Check the host, username and password in `include/db_connect.php`.
- Check whether environment variables are overriding those values.

### “Invalid email or password.”

- Use the email address, not the admin username.
- Check the spelling and capital letters in the password.
- Confirm you imported the latest SQL and the website is connected to the new database.
- Sign out or try a private browser window if you recently switched databases.

### The project page returns 404

- Check that `index.php` is directly inside `C:\xampp\htdocs\KopiTarikBn`.
- Check that Apache is running and that the URL uses its configured port.
- Check for an accidental extra nested project folder.

### Styles or images do not load

- Confirm the complete `assets` folder was copied.
- Press **Ctrl + F5** to refresh cached styles.
- Product images are stored under `assets/images/products`, with a fallback to `assets/img`.
- An internet connection is needed for the external Google Fonts and icon stylesheet; ordinary fonts remain available as fallbacks.

### “Invalid or expired form.”

Reload the page and submit the form again. This can happen when an older tab contains a CSRF token from before you signed in or signed out.

### An order cannot be placed

- Sign in as a registered customer first. The latest database rejects orders without a customer ID.
- Check that the cart contains available products and quantities do not exceed stock.
- If using a promo code, the supported demo code is `BREW10`.
- Confirm the website files and latest database schema belong to the same project version.

### Apache or MySQL will not start

Read the relevant XAMPP log for the reported error. Another application or service may already be using the configured port. If you change Apache's port, update the website URL accordingly. If MySQL uses a non-default port, the PDO connection's DSN also needs that port.

## Development notes

- Database: `KopiTarikDatabase`.
- Shared authentication: `actions/login_process.php` and `include/security.php`.
- Admin access checks: `include/admin_auth.php`.
- Customer helpers: `config/app.php` and `include/shop.php`.
- Customer styles: `assets/customer.css` and `assets/design.css`.
- Homepage-matched login styles: `assets/login.css`.
- Admin styles: `assets/admin.css`.
- Product prices and stock are checked on the server during checkout.
- Different customisations have separate cart lines.
- Order items store snapshots so their names remain visible after a product is edited or deleted.
- Account deletion is restricted when the customer has existing orders.
- Apache `.htaccess` rules protect internal files. Their enforcement depends on Apache's `AllowOverride` configuration.

The SQL was checked with XAMPP MariaDB, and the merged application passed isolated integration checks. Complete the walkthrough above in your own XAMPP installation before presenting or submitting the project.
