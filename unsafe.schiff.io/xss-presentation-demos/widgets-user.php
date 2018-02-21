<?php
$db = new PDO('sqlite:/var/www/sqlite/xss-demo.db');
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Widget Inc.: Registered users</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">

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
            <li class="nav-item active">
                <a class="nav-link" href="widgets-user.php">Users <span class="sr-only">(current)</span></a>
            </li>
        </ul>
      </div>
    </nav>

    <main role="main" class="container">
        <div class="row">
            <div class="col-12">
                <h2>The XSS Champion is: <span id="champion">no one</span>!</h2>
                <p>If you want to be the champion, you have to perform an XSS injection that runs this JavaScript function: <code>declareChampion(myName);</code> (replace <code>myName</code> with a string of your name)</p>

                <hr>
                <h2>List of registered users</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Pic</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Twitter</th>
                        </tr>
                    </thead>
                <?php
                $result = $db->query("SELECT * FROM registrations");

                foreach($result as $row)
                {
                    print "<tr>";
                    $imageUrl = "placeholder-user.png";
                    if (array_key_exists('imageUrl', $row) && $row['imageUrl']!=NULL && $row['imageUrl']!='') {
                        $imageUrl = htmlspecialchars($row['imageUrl']);
                    }
                    print "<td><img height=\"32\" width=\"32\" src='".$imageUrl."'/></td>";
                    print "<td>".htmlspecialchars($row['nameFirst'])." ".htmlspecialchars($row['nameMiddle'])." ".htmlspecialchars($row['nameLast'])."</td>";
                    print "<td>".htmlspecialchars($row['email'])."</td>";
                    print "<td>".htmlspecialchars($row['phone'])."</td>";
                    if (array_key_exists('twitter', $row) && $row['twitter']!=NULL && $row['twitter']!='') {
                        print "<td><a href=\"https://twitter.com/".htmlspecialchars($row['twitter'])."\">@".htmlspecialchars($row['twitter'])."</a></td>";
                    } else {
                        print "<td></td>";
                    }
                    print "</tr>";
                }

                ?>
                </table>

            </div>
        </div>
    </main><!-- /.container -->

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
    <script>
    function declareChampion(name) {
        $('#champion').text(name);
    }
    </script>
  </body>
</html>
