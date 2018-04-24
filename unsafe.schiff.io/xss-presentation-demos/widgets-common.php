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

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" rel="stylesheet">

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

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
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
