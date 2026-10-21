# KopiTarikBn

KopiTarikBn is a PHP and MySQL coffee shop web application developed for a Web Development assignment.

The system includes a customer website and an administrator dashboard for managing products, categories, orders and sales reports.

## Features

### Customer

Customers can:

- Register and log in
- Browse drinks by category
- View drink details
- Customise drinks
- Add products to cart
- Update or remove cart items
- Complete a simulated payment
- View previous orders
- Submit and update product reviews
- Use the drink recommendation quiz

> Customers must be logged in before completing checkout.

### Administrator

Administrators can:

- Log in through the shared login page
- View the admin dashboard
- Add, edit and delete products
- Upload product images
- Manage product stock and availability
- Add, edit and delete categories
- View customer orders
- View drink customisations
- Process order status
- Cancel and refund eligible orders
- View sales reports
- View best-selling products

## Order Flow

A successful simulated payment creates an order with:

```text
Order Status: Pending
Payment Status: Paid
Payment Method: Demo
```

The administrator can then process the order through:

```text
Pending → Preparing → Ready → Completed
```

A Pending order can also be cancelled:

```text
Pending → Cancelled
```

If a paid order is cancelled, the payment becomes `Refunded` and the product stock is restored.

## Technologies Used

- HTML
- CSS
- JavaScript
- PHP
- PDO
- MySQL / MariaDB
- Apache / XAMPP

## Project Structure

```text
KopiTarikBn/
│
├── actions/
│   └── login_process.php
│
├── admin/
│   ├── include/
│   ├── dashboard.php
│   ├── products.php
│   ├── add_product.php
│   ├── edit_product.php
│   ├── delete_product.php
│   ├── categories.php
│   ├── orders.php
│   ├── view_order.php
│   ├── report.php
│   └── logout.php
│
├── assets/
│   ├── images/products/
│   ├── img/
│   ├── admin.css
│   ├── customer.css
│   ├── design.css
│   ├── experience.js
│   └── quiz-journey.js
│
├── config/
│   └── app.php
│
├── include/
│   ├── admin_auth.php
│   ├── db_connect.php
│   ├── product_validation.php
│   ├── security.php
│   └── shop.php
│
├── includes/
│   ├── header.php
│   └── footer.php
│
├── tools/
│   └── create_admin.php
│
├── about.php
├── cart.php
├── checkout.php
├── drink.php
├── index.php
├── login.php
├── logout.php
├── menu.php
├── orders.php
├── quiz.php
├── register.php
├── reviews.php
└── KopiTarikDatabase.sql
```

## Database

Database name:

```text
KopiTarikDatabase
```

Main tables:

```text
admin
customer
category
menu_item
orders
order_items
reviews
```

The supplied demo database contains approximately:

| Table       | Records |
| ----------- | ------: |
| Admin       |       1 |
| Customers   |      24 |
| Categories  |       5 |
| Products    |      23 |
| Orders      |     125 |
| Order Items |     316 |
| Reviews     |      61 |

Order items store product and customisation snapshots so historical orders remain readable even if a product is later edited or deleted.

## Installation

### 1. Copy the project

Place the project inside:

```text
C:\xampp\htdocs\KopiTarikBn
```

### 2. Start XAMPP

Start:

```text
Apache
MySQL
```

### 3. Import the database

Open:

```text
http://localhost/phpmyadmin/
```

Import:

```text
KopiTarikDatabase.sql
```

The database used by the project should be:

```text
KopiTarikDatabase
```

### 4. Open the website

Visit:

```text
http://localhost/KopiTarikBn/
```

## Demo Accounts

### Administrator

```text
Email: admin@kopitarik.test
Password: KopiAdmin2026!
```

### Customer

```text
Email: customer01@kopitarik.test
Password: KopiCustomer2026!
```

The demo customer accounts range from:

```text
customer01@kopitarik.test
```

to:

```text
customer24@kopitarik.test
```

and use the same demo password.

## Security

The project includes:

- Password hashing with `password_hash()`
- Password verification with `password_verify()`
- PDO prepared statements
- CSRF protection
- Session regeneration after login
- Admin authentication checks
- Server-side validation
- Product image validation
- File upload restrictions
- Protected internal folders using `.htaccess`
- Transaction-based checkout
- Duplicate checkout protection

## Demo Workflow

### Customer

```text
Login
→ Menu
→ Select Drink
→ Customise Drink
→ Add to Cart
→ Checkout
→ Simulated Payment
→ My Orders
```

### Administrator

```text
Login
→ Dashboard
→ Orders
→ View Order
→ Preparing
→ Ready
→ Completed
```

Administrators can also manage products, categories and sales reports.

## Notes

- Payments are simulated for demonstration purposes only.
- No real payment gateway is used.
- Orders require a registered customer account.
- Product stock is checked again during checkout.
- Different drink customisations are stored as separate cart items.
- Sales reports exclude refunded transactions.
- The project is intended for educational use.

## Optional Admin Creation

A new administrator can be created from the command line using:

```text
tools/create_admin.php
```

Example:

```powershell
C:\xampp\php\php.exe tools/create_admin.php admin2 admin2@example.com
```

The script securely hashes the new administrator password before saving it.

## Troubleshooting

If the database cannot connect:

- Make sure MySQL is running.
- Confirm the database is named `KopiTarikDatabase`.
- Check `include/db_connect.php`.

If login fails:

- Use the email address instead of the username.
- Check the password carefully.
- Confirm the latest database has been imported.

If CSS or images do not update:

```text
Ctrl + F5
```

to perform a hard refresh.

---

**KopiTarikBn — Web Development Project**
