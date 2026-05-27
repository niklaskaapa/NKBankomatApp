<?php
/** @var array $accounts */
?>

<?php $title = "Deposit"; ?>
<?php require __DIR__ . "/partials/header.php"; ?>

    <div class="form-container">
        <h2>Deposit</h2>

        <?php if (!empty($error)): ?>
            <p>
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <form method="POST">

            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">

            <label>Choose account:</label>

            <select name="account_id">
                <option value="">-- choose account --</option>

        <?php foreach ($accounts as $acc): ?>

                    <option value="<?= $acc["id"] ?>">

                        <?= htmlspecialchars($acc["account_type"]) ?>
                        -
                        <?= htmlspecialchars($acc["balance"]) ?> kr

                    </option>

                <?php endforeach; ?>

            </select>


            <label>Amount:</label><br>

            <input type="number"
                step="0.01"
                name="amount"
                required>


            <button type="submit">
                Deposit
            </button>

        </form>


        <a class="btn-back" href="?route=dashboard">Go back</a>

    </div>


<?php require __DIR__ . "/partials/footer.php"; ?>