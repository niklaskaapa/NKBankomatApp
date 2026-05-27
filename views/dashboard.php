<?php
/** @var array $accounts */
?>

<?php $title = "Dashboard"; ?>
<?php require __DIR__ . "/partials/header.php"; ?>
                                                

<div class="dashboard-header">
    <h2>Dashboard Overview </h2>

    <p>Welcome <?= htmlspecialchars($_SESSION["name"]) ?> </p>

    <p>You're signed in!</p>

    <?php if (!empty($_SESSION["flash_success"])): ?>
        <p><?= htmlspecialchars($_SESSION["flash_success"]) ?></p>

        <?php unset($_SESSION["flash_success"]); ?>
        
    <?php endif; ?>
    

</div>

 <div class="table-spaced">   
    
    <p class="accounts-title">Your accounts:</p>


    <table>
        <tr>
            <th>ID</th>
            <th>Type</th>
            <th>Balance</th>
        </tr>

        <?php foreach ($accounts as $acc): ?>
            <tr>
                <td><?= htmlspecialchars($acc["id"]) ?></td>
                <td><?= htmlspecialchars($acc["account_type"]) ?></td>
                <td><?= htmlspecialchars($acc["balance"]) ?> kr</td>
            </tr>
        <?php endforeach; ?>

    </table>

</div>   

    <div class="dashboard-links">
        <a href="?route=withdraw">Withdraw money</a>
        <a href="?route=deposit">Deposit money</a>
        <a href="?route=transfer">Transfer money</a>
    </div>

<?php if ($_SESSION["role"] === "admin"): ?>

<div class="dashboard-header">
    <h3>Admin Panel</h3>
</div>

<div class="dashboard-links">
    <a href="?route=admin/users">View Users</a>
    <a href="?route=admin/accounts">View Accounts</a>
    <a href="?route=admin/transactions">View Transactions</a>
</div>

<?php endif; ?>

<?php require __DIR__ . "/partials/footer.php"; ?>

