<?php
/** @var array $accounts */
?>

<?php $title = "Admin - Accounts"; ?>
<?php require __DIR__ . "/partials/header.php"; ?>

<h2 class="page-title">Admin - Accounts</h2>

    <table class="table-spaced">
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Account owner</th>
            <th>Type</th>
            <th>Balance</th>
        </tr>

        <?php foreach ($accounts as $acc): ?>

            <tr>
                <td><?= htmlspecialchars($acc["id"]) ?></td>
                <td><?= htmlspecialchars($acc["user_id"]) ?></td>
                <td><?= htmlspecialchars($acc["owner_name"]) ?></td>
                <td><?= htmlspecialchars($acc["account_type"]) ?></td>
                <td><?= htmlspecialchars($acc["balance"]) ?></td>
            </tr>

        <?php endforeach; ?>

    </table>

<div class="center">
    <a class="back-link" href="?route=dashboard">Go back</a>
</div>

<?php require __DIR__ . "/partials/footer.php"; ?>