<?php
require_once('common.php');

// handle logout
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    die();
}

// handle login
if (isset($_POST['login'])) {

    initializeDbObject();

    // retrieve and validate email address
    $email = $_POST['email'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("hey jerkface, that's not a real email address");
    }

    $row = selectAccountByEmail($email);

    // if user doesn't exist, create as new user
    if (!$row) {
        $_SESSION['account_id'] = createNewAccount($email);
    } else {
        $_SESSION['account_id'] = $row->id;
    }
    header('Location: index.php');
    die();
}

printHead('Login');
?>
<style>
html,
body {
    height: 100%;
}

body {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-align: center;
    align-items: center;
    padding-top: 40px;
    padding-bottom: 40px;
    background-color: #f5f5f5;
}

.form-signin {
    width: 100%;
    max-width: 360px;
    padding: 15px;
    margin: auto;
}

.form-signin .checkbox {
    font-weight: 400;
}

.form-signin .form-control {
    position: relative;
    box-sizing: border-box;
    height: auto;
    padding: 10px;
    font-size: 16px;
}

.form-signin .form-control:focus {
    z-index: 2;
}

.form-signin input[type="email"] {
    margin-bottom: -1px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
}

.form-signin input[type="password"] {
    margin-bottom: 10px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
}
</style>
    <main role="main" class="container">
        <form class="form-signin" action="login.php" method="POST">
            <h1 class="display-4 text-center">
                <img class="mb-4" src="assets/logo.png" alt="" width="72" height="72">
                HNY Bank
            </h1>
            <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>

            <label for="inputEmail" class="sr-only">Email address</label>
            <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" required autofocus>

            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password">

            <div class="checkbox mb-3">
                <label>
                  <input type="checkbox" value="remember-me"> Remember me
                </label>
            </div>

            <button class="btn btn-lg btn-primary btn-block" name="login" type="submit">Sign in</button>
            <p class="small text-muted">(psst... passwords aren't validated... you can leave it blank for all I care)</p>

            <p class="mt-5 mb-3 text-muted">&copy; 2018 Hopefully Not Your Bank LLC<br><small>(not really lol)</small></p>
        </form>
    </main><!-- /.container -->
<?php
printFoot();
