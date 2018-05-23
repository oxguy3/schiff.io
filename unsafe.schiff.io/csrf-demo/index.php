<?php
require_once('common.php');

requireLoggedIn();
$user = getCurrentUser();

if (isset($_GET['transfer'])) {
    initializeDbObject();

    // retrieve and validate email address
    $email = $_GET['email'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("hey jerkface, that's not a real email address");
    }

    // retrieve and validate amount
    $amount = $_GET['amount'];
    if (!is_numeric($amount)) {
        die("transfer amounts have to be numbers, ya dang goobus");
    }
    $amount = floatval($amount);
    if ($amount <= 0) {
        die("transfer amount <= 0?? just what kind of stunt are you trying to pull here buddy");
    }
    if ($amount > $user->balance) {
        die("lol you're too broke to transfer that much money");
    }

    $recipient = selectAccountByEmail($email);

    // if user doesn't exist, create as new user
    if (!$recipient) {
        $recipientId      = createNewAccount($email);
        $recipientBalance = STARTING_ACCOUNT_BALANCE;
    } else {
        $recipientId      = $recipient->id;
        $recipientBalance = $recipient->balance;
    }

    // make the transfers!
    $transferSuccess = true;
    $transferSuccess = $transferSuccess && updateAccountBalance($user->id, $user->balance - $amount);
    $transferSuccess = $transferSuccess && updateAccountBalance($recipientId, $recipientBalance + $amount);

    // update our user object since the balance is now out of date
    $user = getCurrentUser();
}

printHead('Your Account');
printNavbar();
?>
    <main role="main" class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="display-4">Welcome to HNY Bank!</h1>

                <p>You are logged in as <?php echo htmlspecialchars($user->email, ENT_QUOTES | ENT_HTML5); ?>.</p>

                <p>Current balance: $<?php echo number_format($user->balance, 2); ?></p>

                <h2 class="h3">Make a transfer</h2>
                <?php if (isset($transferSuccess)) { ?>
                    <div class="alert alert-<?php echo $transferSuccess ? "success" : "danger" ?>" role="alert">
                        <?php echo $transferSuccess ? "Money successfully transferred!" : "Something went wrong with your transfer." ?>
                    </div>
                <?php } ?>
                <form action="index.php" method="get" class="form-inline">
                    <label class="sr-only" for="transferFormEmail">Email address</label>
                    <input type="email" class="form-control mb-2 mr-sm-2" id="transferFormEmail" name="email" placeholder="Email address">

                    <label class="sr-only" for="transferFormAmount">Amount</label>
                    <div class="input-group mb-2 mr-sm-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">$</div>
                        </div>
                        <input type="number" class="form-control" id="transferFormAmount" placeholder="Amt" name="amount"
                            step=".01" min="0" max="<?php echo $user->balance; ?>">
                    </div>

                    <button type="submit" name="transfer" class="btn btn-primary mb-2">Send money</button>
                </form>
            </div>
        </div>
    </main><!-- /.container -->
<?php
printFoot();
