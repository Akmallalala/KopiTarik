# KopiTarikBn — merged customer and admin project

## What is included
The original admin dashboard, products/categories, order management, reporting and shared login, combined with the friend's homepage, menu, drink customisation, cart, checkout, quiz, about page, registration, reviews and customer order history. The site's name is consistently KopiTarikBn.

## Install / update in XAMPP
1. Back up your existing KopiTarikBn folder and database.
2. Extract the ZIP into a fresh folder so the final project is C:/xampp/htdocs/KopiTarikBn. The ZIP already has one KopiTarikBn folder: avoid creating KopiTarikBn/KopiTarikBn. Using a fresh project folder prevents old setup/login files being left behind.
3. Keep your existing coffee_shop database if it already uses the master schema from the improved project. No schema migration is required for this merge. DO NOT reimport coffee_shop.sql into a database you want to preserve: it drops tables and replaces data.
4. For a new/test installation only, start MySQL in XAMPP and import coffee_shop.sql with phpMyAdmin. It contains the original categories, products and sample orders. It intentionally does not create an admin account.
5. To create an admin if needed, open PowerShell in the project and run:

   C:\xampp\php\php.exe tools/create_admin.php admin your-email@example.com

   Enter a password of 12–72 bytes at the prompt. Input is visible in the terminal. The tool does not overwrite an existing account and cannot be run through a browser.
6. Start Apache and MySQL. Open http://localhost/KopiTarikBn/ . Both customers and admins use login.php. Register a customer through register.php; admins land on admin/dashboard.php.
7. Sign out and sign in after replacing an older version. The cart structure changed to support separate drink customisations; old-format carts are cleared on first use.

## Database configuration
One shared PDO connection lives in include/db_connect.php. Defaults are localhost, coffee_shop, root, and an empty password for local XAMPP. Optional environment variables: KOPI_DB_HOST, KOPI_DB_NAME, KOPI_DB_USER, KOPI_DB_PASSWORD. The customer side no longer uses a second mysqli connection.

## Cart, pricing and checkout rules
- Each unique combination of product, size, milk, ice, shots, syrup, topping and note has its own cart line. Adding the identical configuration increases that line's quantity.
- Size: Small -BND 0.50, Medium unchanged, Large +BND 0.50. Oat/Almond/Soy milk +BND 0.80. Shots, pumps and toppings remain included in the price, matching the supplied customer's cart/price display. Hot drinks cannot select ice.
- Server-side prices are authoritative. The browser cannot set the charged price.
- BREW10 discounts each customised drink by 10%, rounded to the nearest cent per unit. Discounted unit prices/subtotals are saved, so order totals and admin item-sales reports agree exactly. The order confirmation shows the total savings. No new database columns are needed.
- Guest details go directly into order snapshots with customer_id=NULL. Guests are not silently registered; an email matching an existing customer can still be used for guest checkout.
- Logged-in checkout uses the customer's database details, rather than trusting submitted identity fields.
- Checkout validates aggregate stock across all customisations, locks product rows in a consistent order, saves order/item snapshots and customisations, and decrements stock in one transaction. Zero stock marks the product unavailable.
- Successful checkout redirects to a session-bound receipt page and clears the cart. Refreshing/reposting cannot repeat that completed cart.
- Orders are pickup requests; no online payment is collected.

## Security and structure
- Shared sessions: include/security.php. Customer keys: customer_id/customer_name; admin keys: admin_id/admin_username.
- Every modifying form uses POST and the same csrf_token. Logout also uses POST.
- Customer pages load config/app.php, which reuses the shared security/PDO files and include/shop.php. There is no config/db.php connection to maintain.
- Customer order history uses saved product names/customisations even after a product is renamed or deleted. It is restricted by the signed-in customer's ID.
- The database allows one review per customer/product. Submitting again updates that customer's review.
- Customer pages, including login, use assets/customer.css plus assets/design.css; admin styling remains assets/admin.css.
- Apache .htaccess rules protect SQL/internal folders and the upload directory; Apache AllowOverride must support those rules.

## Validation and limits
See MERGE_REPORT.md for the verified checks and remaining live XAMPP checks. All testing used workspace copies and an isolated test database. Original ZIPs, htdocs files and your live database were not modified.
