<?php
/** @var array $accounts */
?>

<?php $title = "Transfer"; ?>
<?php require __DIR__ . "/partials/header.php"; ?>

    <div class="form-container">

        <h2>Transfer</h2>

        <?php if (!empty($error)): ?>
            <p>
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <form method="POST">

        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token())  ?>">

        <label>From account:</label>

        <select name="from_account">
            <option value="">-- choose account --</option>

        <?php foreach ($accounts as $acc): ?>

                    <option value="<?= $acc["id"] ?>">

                        <?= htmlspecialchars($acc["account_type"]) ?>
                        -
                        <?= htmlspecialchars($acc["balance"]) ?> kr

                    </option>

            <?php endforeach; ?>

        </select>

        

        <label>To account:</label>

        <select name="to_account">
            <option value="">-- choose account --</option>

                <?php foreach ($accounts as $acc): ?>

                    <option value="<?= $acc["id"] ?>">

                        <?= htmlspecialchars($acc["account_type"]) ?>
                        -
                        <?= htmlspecialchars($acc["balance"]) ?> kr

                    </option>

                <?php endforeach; ?>

        </select>

            

            <label>Amount:</label>

            <input type="number"
                step="0.01"
                name="amount"
                required>

            

            <button type="submit">
                Transfer
            </button>

        </form>

        

        <a class="btn-back" href="?route=dashboard">Go back</a>

    </div>


<?php require __DIR__ . "/partials/footer.php"; ?>