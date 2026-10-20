<?php
require __DIR__ . '/config/app.php';
require_user();$title='Reviews';$err='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    verify_csrf();$mid=filter_var($_POST['menu_id'] ?? null,FILTER_VALIDATE_INT) ?: 0;$rating=filter_var($_POST['rating'] ?? null,FILTER_VALIDATE_INT) ?: 0;$comment=trim($_POST['comment'] ?? '');
    $st=$pdo->prepare('SELECT menu_id FROM menu_item WHERE menu_id=?');$st->execute([$mid]);
    if (!$st->fetchColumn() || $rating<1 || $rating>5 || mb_strlen($comment)>1000) $err='Choose a drink, a rating from 1 to 5, and a comment under 1000 characters.';
    else try {
        $cid=$_SESSION['customer_id'];
        $st=$pdo->prepare('SELECT review_id FROM reviews WHERE customer_id=? AND menu_id=?');$st->execute([$cid,$mid]);$existing=$st->fetchColumn();
        if ($existing) { $st=$pdo->prepare('UPDATE reviews SET rating=?,comment=?,review_date=CURRENT_TIMESTAMP WHERE review_id=? AND customer_id=?');$st->execute([$rating,$comment,$existing,$cid]); }
        else { $st=$pdo->prepare('INSERT INTO reviews (customer_id,menu_id,rating,comment) VALUES (?,?,?,?)');$st->execute([$cid,$mid,$rating,$comment]); }
        flash('Thank you! Your review has been saved.');go('reviews.php');
    } catch (PDOException $e) { $err='Unable to save the review. Please refresh and try again.'; }
}
$items=$pdo->query('SELECT menu_id,menu_name FROM menu_item ORDER BY menu_name');
$reviews=$pdo->query('SELECT r.rating,r.comment,r.review_date,c.name,m.menu_name FROM reviews r JOIN customer c ON c.customer_id=r.customer_id JOIN menu_item m ON m.menu_id=r.menu_id ORDER BY r.review_date DESC LIMIT 30');
include __DIR__ . '/includes/header.php';

?>


<!-- 8. PAGE HEADER -->

<div class="pagehead">

    <div class="wrap">

        <h1>
            Customer Reviews
        </h1>

    </div>

</div>


<!-- 9. CUSTOMER REVIEWS SECTION -->

<section class="section wrap">


    <!-- REVIEW SUBMISSION FORM -->

    <form method="post" class="form-card">

        <h2>
            Share your experience
        </h2><p>Your latest review replaces your earlier review for the same drink.</p>


        <!-- Display Error Message -->

        <?php if ($err): ?>

            <p class="error">
                <?= h($err) ?>
            </p>

        <?php endif; ?>


        <!-- CSRF Token -->

        <input
            type="hidden"
            name="csrf_token"
            value="<?= csrf() ?>"
        >


        <!-- SELECT DRINK -->

        <label>
            Drink
        </label>

        <select name="menu_id" required>

            <?php foreach ($items as $m): ?>

                <option value="<?= $m['menu_id'] ?>">
                    <?= h($m['menu_name']) ?>
                </option>

            <?php endforeach; ?>

        </select>


        <!-- SELECT RATING -->

        <label>
            Rating
        </label>

        <select name="rating">

            <?php for ($i = 5; $i >= 1; $i--): ?>

                <option value="<?= $i ?>">
                    <?= $i ?> / 5
                </option>

            <?php endfor; ?>

        </select>


        <!-- REVIEW COMMENT -->

        <label>
            Comment
        </label>

        <textarea
            name="comment"
            maxlength="1000"
            rows="4"
        ></textarea>


        <!-- SUBMIT REVIEW BUTTON -->

        <p>

            <button class="btn dark">
                Submit review
            </button>

        </p>

    </form>


    <!-- 10. RECENT CUSTOMER REVIEWS -->

    <h2>
        Recent reviews
    </h2>

    <div class="grid">

        <?php foreach ($reviews as $r): ?>

            <div class="panel">


                <!-- Reviewed Drink Name -->

                <strong>
                    <?= h($r['menu_name']) ?>
                </strong>


                <!-- Customer Star Rating -->

                <p>
                    <?= str_repeat('★', (int) $r['rating']) ?>
                </p>


                <!-- Customer Review Comment -->

                <p>
                    <?= h($r['comment']) ?>
                </p>


                <!-- Customer Name and Review Date -->

                <p class="small muted">

                    By <?= h($r['name']) ?>

                    · <?= h($r['review_date']) ?>

                </p>

            </div>

        <?php endforeach; ?>

    </div>

</section>


<!-- 11. FOOTER -->

<?php include __DIR__ . '/includes/footer.php'; ?>
