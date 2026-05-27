<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? "NK Bank App" ?></title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<nav>
    <div class="logo">NK Bank App</div>

    <?php if (isset($_SESSION["user_id"])): ?>

        <form method="POST" action="?route=logout">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
            <button class="login-btn" type="submit">Logout</button>
        </form>
       
    <?php else: ?>
         <a class="login-btn" href="?route=login">Login</a>
    <?php endif; ?>
    
</nav>

<main>
