<?php

require __DIR__ . '/../include/admin_auth.php';
require __DIR__ . '/../include/db_connect.php';


// ==========================================
// REPORT FILTER
// ==========================================

$startDate = trim($_GET['start_date'] ?? '');
$endDate = trim($_GET['end_date'] ?? '');
$reportGenerated = isset($_GET['generate']);

$dateError = '';


// ==========================================
// DATE VALIDATION
// ==========================================

function validReportDate(string $date): bool
{
    $dateObject = DateTime::createFromFormat(
        'Y-m-d',
        $date
    );

    return (
        $dateObject !== false &&
        $dateObject->format('Y-m-d') === $date
    );
}


if (
    $startDate !== '' &&
    !validReportDate($startDate)
) {
    $dateError = 'Invalid start date.';
}


if (
    $endDate !== '' &&
    !validReportDate($endDate)
) {
    $dateError = 'Invalid end date.';
}


if (
    $dateError === '' &&
    $startDate !== '' &&
    $endDate !== '' &&
    $startDate > $endDate
) {
    $dateError =
        'Start date cannot be later than end date.';
}


if ($dateError !== '') {
    $startDate = '';
    $endDate = '';
}


// ==========================================
// BUILD DATE FILTER
//
// The selected period is based on order_date
// so every statistic uses the same report
// period.
// ==========================================

$dateConditions = [];
$dateParams = [];


if ($startDate !== '') {

    $dateConditions[] =
        'o.order_date >= :start_date';

    $dateParams[':start_date'] =
        $startDate . ' 00:00:00';
}


if ($endDate !== '') {

    $nextDay = date(
        'Y-m-d',
        strtotime($endDate . ' +1 day')
    );

    $dateConditions[] =
        'o.order_date < :end_date';

    $dateParams[':end_date'] =
        $nextDay . ' 00:00:00';
}


$dateSql = '';

if (!empty($dateConditions)) {

    $dateSql =
        ' AND ' .
        implode(
            ' AND ',
            $dateConditions
        );
}


// ==========================================
// TOTAL ORDERS
// ==========================================

$stmt = $pdo->prepare(
    "SELECT COUNT(*)
     FROM orders o
     WHERE 1 = 1
     $dateSql"
);

$stmt->execute($dateParams);

$totalOrders =
    (int) $stmt->fetchColumn();


// ==========================================
// COMPLETED ORDERS
//
// Operational count, separate from payment.
// ==========================================

$stmt = $pdo->prepare(
    "SELECT COUNT(*)
     FROM orders o
     WHERE o.status = 'Completed'
     $dateSql"
);

$stmt->execute($dateParams);

$completedOrders =
    (int) $stmt->fetchColumn();


// ==========================================
// TOTAL REVENUE
//
// Paid simulated orders only.
// Refunded orders are excluded because their
// payment_status is no longer Paid.
// ==========================================

$stmt = $pdo->prepare(
    "SELECT COALESCE(
        SUM(o.total_amount),
        0
     )
     FROM orders o
     WHERE o.payment_status = 'Paid'
       AND o.payment_method = 'Demo'
       $dateSql"
);

$stmt->execute($dateParams);

$totalRevenue =
    $stmt->fetchColumn();


// ==========================================
// TOTAL PRODUCTS SOLD
// ==========================================

$stmt = $pdo->prepare(
    "SELECT COALESCE(
        SUM(oi.quantity),
        0
     )
     FROM order_items oi

     JOIN orders o
        ON o.order_id = oi.order_id

     WHERE o.payment_status = 'Paid'
       AND o.payment_method = 'Demo'
       $dateSql"
);

$stmt->execute($dateParams);

$totalProductsSold =
    (int) $stmt->fetchColumn();


// ==========================================
// BEST SELLING PRODUCTS PAGINATION
// ==========================================

$productsPerPage = 5;

$bestPage = filter_input(
    INPUT_GET,
    'best_page',
    FILTER_VALIDATE_INT
);


if (!$bestPage || $bestPage < 1) {
    $bestPage = 1;
}


// ==========================================
// COUNT BEST SELLING PRODUCTS
// ==========================================

$stmt = $pdo->prepare(
    "SELECT COUNT(*)

     FROM (

         SELECT oi.menu_name

         FROM order_items oi

         JOIN orders o
            ON o.order_id = oi.order_id

         WHERE o.payment_status = 'Paid'
           AND o.payment_method = 'Demo'
           $dateSql

         GROUP BY oi.menu_name

     ) AS paid_products"
);

$stmt->execute($dateParams);

$totalBestProducts =
    (int) $stmt->fetchColumn();


$totalBestPages = max(
    1,
    (int) ceil(
        $totalBestProducts /
        $productsPerPage
    )
);


$bestPage = min(
    $bestPage,
    $totalBestPages
);


$bestOffset =
    ($bestPage - 1) *
    $productsPerPage;


// ==========================================
// GET BEST SELLING PRODUCTS
// ==========================================

$stmt = $pdo->prepare(
    "SELECT
         oi.menu_name,
         SUM(oi.quantity) AS quantity_sold,
         SUM(oi.subtotal) AS revenue

     FROM order_items oi

     JOIN orders o
        ON o.order_id = oi.order_id

     WHERE o.payment_status = 'Paid'
       AND o.payment_method = 'Demo'
       $dateSql

     GROUP BY oi.menu_name

     ORDER BY
        quantity_sold DESC,
        revenue DESC,
        oi.menu_name ASC

     LIMIT :limit
     OFFSET :offset"
);


foreach (
    $dateParams as
    $parameter => $value
) {
    $stmt->bindValue(
        $parameter,
        $value
    );
}


$stmt->bindValue(
    ':limit',
    $productsPerPage,
    PDO::PARAM_INT
);

$stmt->bindValue(
    ':offset',
    $bestOffset,
    PDO::PARAM_INT
);


$stmt->execute();

$productSales =
    $stmt->fetchAll();


// ==========================================
// PAGINATION RESULT RANGE
// ==========================================

$startBestProduct =
    $totalBestProducts > 0
        ? $bestOffset + 1
        : 0;


$endBestProduct =
    $totalBestProducts > 0

        ? min(
            $bestOffset +
            $productsPerPage,

            $totalBestProducts
        )

        : 0;


// ==========================================
// ALL PAYMENTS PAGINATION
//
// Show every payment record in the selected
// report period, 10 records per page.
// ==========================================

$paymentsPerPage = 10;

$paymentPage = filter_input(
    INPUT_GET,
    'payment_page',
    FILTER_VALIDATE_INT
);

if (!$paymentPage || $paymentPage < 1) {
    $paymentPage = 1;
}


// ==========================================
// COUNT ALL PAYMENTS
// ==========================================

$stmt = $pdo->prepare(
    "SELECT COUNT(*)
     FROM orders o
     WHERE 1 = 1
     $dateSql"
);

$stmt->execute($dateParams);

$totalPayments =
    (int) $stmt->fetchColumn();


$totalPaymentPages = max(
    1,
    (int) ceil(
        $totalPayments /
        $paymentsPerPage
    )
);


$paymentPage = min(
    $paymentPage,
    $totalPaymentPages
);


$paymentOffset =
    ($paymentPage - 1) *
    $paymentsPerPage;


// ==========================================
// GET ALL PAYMENTS
// ==========================================

$stmt = $pdo->prepare(
    "SELECT
         o.order_id,
         o.customer_name,
         COALESCE(o.paid_at, o.order_date) AS payment_date,
         o.payment_status,
         o.payment_method,
         o.total_amount

     FROM orders o

     WHERE 1 = 1
     $dateSql

     ORDER BY
         COALESCE(o.paid_at, o.order_date) DESC,
         o.order_id DESC

     LIMIT :payment_limit
     OFFSET :payment_offset"
);


foreach (
    $dateParams as
    $parameter => $value
) {
    $stmt->bindValue(
        $parameter,
        $value
    );
}


$stmt->bindValue(
    ':payment_limit',
    $paymentsPerPage,
    PDO::PARAM_INT
);

$stmt->bindValue(
    ':payment_offset',
    $paymentOffset,
    PDO::PARAM_INT
);

$stmt->execute();

$payments = $stmt->fetchAll();


// ==========================================
// PAYMENT PAGINATION RESULT RANGE
// ==========================================

$startPayment =
    $totalPayments > 0
        ? $paymentOffset + 1
        : 0;


$endPayment =
    $totalPayments > 0

        ? min(
            $paymentOffset +
            $paymentsPerPage,
            $totalPayments
        )

        : 0;


// ==========================================
// REPORT PERIOD LABEL
// ==========================================

if (
    $startDate !== '' &&
    $endDate !== ''
) {

    $reportPeriod =
        date(
            'd M Y',
            strtotime($startDate)
        )
        . ' to ' .
        date(
            'd M Y',
            strtotime($endDate)
        );

} elseif ($startDate !== '') {

    $reportPeriod =
        'From ' .
        date(
            'd M Y',
            strtotime($startDate)
        )
        . ' onwards';

} elseif ($endDate !== '') {

    $reportPeriod =
        'Up to ' .
        date(
            'd M Y',
            strtotime($endDate)
        );

} else {

    $reportPeriod =
        'All stored data';
}


// ==========================================
// CSV / EXCEL EXPORT
//
// CSV opens directly in Microsoft Excel and
// does not require an external PHP library.
// The export uses the same selected date
// range as the on-screen generated report.
// ==========================================

function csvSafeValue($value): string
{
    $value = (string) $value;

    // Prevent spreadsheet formula injection when a value
    // begins with a character Excel may interpret as a formula.
    if (
        $value !== '' &&
        preg_match('/^[=+\-@]/', $value)
    ) {
        $value = "'" . $value;
    }

    return $value;
}


if (
    isset($_GET['export']) &&
    $_GET['export'] === 'csv' &&
    $dateError === ''
) {

    // --------------------------------------
    // ALL BEST-SELLING PRODUCTS FOR EXPORT
    // --------------------------------------

    $stmt = $pdo->prepare(
        "SELECT
             oi.menu_name,
             SUM(oi.quantity) AS quantity_sold,
             SUM(oi.subtotal) AS revenue

         FROM order_items oi

         JOIN orders o
            ON o.order_id = oi.order_id

         WHERE o.payment_status = 'Paid'
           AND o.payment_method = 'Demo'
           $dateSql

         GROUP BY oi.menu_name

         ORDER BY
            quantity_sold DESC,
            revenue DESC,
            oi.menu_name ASC"
    );

    $stmt->execute($dateParams);
    $exportProducts = $stmt->fetchAll();


    // --------------------------------------
    // ALL ORDERS IN THE SELECTED PERIOD
    // --------------------------------------

    $stmt = $pdo->prepare(
        "SELECT
             o.order_id,
             o.customer_name,
             o.customer_email,
             o.order_date,
             o.paid_at,
             o.status,
             o.payment_status,
             o.payment_method,
             o.total_amount

         FROM orders o

         WHERE 1 = 1
         $dateSql

         ORDER BY
            o.order_date DESC,
            o.order_id DESC"
    );

    $stmt->execute($dateParams);
    $exportOrders = $stmt->fetchAll();


    // --------------------------------------
    // FILE NAME
    // --------------------------------------

    if (
        $startDate !== '' &&
        $endDate !== ''
    ) {
        $filePeriod =
            $startDate . '_to_' . $endDate;

    } elseif ($startDate !== '') {
        $filePeriod =
            'from_' . $startDate;

    } elseif ($endDate !== '') {
        $filePeriod =
            'up_to_' . $endDate;

    } else {
        $filePeriod = 'all_data';
    }

    $fileName =
        'KopiTarikBn_Sales_Report_' .
        $filePeriod .
        '.csv';


    // --------------------------------------
    // DOWNLOAD HEADERS
    // --------------------------------------

    header('Content-Type: text/csv; charset=UTF-8');
    header(
        'Content-Disposition: attachment; filename="' .
        $fileName .
        '"'
    );
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen('php://output', 'w');

    if ($output === false) {
        http_response_code(500);
        exit('Unable to generate report.');
    }

    // UTF-8 BOM helps Microsoft Excel display text correctly.
    fwrite($output, "\xEF\xBB\xBF");


    // --------------------------------------
    // REPORT HEADER
    // --------------------------------------

    fputcsv($output, ['KopiTarikBn Sales Report']);
    fputcsv($output, ['Report Period', $reportPeriod]);
    fputcsv($output, []);


    // --------------------------------------
    // SUMMARY
    // --------------------------------------

    fputcsv($output, ['REPORT SUMMARY']);
    fputcsv($output, ['Metric', 'Value']);
    fputcsv($output, ['Total Orders', $totalOrders]);
    fputcsv($output, ['Completed Orders', $completedOrders]);
    fputcsv($output, ['Products Sold', $totalProductsSold]);
    fputcsv(
        $output,
        [
            'Total Revenue',
            number_format((float) $totalRevenue, 2, '.', '')
        ]
    );
    fputcsv($output, []);


    // --------------------------------------
    // BEST-SELLING PRODUCTS
    // --------------------------------------

    fputcsv($output, ['BEST-SELLING PRODUCTS']);
    fputcsv(
        $output,
        [
            'Rank',
            'Product',
            'Quantity Sold',
            'Revenue'
        ]
    );

    $rank = 1;

    foreach ($exportProducts as $product) {
        fputcsv(
            $output,
            [
                $rank,
                csvSafeValue($product['menu_name']),
                (int) $product['quantity_sold'],
                number_format(
                    (float) $product['revenue'],
                    2,
                    '.',
                    ''
                )
            ]
        );

        $rank++;
    }

    if (empty($exportProducts)) {
        fputcsv($output, ['No paid sales found.']);
    }

    fputcsv($output, []);


    // --------------------------------------
    // ORDER DETAILS
    // --------------------------------------

    fputcsv($output, ['ORDER DETAILS']);
    fputcsv(
        $output,
        [
            'Order ID',
            'Customer',
            'Email',
            'Order Date',
            'Paid At',
            'Order Status',
            'Payment Status',
            'Payment Method',
            'Total'
        ]
    );

    foreach ($exportOrders as $order) {
        fputcsv(
            $output,
            [
                'ORD-' . str_pad(
                    (string) $order['order_id'],
                    5,
                    '0',
                    STR_PAD_LEFT
                ),
                csvSafeValue($order['customer_name']),
                csvSafeValue($order['customer_email']),
                $order['order_date'],
                $order['paid_at'] ?? '',
                csvSafeValue($order['status']),
                csvSafeValue($order['payment_status']),
                csvSafeValue($order['payment_method']),
                number_format(
                    (float) $order['total_amount'],
                    2,
                    '.',
                    ''
                )
            ]
        );
    }

    if (empty($exportOrders)) {
        fputcsv($output, ['No orders found for this period.']);
    }

    fclose($output);
    exit;
}


// ==========================================
// PAGINATION URL HELPER
//
// Preserve the generated report date range
// while browsing best-selling-product pages.
// ==========================================

function reportPageUrl(
    int $page,
    string $startDate,
    string $endDate,
    bool $reportGenerated,
    int $paymentPage = 1
): string {

    $query = [
        'best_page' => $page
    ];


    if ($startDate !== '') {
        $query['start_date'] =
            $startDate;
    }


    if ($endDate !== '') {
        $query['end_date'] =
            $endDate;
    }


    if ($reportGenerated) {
        $query['generate'] = '1';
    }


    if ($paymentPage > 1) {
        $query['payment_page'] = $paymentPage;
    }


    return
        'report.php?' .
        http_build_query($query);
}


// ==========================================
// PAYMENT PAGINATION URL HELPER
//
// Keeps the selected date range and the
// current best-selling-products page while
// browsing the payment records.
// ==========================================

function paymentPageUrl(
    int $page,
    string $startDate,
    string $endDate,
    bool $reportGenerated,
    int $bestPage
): string {

    $query = [
        'payment_page' => $page
    ];


    if ($bestPage > 1) {
        $query['best_page'] = $bestPage;
    }


    if ($startDate !== '') {
        $query['start_date'] =
            $startDate;
    }


    if ($endDate !== '') {
        $query['end_date'] =
            $endDate;
    }


    if ($reportGenerated) {
        $query['generate'] = '1';
    }


    return
        'report.php?' .
        http_build_query($query);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Sales Report | KopiTarikBn
    </title>

    <link
        rel="stylesheet"
        href="../assets/admin.css"
    >


    <style>

        /* =====================================
           REPORT GENERATOR
        ====================================== */

        .report-generator {
            margin-bottom: 24px;
        }

        .report-generator-grid {
            display: flex;
            align-items: flex-end;
            gap: 16px;
            flex-wrap: wrap;
        }

        .report-generator .filter-control {
            flex: 1 1 220px;
        }

        .report-generator .filter-control label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .report-generator input[type="date"] {
            width: 100%;
            min-height: 44px;
            padding: 0 13px;
            border: 1px solid #ddd1c5;
            border-radius: 10px;
            background: #fffdfa;
            color: #514238;
            font: inherit;
            box-sizing: border-box;
        }

        .report-generator input[type="date"]:focus {
            outline: none;
            border-color: #9b6743;
            box-shadow: 0 0 0 3px rgba(155, 103, 67, 0.12);
        }

        .report-generator-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .report-period-message {
            margin-bottom: 24px;
        }

        @media (max-width: 700px) {

            .report-generator-grid {
                display: block;
            }

            .report-generator .filter-control {
                margin-bottom: 14px;
            }

            .report-generator-actions {
                width: 100%;
            }

            .report-generator-actions .btn-primary,
            .report-generator-actions .btn-secondary {
                flex: 1;
                text-align: center;
            }
        }

    </style>

</head>


<body class="admin-body">


<div class="admin-layout dashboard-layout admin-theme">


    <?php include __DIR__ . '/include/sidebar.php'; ?>


    <main class="admin-main">


        <!-- ==================================
             PAGE HEADER
        =================================== -->

        <div class="admin-page-header">

            <div>

                <span class="products-eyebrow">CAFÉ PERFORMANCE</span>

                <h1>
                    Sales Report
                </h1>

                <p>
                    Overview of order fulfilment
                    and product sales.
                </p>

            </div>

        </div>



        <!-- ==================================
             GENERATE REPORT
        =================================== -->

        <section
            class="
                admin-section
                report-section
                report-generator
            "
        >

            <div class="section-header">

                <div>

                    <h2>
                        Generate Sales Report
                    </h2>

                    <p class="section-description">
                        Select a date range to generate
                        a report from stored order data.
                    </p>

                </div>

            </div>


            <form
                method="GET"
                action="report.php"
                class="report-generator-grid"
            >


                <!-- FROM DATE -->

                <div class="filter-control">

                    <label for="start_date">
                        From Date
                    </label>

                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="<?= htmlspecialchars(
                            $startDate,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                    >

                </div>


                <!-- TO DATE -->

                <div class="filter-control">

                    <label for="end_date">
                        To Date
                    </label>

                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="<?= htmlspecialchars(
                            $endDate,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                    >

                </div>


                <!-- ACTIONS -->

                <div class="report-generator-actions">

                    <button
                        type="submit"
                        name="generate"
                        value="1"
                        class="btn-primary"
                    >
                        Generate Report
                    </button>


                    <a
                        href="report.php"
                        class="btn-secondary"
                    >
                        Reset
                    </a>


                    <button
                        type="submit"
                        name="export"
                        value="csv"
                        class="btn-secondary"
                    >
                        Export to Excel (CSV)
                    </button>

                </div>


            </form>

        </section>


        <!-- DATE VALIDATION ERROR -->

        <?php if ($dateError !== ''): ?>

            <div class="alert-error report-period-message">

                <?= htmlspecialchars(
                    $dateError,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>

            </div>

        <?php endif; ?>


        <!-- GENERATED REPORT MESSAGE -->

        <?php if (
            $reportGenerated &&
            $dateError === ''
        ): ?>

            <div class="alert-success report-period-message">

                <strong>
                    Report generated successfully.
                </strong>

                Period:

                <?= htmlspecialchars(
                    $reportPeriod,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>.

            </div>

        <?php endif; ?>


        <!-- ==================================
             REPORT SUMMARY
        =================================== -->

        <section class="dashboard-cards">


            <!-- TOTAL ORDERS -->

            <div class="dashboard-card dashboard-card-orders">

                <div class="dashboard-card-top">
                    <span class="card-label">Total Orders</span>
                    <span class="dashboard-card-icon" aria-hidden="true">≡</span>
                </div>

                <h2>
                    <?= $totalOrders ?>
                </h2>


                <span class="report-note">
                    All order statuses
                </span>


            </div>



            <!-- COMPLETED ORDERS -->

            <div class="dashboard-card dashboard-card-categories">

                <div class="dashboard-card-top">
                    <span class="card-label">Completed Orders</span>
                    <span class="dashboard-card-icon" aria-hidden="true">✓</span>
                </div>

                <h2>
                    <?= $completedOrders ?>
                </h2>


                <span class="report-note">
                    Successful sales
                </span>


            </div>



            <!-- TOTAL PRODUCTS SOLD -->

            <div class="dashboard-card dashboard-card-products">

                <div class="dashboard-card-top">
                    <span class="card-label">Products Sold</span>
                    <span class="dashboard-card-icon" aria-hidden="true">+</span>
                </div>

                <h2>
                    <?= $totalProductsSold ?>
                </h2>


                <span class="report-note">
                    Paid simulated orders; refunds excluded
                </span>


            </div>



            <!-- TOTAL REVENUE -->

            <div class="dashboard-card dashboard-card-revenue">

                <div class="dashboard-card-top">
                    <span class="card-label">Total Revenue</span>
                    <span class="dashboard-card-icon" aria-hidden="true">$</span>
                </div>

                <h2>

                    $<?= number_format(
                        $totalRevenue,
                        2
                    ) ?>

                </h2>


                <span class="report-note">
                    Paid simulated orders; refunds excluded
                </span>


            </div>


        </section>



        <!-- ==================================
             BEST SELLING PRODUCTS
        =================================== -->

        <section
            class="
                admin-section
                report-section
            "
        >


            <div class="section-header">


                <div>

                    <h2>
                        Best-Selling Products
                    </h2>


                    <p class="section-description">

                        Product performance
                        from paid simulated orders, excluding refunds.

                    </p>

                </div>


            </div>



            <div class="table-container">


                <table class="admin-table">


                    <thead>


                    <tr>

                        <th>
                            Rank
                        </th>

                        <th>
                            Product
                        </th>

                        <th>
                            Quantity Sold
                        </th>

                        <th>
                            Revenue
                        </th>

                    </tr>


                    </thead>



                    <tbody>


                    <?php if (
                        !empty($productSales)
                    ): ?>


                        <?php

                        // Continue rank across pages
                        $rank =
                            $bestOffset + 1;

                        ?>


                        <?php foreach (
                            $productSales
                            as $product
                        ): ?>


                            <tr>


                                <!-- RANK -->

                                <td>

                                    <?= $rank ?>

                                </td>



                                <!-- PRODUCT -->

                                <td>

                                    <strong>

                                        <?= htmlspecialchars(
                                            $product['menu_name'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </strong>

                                </td>



                                <!-- QUANTITY -->

                                <td>

                                    <?= (int)$product[
                                        'quantity_sold'
                                    ] ?>

                                </td>



                                <!-- REVENUE -->

                                <td>

                                    $<?= number_format(
                                        $product['revenue'],
                                        2
                                    ) ?>

                                </td>


                            </tr>


                            <?php $rank++; ?>


                        <?php endforeach; ?>


                    <?php else: ?>


                        <tr>

                            <td
                                colspan="4"
                                class="empty-table"
                            >

                                No paid sales found.

                            </td>

                        </tr>


                    <?php endif; ?>


                    </tbody>


                </table>


            </div>



            <!-- ==================================
                 BEST SELLER PAGINATION
            =================================== -->

            <?php if (
                $totalBestProducts > 0
            ): ?>


                <div class="products-pagination">


                    <!-- RESULT INFO -->

                    <div class="pagination-info">

                        Showing

                        <strong>
                            <?= $startBestProduct ?>
                        </strong>

                        –

                        <strong>
                            <?= $endBestProduct ?>
                        </strong>

                        of

                        <strong>
                            <?= $totalBestProducts ?>
                        </strong>

                        products

                    </div>



                    <?php if (
                        $totalBestPages > 1
                    ): ?>


                        <nav
                            class="pagination-links"
                            aria-label="
                                Best-selling
                                product pages
                            "
                        >


                            <!-- PREVIOUS -->

                            <?php if (
                                $bestPage > 1
                            ): ?>


                                <a
                                    href="<?= htmlspecialchars(
                                        reportPageUrl(
                                            $bestPage - 1,
                                            $startDate,
                                            $endDate,
                                            $reportGenerated,
                                            $paymentPage
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                    class="pagination-button"
                                >
                                    ← Previous
                                </a>


                            <?php endif; ?>



                            <?php

                            $startPage = max(
                                1,
                                $bestPage - 2
                            );

                            $endPage = min(
                                $totalBestPages,
                                $bestPage + 2
                            );

                            ?>



                            <!-- FIRST PAGE -->

                            <?php if (
                                $startPage > 1
                            ): ?>


                                <a
                                    href="<?= htmlspecialchars(
                                        reportPageUrl(
                                            1,
                                            $startDate,
                                            $endDate,
                                            $reportGenerated,
                                            $paymentPage
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                    class="pagination-button"
                                >
                                    1
                                </a>


                                <?php if (
                                    $startPage > 2
                                ): ?>

                                    <span
                                        class="pagination-dots"
                                    >
                                        ...
                                    </span>

                                <?php endif; ?>


                            <?php endif; ?>



                            <!-- PAGE NUMBERS -->

                            <?php for (
                                $i = $startPage;
                                $i <= $endPage;
                                $i++
                            ): ?>


                                <?php if (
                                    $i === $bestPage
                                ): ?>


                                    <span
                                        class="
                                            pagination-button
                                            active
                                        "
                                        aria-current="page"
                                    >
                                        <?= $i ?>
                                    </span>


                                <?php else: ?>


                                    <a
                                        href="<?= htmlspecialchars(
                                            reportPageUrl(
                                                $i,
                                                $startDate,
                                                $endDate,
                                                $reportGenerated,
                                                $paymentPage
                                            ),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>"
                                        class="pagination-button"
                                    >
                                        <?= $i ?>
                                    </a>


                                <?php endif; ?>


                            <?php endfor; ?>



                            <!-- LAST PAGE -->

                            <?php if (
                                $endPage
                                < $totalBestPages
                            ): ?>


                                <?php if (
                                    $endPage
                                    < $totalBestPages - 1
                                ): ?>

                                    <span
                                        class="pagination-dots"
                                    >
                                        ...
                                    </span>

                                <?php endif; ?>


                                <a
                                    href="<?= htmlspecialchars(
                                        reportPageUrl(
                                            $totalBestPages,
                                            $startDate,
                                            $endDate,
                                            $reportGenerated,
                                            $paymentPage
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                    class="pagination-button"
                                >
                                    <?= $totalBestPages ?>
                                </a>


                            <?php endif; ?>



                            <!-- NEXT -->

                            <?php if (
                                $bestPage
                                < $totalBestPages
                            ): ?>


                                <a
                                    href="<?= htmlspecialchars(
                                        reportPageUrl(
                                            $bestPage + 1,
                                            $startDate,
                                            $endDate,
                                            $reportGenerated,
                                            $paymentPage
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                    class="pagination-button"
                                >
                                    Next →
                                </a>


                            <?php endif; ?>


                        </nav>


                    <?php endif; ?>


                </div>


            <?php endif; ?>


        </section>



        <!-- ==================================
             ALL PAYMENTS
        =================================== -->

        <section
            class="
                admin-section
                report-section
            "
        >


            <div class="section-header">


                <div>

                    <h2>
                        All Payments
                    </h2>


                    <p class="section-description">

                        All customer payment records
                        for the selected report period.

                    </p>

                </div>


            </div>



            <div class="table-container">


                <table class="admin-table">


                    <thead>


                    <tr>

                        <th>
                            Order ID
                        </th>

                        <th>
                            Customer
                        </th>

                        <th>
                            Payment Date
                        </th>

                        <th>
                            Payment Status
                        </th>

                        <th>
                            Method
                        </th>

                        <th>
                            Total
                        </th>

                        <th>
                            Action
                        </th>

                    </tr>


                    </thead>



                    <tbody>


                    <?php if (
                        !empty($payments)
                    ): ?>


                        <?php foreach (
                            $payments
                            as $payment
                        ): ?>


                            <tr>


                                <!-- ORDER ID -->

                                <td>

                                    <strong>

                                        ORD-<?= str_pad(
                                            $payment['order_id'],
                                            5,
                                            '0',
                                            STR_PAD_LEFT
                                        ) ?>

                                    </strong>

                                </td>



                                <!-- CUSTOMER -->

                                <td>

                                    <?= htmlspecialchars(
                                        $payment['customer_name'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </td>



                                <!-- PAYMENT DATE -->

                                <td>

                                    <?= e(
                                        local_datetime(
                                            $payment['payment_date'],
                                            'd M Y, h:i A'
                                        )
                                    ) ?>

                                </td>



                                <!-- PAYMENT STATUS -->

                                <td>

                                    <strong>
                                        <?= htmlspecialchars(
                                            $payment['payment_status'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </strong>

                                </td>



                                <!-- PAYMENT METHOD -->

                                <td>

                                    <?= htmlspecialchars(
                                        $payment['payment_method'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </td>



                                <!-- TOTAL -->

                                <td>

                                    $<?= number_format(
                                        $payment[
                                            'total_amount'
                                        ],
                                        2
                                    ) ?>

                                </td>



                                <!-- VIEW -->

                                <td>

                                    <a
                                        href="view_order.php?id=<?= (int)$payment['order_id'] ?>"
                                        class="btn-view"
                                    >
                                        View
                                    </a>

                                </td>


                            </tr>


                        <?php endforeach; ?>


                    <?php else: ?>


                        <tr>

                            <td
                                colspan="7"
                                class="empty-table"
                            >

                                No payment records found.

                            </td>

                        </tr>


                    <?php endif; ?>


                    </tbody>


                </table>


            </div>


            <!-- ==================================
                 PAYMENT PAGINATION
            =================================== -->

            <?php if (
                $totalPayments > 0
            ): ?>


                <div class="products-pagination">


                    <!-- RESULT INFO -->

                    <div class="pagination-info">

                        Showing

                        <strong>
                            <?= $startPayment ?>
                        </strong>

                        –

                        <strong>
                            <?= $endPayment ?>
                        </strong>

                        of

                        <strong>
                            <?= $totalPayments ?>
                        </strong>

                        payments

                    </div>



                    <?php if (
                        $totalPaymentPages > 1
                    ): ?>


                        <nav
                            class="pagination-links"
                            aria-label="Payment pages"
                        >


                            <!-- PREVIOUS -->

                            <?php if (
                                $paymentPage > 1
                            ): ?>


                                <a
                                    href="<?= htmlspecialchars(
                                        paymentPageUrl(
                                            $paymentPage - 1,
                                            $startDate,
                                            $endDate,
                                            $reportGenerated,
                                            $bestPage
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                    class="pagination-button"
                                >
                                    ← Previous
                                </a>


                            <?php endif; ?>



                            <?php

                            $paymentStartPage = max(
                                1,
                                $paymentPage - 2
                            );

                            $paymentEndPage = min(
                                $totalPaymentPages,
                                $paymentPage + 2
                            );

                            ?>



                            <!-- FIRST PAGE -->

                            <?php if (
                                $paymentStartPage > 1
                            ): ?>


                                <a
                                    href="<?= htmlspecialchars(
                                        paymentPageUrl(
                                            1,
                                            $startDate,
                                            $endDate,
                                            $reportGenerated,
                                            $bestPage
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                    class="pagination-button"
                                >
                                    1
                                </a>


                                <?php if (
                                    $paymentStartPage > 2
                                ): ?>

                                    <span
                                        class="pagination-dots"
                                    >
                                        ...
                                    </span>

                                <?php endif; ?>


                            <?php endif; ?>



                            <!-- PAGE NUMBERS -->

                            <?php for (
                                $i = $paymentStartPage;
                                $i <= $paymentEndPage;
                                $i++
                            ): ?>


                                <?php if (
                                    $i === $paymentPage
                                ): ?>


                                    <span
                                        class="
                                            pagination-button
                                            active
                                        "
                                        aria-current="page"
                                    >
                                        <?= $i ?>
                                    </span>


                                <?php else: ?>


                                    <a
                                        href="<?= htmlspecialchars(
                                            paymentPageUrl(
                                                $i,
                                                $startDate,
                                                $endDate,
                                                $reportGenerated,
                                                $bestPage
                                            ),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>"
                                        class="pagination-button"
                                    >
                                        <?= $i ?>
                                    </a>


                                <?php endif; ?>


                            <?php endfor; ?>



                            <!-- LAST PAGE -->

                            <?php if (
                                $paymentEndPage
                                < $totalPaymentPages
                            ): ?>


                                <?php if (
                                    $paymentEndPage
                                    < $totalPaymentPages - 1
                                ): ?>

                                    <span
                                        class="pagination-dots"
                                    >
                                        ...
                                    </span>

                                <?php endif; ?>


                                <a
                                    href="<?= htmlspecialchars(
                                        paymentPageUrl(
                                            $totalPaymentPages,
                                            $startDate,
                                            $endDate,
                                            $reportGenerated,
                                            $bestPage
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                    class="pagination-button"
                                >
                                    <?= $totalPaymentPages ?>
                                </a>


                            <?php endif; ?>



                            <!-- NEXT -->

                            <?php if (
                                $paymentPage
                                < $totalPaymentPages
                            ): ?>


                                <a
                                    href="<?= htmlspecialchars(
                                        paymentPageUrl(
                                            $paymentPage + 1,
                                            $startDate,
                                            $endDate,
                                            $reportGenerated,
                                            $bestPage
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                    class="pagination-button"
                                >
                                    Next →
                                </a>


                            <?php endif; ?>


                        </nav>


                    <?php endif; ?>


                </div>


            <?php endif; ?>


        </section>


    </main>


</div>


</body>

</html>