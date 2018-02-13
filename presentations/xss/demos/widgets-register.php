<?php
header("Content-Security-Policy: default-src * 'unsafe-eval' 'unsafe-inline'");
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Widget Inc.: Register your account</title>

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
          <li class="nav-item active">
            <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">About Us</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Support</a>
          </li>
        </ul>
      </div>
    </nav>

    <main role="main" class="container">
        <div class="row">
            <div class="col-12">
                <h2>Welcome to Widgets Inc! Please register your account.</h2>
                <div class="alert alert-danger" role="alert">
                    <strong>Warning!</strong> Please don't submit any real information to this demo! I wrote really lazy database code. :)
                </div>
                <form method="post">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="registerInputEmail">Email address*</label>
                            <input type="email" name="email" class="form-control" id="registerInputEmail" placeholder="hjfarnsworth@planetexpress.co" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="registerInputPassword">Password*</label>
                            <input type="password" name="password" class="form-control" id="registerInputPassword" placeholder="hunter2" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-5">
                            <label for="registerInputFirstName">First name*</label>
                            <input type="text" name="nameFirst" class="form-control" id="registerInputFirstName" placeholder="Hubert" required>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="registerInputMiddleInitial">M.I.</label>
                            <input type="text" name="nameMiddle" class="form-control" id="registerInputMiddleInitial" placeholder="J">
                        </div>
                        <div class="form-group col-md-5">
                            <label for="registerInputLastName">Last name*</label>
                            <input type="text" name="nameLast" class="form-control" id="registerInputLastName" placeholder="Farnsworth" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="registerInputPhone">Phone</label>
                            <input type="tel" name="phone" class="form-control" id="registerInputPhone" placeholder="513-867-5309">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="registerInputTwitter">Twitter username</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">@</span>
                                </div>
                                <input type="text" name="twitter" class="form-control" id="registerInputTwitter" placeholder="prof_farnsworth">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="registerInputImageURL">Profile picture URL</label>
                            <input type="url" name="imageUrl" class="form-control" id="registerInputImageURL" placeholder="https://i.imgur.com/R8IFHAB.jpg">
                        </div>
                    </div>
                    <button type="submit" name="register" class="btn btn-primary">Submit</button>
                </form>
                <!-- <pre><?php print_r($_POST); ?></pre> -->
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <hr>
                <h2>List of registered users</h2>
            </div>
        </div>
    </main><!-- /.container -->

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
