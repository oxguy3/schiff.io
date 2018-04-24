<?php

function getPDO() {
    $db_paths = [
        '/var/www/sqlite/xss-demo.db',
        '/tmp/xss-demo.db'
    ];
    foreach ($db_paths as $path) {
        if (file_exists($path)) {
            return new PDO('sqlite:'.$path);
        }
    }
    return false;
}

function printHead($page_title) {
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Widgets Inc.: <?php echo $page_title; ?></title>

    <link href="assets/bootstrap.min.css" rel="stylesheet">

    <style>
    main.container {
        margin-top: 80px;
    }
    .profile-pic {
        background: #ccc 50% 50% no-repeat;
        width: 256px;
        height: 256px;
    }
    </style>

    <script src="assets/jquery-3.3.1.slim.min.js"></script>
    <script src="assets/popper.min.js"></script>
    <script src="assets/bootstrap.min.js"></script>
    <script>
    function declareChampion(name) {
        $('#champion').text(name);
    }
    </script>
  </head>

  <body>

    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
      <a class="navbar-brand" href="#">Widgets Inc.</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="widgets-register.php">Register</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="widgets-user.php" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">User list</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="widgets-user.php?mode=easy">Easy</a>
                    <a class="dropdown-item" href="widgets-user.php?mode=hard">Hard</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="widgets-help.php">Help</a>
            </li>
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
