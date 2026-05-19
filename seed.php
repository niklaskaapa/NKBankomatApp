<?php

require __DIR__ . "/src/db.php";

$pdo = getDB();

$pdo->exec("DROP TABLE IF EXISTS transactions");
$pdo->exec("DROP TABLE IF EXISTS accounts");
$pdo->exec("DROP TABLE IF EXISTS users");

$pdo->exec(file_get_contents(__DIR__ . "/schema.sql"));

$now = date("Y-m-d H:i:s");

$users = [
    ["card_number" => "1234", "pin" => "1111", "name" => "Anders Andersson", "role" => "user"],
    ["card_number" => "5678", "pin" => "2222", "name" => "Bosse Bossesson", "role" => "user"],
    ["card_number" => "6464", "pin" => "3333", "name" => "David Davidsson", "role" => "user"],
    ["card_number" => "9999", "pin" => "0000", "name" => "Admin Adminsson", "role" => "admin"],
];

$stmt = $pdo->prepare(
    "INSERT INTO users (card_number, pin_hash, name, role, created_at) VALUES (?, ?, ?, ?, ?)"
);

foreach($users as $u){ 
    $stmt->execute([
    $u["card_number"],
    password_hash($u["pin"], PASSWORD_BCRYPT),
    $u["name"],
    $u["role"],
    $now
    ]);
}

$accountStmt = $pdo->prepare(
    "INSERT INTO accounts (user_id, account_type, balance, created_at) VALUES (?, ?, ?, ?)"
);

$accountStmt->execute([1, "checking", 5000.00, $now]);
$accountStmt->execute([1, "saving", 10000.00, $now]);

$accountStmt->execute([2, "checking", 6000.00, $now]);
$accountStmt->execute([2, "saving", 12000.00, $now]);

$accountStmt->execute([3, "checking", 7000.00, $now]);
$accountStmt->execute([3, "saving", 14000.00, $now]);

$accountStmt->execute([4, "checking", 10000.00, $now]);
$accountStmt->execute([4, "saving", 10000.00, $now]);


$transactionStmt = $pdo->prepare(
    "INSERT INTO transactions (from_account_id, to_account_id, type, amount, created_at)
     VALUES (?, ?, ?, ?, ?)"
);

$transactionStmt->execute([1, null, "deposit", 1000.00, $now]);        
$transactionStmt->execute([null, 2, "withdrawal", 2000.00, $now]);     
$transactionStmt->execute([3, 4, "transfer", 3000.00, $now]);          

echo "Seeddata skapad\n";


?>