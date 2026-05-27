<?php $title = "Login"; ?>
<?php require __DIR__ . "/partials/header.php"; ?>


<div class="login-container">

    <h2>Login</h2>

    <?php if (isset($_GET["logged_out"]) && empty($error)): ?>
        <p>
            You have been logged out.
        </p>
    <?php endif; ?>

    <?php if (isset($_GET["reason"])): ?>
        <p>
            <?php if ($_GET["reason"] === "timeout"): ?>
                Session expired due to inactivity.
            <?php elseif ($_GET["reason"] === "expired"): ?>
                Session expired.
            <?php endif; ?>
        </p>
    <?php endif; ?>

    <?php if(!empty($error)): ?>
        <p><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token())  ?>">

        <label>Cardnumber:</label><br>
        <input type="text" name="card_number" required><br>

        <label>PIN:</label><br>
        <input type="password" name="pin" required><br>

        <button type="submit">Sign In</button>

    </form>

</div>



<?php require __DIR__ . "/partials/footer.php"; ?>