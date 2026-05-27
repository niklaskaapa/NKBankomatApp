<?php


ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_secure', '0'); // sätt till 1 om det ej är http://localhost
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.use_strict_mode', '1');

session_start();

require __DIR__ . '/../src/helpers.php';
check_absolute_timeout();
check_idle_timeout();

require __DIR__ . '/../src/db.php';

require __DIR__ . '/../src/Interfaces/RepositoryInterface.php';

require __DIR__ . '/../src/Repositories/UserRepository.php';
require __DIR__ . '/../src/Repositories/AccountRepository.php';
require __DIR__ . '/../src/Repositories/TransactionRepository.php';



$db = getDB();
$userRepo = new UserRepository($db);
$accountRepo = new AccountRepository($db);
$transactionRepo = new TransactionRepository($db);

$route = $_GET["route"] ?? "home";

if($route === "home") {
    require "../views/home.php";
    exit;
}

if ($route === "login") {

    if($_SERVER["REQUEST_METHOD"] === "POST") {

            csrf_verify();

            $card = trim($_POST['card_number'] ?? '');
            $pin = $_POST['pin'] ?? '';

            $user = $userRepo->findByCardNumber($card);

            if($user && password_verify($pin, $user["pin_hash"])) {
                
                session_regenerate_id(true);
                
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["role"] = $user["role"];
                $_SESSION["name"] = $user["name"];

                $_SESSION["login_time"] = time();
                $_SESSION["last_active"] = time();

                header("Location: ?route=dashboard");
                exit;
            }

            $error = "Wrong cardnumber or PIN";


    }

    require "../views/login.php";
    exit;

}

if ($route === "dashboard") {
    requireAuth();

    $accounts = $accountRepo->getByUserId($_SESSION["user_id"]);

    require "../views/dashboard.php";
    exit;
}

if ($route === "withdraw") {
    requireAuth();

    $accounts = $accountRepo->getByUserId($_SESSION['user_id']);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        csrf_verify();

        $accountId = (int) ($_POST["account_id"] ?? 0);
        $amount = (float) ($_POST["amount"] ?? 0);

        
        if ($accountId <= 0) {
            $error = "You must choose an account.";
        } 
        
        elseif ($amount <= 0) {
            $error = "Invalid amount";
        } 
        else {

            $account = $accountRepo->findById($accountId);

            if (!$account || $account["user_id"] != $_SESSION["user_id"]) {
                $error = "Unauthorized account";
            } 
            elseif ($account["balance"] < $amount) {
                $error = "Not enough money to proceed with the withdrawal.";
            } 
            else {

                $accountRepo->withdraw($accountId, $amount);

                $now = date("Y-m-d H:i:s");

                $transactionRepo->create(
                    $accountId,
                    null,
                    "withdrawal",
                    $amount,
                    $now
                );

                $_SESSION["flash_success"] = "Successful withdrawal $amount kr";

                header("Location: ?route=dashboard");
                exit;
            }
        }
    }

    require '../views/withdraw.php';
    exit;
}

if ($route === "deposit") {
    requireAuth();

    $accounts = $accountRepo->getByUserId($_SESSION["user_id"]);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        csrf_verify();

        $accountId = (int) ($_POST["account_id"] ?? 0);
        $amount = (float) ($_POST["amount"] ?? 0);

        
        if ($accountId <= 0) {
            $error = "You must choose an account.";
        }
        elseif ($amount <= 0) {
            $error = "Invalid amount";
        }
        elseif ($amount > 10000) {
            $error = "Maximum amount to deposit is 10 000.";
        }
        else {

            $account = $accountRepo->findById($accountId);

            
            if (!$account || $account["user_id"] != $_SESSION["user_id"]) {
                $error = "Unauthorized account";
            }
            else {

                $accountRepo->deposit($accountId, $amount);

                $now = date("Y-m-d H:i:s");

                $transactionRepo->create(
                    null,
                    $accountId,
                    "deposit",
                    $amount,
                    $now
                );

                $_SESSION["flash_success"] = "Successful deposit $amount kr";

                header("Location: ?route=dashboard");
                exit;
            }
        }
    }

    require '../views/deposit.php';
    exit;
}

if ($route === "transfer") {

    requireAuth();

    $accounts = $accountRepo->getByUserId($_SESSION["user_id"]);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        csrf_verify();

        $fromId = (int) ($_POST["from_account"] ?? 0);
        $toId = (int) ($_POST["to_account"] ?? 0);
        $amount = (float) ($_POST['amount'] ?? 0);

        if ($fromId <= 0 || $toId <= 0) {
            $error = "You must choose both accounts.";
        }
        elseif ($fromId === $toId) {
            $error = "Cannot transfer to same account";
        }
        elseif ($amount <= 0) {
            $error = "Invalid amount";
        }
        else {

            $fromAccount = $accountRepo->findById($fromId);
            $toAccount = $accountRepo->findById($toId);

            if (
                !$fromAccount ||
                !$toAccount ||
                $fromAccount['user_id'] != $_SESSION['user_id'] ||
                $toAccount['user_id'] != $_SESSION['user_id']
            ) {
                $error = "Unauthorized account";
            }
            elseif ($fromAccount["balance"] < $amount) {
                $error = "Not enough money to proceed with transfer";
            }
            else {

                try {

                    $db->beginTransaction();

                    $accountRepo->transfer($fromId, $toId, $amount);

                    $now = date("Y-m-d H:i:s");

                    $transactionRepo->create(
                        $fromId,
                        $toId,
                        "transfer",
                        $amount,
                        $now
                    );

                    $db->commit();

                    $_SESSION["flash_success"] = "Successful transfer $amount kr";

                    header("Location: ?route=dashboard");
                    exit;

                } catch (Exception $e) {

                    $db->rollBack();

                    $error = "Transfer failed";
                }
            }
        }
    }

    require '../views/transfer.php';
    exit;
}

if ($route === "admin/users") {
    
    requireAuth();
    require_role("admin");

    $users = $userRepo->getAll();

    require '../views/admin_users.php';
    exit;
}

if ($route === "admin/accounts") {
    
    requireAuth();
    require_role("admin");

    $accounts = $accountRepo->getAll();

    require '../views/admin_accounts.php';
    exit;
}

if ($route === "admin/transactions") {

    requireAuth();
    require_role("admin");

    $transactions = $transactionRepo->getAll();

     require '../views/admin_transactions.php';
     exit;
}


if ($route === "logout") {

    
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        die("Invalid request");
    }

    
    csrf_verify();

    
    $_SESSION = [];

    
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            [
                'expires'  => time() - 42000,
                'path'     => $params['path'],
                'domain'   => $params['domain'],
                'secure'   => $params['secure'],
                'httponly' => $params['httponly'],
                'samesite' => $params['samesite'] ?? 'Strict',
            ]
        );
    }

    session_destroy();

    header("Location: ?route=login&logged_out=1");
    exit;
}

http_response_code(404);
echo "404 – Page not found";




?>