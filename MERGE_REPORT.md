# KopiTarikBn merge report

## Result
Merged the friend's customer pages and assets into the improved KopiTarikBn baseline. The existing admin implementation and shared login remain in place. Both original archives and the live project/database remain unchanged. Git history, test fixtures, and the friend's unrelated WebDev sample directory are excluded from the deliverable.

## Changes made

| Area | Change |
|---|---|
| Customer pages | Integrated homepage, about, menu, customisable drink details, cart, checkout, quiz, registration, reviews and order history. |
| Shared login | Kept the existing admin/customer login and role redirects. Added access to registration, menu, cart, customer orders, admin dashboard and protected logout as appropriate. |
| Database connection | Converted customer mysqli queries to the established PDO connection. Removed duplicate session/CSRF implementations. No schema changes or database reset required. |
| Guest checkout | Stores guest information in orders with a NULL customer ID. Removed the hidden guest-account creation that conflicted with unique customer emails. |
| Customisations | Preserves size, milk, ice, shots, pumps, topping and barista note all the way from drink page to cart, database and admin order details. |
| Cart variations | Keeps differently customised versions of one drink separate. Update/remove affects only the selected variation; stock is validated across all variations together. |
| Prices and discounts | Uses current database prices plus size/milk adjustments and calculates totals in integer cents. BREW10 is applied per unit with cent rounding, and saved item totals match the order total. Browser-submitted prices are ignored. |
| Stock and transactions | Locks products in consistent ID order, validates availability/aggregate quantities, saves all order records and deducts inventory in a transaction. Failed checkouts roll back. Zero stock becomes unavailable. |
| Repeat checkout | Uses a redirect after success and clears the cart; refreshing or replaying that completed checkout cannot create another order. |
| Order history | Uses saved item snapshots instead of joining live products, so product deletion or renaming does not erase purchase details. Customer queries filter by the current account. |
| Registration | Validates database field limits and password bounds, hashes passwords, renews sessions, clears the other role, and handles duplicate registrations without a raw database error. |
| Reviews | Respects the existing one-review-per-customer/product rule: repeat submissions update the review. Validates whole-number ratings and escapes displayed content. |
| Branding/design | Uses KopiTarikBn consistently. Preserves the friend's customer design and the original admin/login styles in separate stylesheets. Moves account actions into mobile navigation and adds a menu symbol that does not rely on the icon CDN. |
| Product images | Uses the correct assets/images/products path with a fallback to customer assets. Existing admin upload handling is preserved. |
| Defensive checks | Shared CSRF validation, typed POST handling, safe IDs, escaped notes/reviews, safe inline quiz data, and internal-folder access rules. |
| Compatibility | Old product.php links open the new drink detail page. Existing coffee_shop.sql is included unchanged. |

## Verification completed

- **36 PHP files:** syntax checked using XAMPP PHP.
- **63 integration checks:** PHP CLI request simulations against an isolated SQLite database converted from the supplied master SQL, with foreign keys enabled.
- Pages checked: all public customer pages, authenticated orders/reviews, shared login, and eight admin pages.
- Checked valid admin/customer login, session renewal, registration, duplicate registration, malformed inputs, absent CSRF tokens, cart add/update/remove, separate customisations, stock shortages, trusted prices, promo totals, guest checkout with an existing customer's email, account identity snapshots, receipt refresh/replay, and logout.
- Confirmed order/item writes and stock are rolled back when an injected database failure occurs during checkout.
- Confirmed customisation text is escaped, reviews update rather than violate the unique constraint, and customer history survives product deletion.
- Checked local links/assets in rendered page outputs and syntax of both external JavaScript files and rendered inline scripts.

## Testing limits

The SQLite fixture checks application behavior; it does not prove live MySQL row-lock/concurrency behavior or a phpMyAdmin import. MySQL access was unavailable in this environment. An automated headless-browser launch also failed in the sandbox, so interactive browser behavior and visual layout are not certified. Multipart image uploads and Apache access rules still need a live XAMPP check. External fonts/icons require internet access, with ordinary font fallbacks and an independent mobile-menu symbol provided.

## Use the merged version

Follow README.md. Back up the existing folder/database, replace the code with this fresh merged folder, keep the existing compatible database, and sign out/in. **Do not reimport the SQL unless deliberately creating/resetting a test database.**

Recommended final XAMPP walkthrough: register a customer; log in with each role; customise two versions of one drink; update/remove a cart line; check out as guest and customer; confirm stock, customisations and order totals in admin; change an order status; submit/update a review; try the quiz and mobile navigation; upload a product image.
