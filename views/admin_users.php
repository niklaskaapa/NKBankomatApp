<?php
/** @var array $users */
?>

<?php $title = "Admin - Users"; ?>
<?php require __DIR__ . "/partials/header.php"; ?>

<h2 class="page-title">Admin - Users</h2>

    <table class="table-spaced">

        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Card Number</th>
            <th>Role</th>
            <th>Created</th>
        </tr>

        <?php foreach($users as $user): ?>

            <tr>
                <td><?= htmlspecialchars($user["id"]) ?></td>
                <td><?= htmlspecialchars($user["name"]) ?></td>
                <td><?= htmlspecialchars($user["card_number"]) ?></td>
                <td><?= htmlspecialchars($user["role"]) ?></td>
                <td><?= htmlspecialchars($user["created_at"]) ?></td>

            </tr>

        <?php endforeach; ?>

    </table>
 
<div class="center">
    <a class="back-link" href="?route=dashboard">Go back</a>
</div>

<?php require __DIR__ . "/partials/footer.php"; ?>