<?php

session_start();

define('STARTING_ACCOUNT_BALANCE', 1000);

// creates db object
function getPDO() {
    $db_paths = [
        '/var/www/sqlite/csrf-demo.db',
        '/tmp/csrf-demo.db',
        '/Users/hayden/Downloads/csrf-demo.db'
    ];
    foreach ($db_paths as $path) {
        if (file_exists($path)) {
            return new PDO('sqlite:'.$path);
        }
    }
    return false;
}

// sets the global db object if it isn't already set
function initializeDbObject() {
    global $db;
    if (!isset($db)) {
        $db = getPDO();
    }
}

function selectAccountByEmail($email) {
    global $db;
    initializeDbObject();

    $selectSql = "SELECT * FROM accounts WHERE email = :email";
    $selectStmt = $db->prepare($selectSql);
    $selectStmt->bindParam(':email', $email, PDO::PARAM_STR);
    $selectStmt->execute();
    $row = $selectStmt->fetchObject();

    return $row;
}

function updateAccountBalance($accountId, $balance) {
    global $db;
    initializeDbObject();

    $updateSql = 'UPDATE accounts SET balance = :balance WHERE id = :id';
    $updateStmt = $db->prepare($updateSql);
    $updateStmt->bindParam(':id', $accountId, PDO::PARAM_INT);
    $updateStmt->bindParam(':balance', $balance, PDO::PARAM_STR);
    
    return $updateStmt->execute();
}

function createNewAccount($email) {
    global $db;
    initializeDbObject();

    $balance = STARTING_ACCOUNT_BALANCE;
    $insertSql = 'INSERT INTO accounts(email, balance) VALUES (:email, :balance)';
    $insertStmt = $db->prepare($insertSql);
    $insertStmt->bindParam(':email', $email, PDO::PARAM_STR);
    $insertStmt->bindParam(':balance', $balance, PDO::PARAM_STR);
    $insertStmt->execute();

    return $db->lastInsertId();
}

function isLoggedIn() {
    return isset($_SESSION['account_id']);
}

function getCurrentUser() {
    global $db;
    initializeDbObject();

    if (!isLoggedIn()) return false;

    $selectSql = "SELECT * FROM accounts WHERE id = :account_id";
    $stmt = $db->prepare($selectSql);
    $stmt->bindParam(':account_id', $_SESSION['account_id'], PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetchObject();
    return $row;
}

function requireLoggedIn() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        die();
    }
}

function printHead($page_title) {
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Hopefully Not Your Bank: <?php echo $page_title; ?></title>

    <link rel="icon" href="assets/logo.png" type="image/png">

    <link href="assets/bootstrap.min.css" rel="stylesheet">
    <style>
    main.container {
        margin-top: 80px;
    }
    </style>

    <script src="assets/jquery-3.3.1.slim.min.js"></script>
    <script src="assets/popper.min.js"></script>
    <script src="assets/bootstrap.min.js"></script>
    <script>
    </script>
  </head>

  <body>
<?php
}

function printNavbar() {
    ?>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="index.php">Hopefully Not Your Bank</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="help.php">Help</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0" action="login.php" method="POST">
                <button class="btn btn-secondary my-2 my-sm-0" name="logout" type="submit">Log out</button>
            </form>
        </div>
    </nav>
    <?php
}

function printFoot() {
?>
    </body>
</html>
<?php
}
