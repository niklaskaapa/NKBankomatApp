<?php
/** @var array $transactions */
?>

<?php $title = "Admin - Transactions"; ?>
<?php require __DIR__ . "/partials/header.php"; ?>

<h2 class="page-title">Admin - Transactions</h2>

    <table class="table-spaced">

        <tr>
            <th>ID</th>
            <th>Type</th>
            <th>Amount</th>
            <th>From account id</th>
            <th>Type</th>
            <th>To account id</th>
            <th>Type</th>
            <th>Date</th>
        </tr>

        <?php foreach ($transactions as $taction): ?>
            <tr>
                <td><?= htmlspecialchars($taction["id"]) ?></td>
                <td><?= htmlspecialchars($taction["type"]) ?></td>
                <td><?= htmlspecialchars($taction["amount"]) ?></td>
                <td><?= htmlspecialchars($taction["from_account_id"] ?? '-') ?></td>
                <td><?= htmlspecialchars($taction["from_account_type"] ?? '-') ?></td>
                <td><?= htmlspecialchars($taction["to_account_id"] ?? '-') ?></td>
                <td><?= htmlspecialchars($taction["to_account_type"] ?? '-') ?></td>
                <td><?= htmlspecialchars($taction["created_at"]) ?></td>
            </tr>


        <?php endforeach; ?>

    </table>

<div class="center">
    <a class="back-link" href="?route=dashboard">Go back</a>
</div>    

<?php require __DIR__ . "/partials/footer.php"; ?>