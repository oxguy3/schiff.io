<?php
require_once('widgets-common.php');

$db = getPDO();

$insertId = false;

if (isset($_POST['register'])) {
    $insertSql = 'INSERT INTO registrations(email, password, nameFirst, nameMiddle, nameLast, phone, twitter, imageUrl) VALUES(:email, :password, :nameFirst, :nameMiddle, :nameLast, :phone, :twitter, :imageUrl)';
    $stmt = $db->prepare($insertSql);
    $stmt->execute([
        ':email' => $_POST['email'],
        ':password' => $_POST['password'],
        ':nameFirst' => $_POST['nameFirst'],
        ':nameMiddle' => $_POST['nameMiddle'],
        ':nameLast' => $_POST['nameLast'],
        ':phone' => $_POST['phone'],
        ':twitter' => $_POST['twitter'],
        ':imageUrl' => $_POST['imageUrl'],
    ]);
    $insertId = $db->lastInsertId();
}
printHead('Register your account');
?>
    <main role="main" class="container">
        <div class="row">
            <div class="col-12">
                <h2>Welcome to Widgets Inc! Please register your account.</h2>
                <div class="alert alert-danger" role="alert">
                    <strong>Warning:</strong> Please don't submit any real information to this demo! Nothing about this page is secure!
                </div>
                <form method="post">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="registerInputEmail">Email address*</label>
                            <input type="email" name="text" class="form-control" id="registerInputEmail" placeholder="hjfarnsworth@planetexpress.co" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="registerInputPassword">Password*</label>
                            <input type="password" name="password" class="form-control" id="registerInputPassword" placeholder="hunter2" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-5">
                            <label for="registerInputFirstName">First name</label>
                            <input type="text" name="nameFirst" class="form-control" id="registerInputFirstName" placeholder="Hubert">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="registerInputMiddleInitial">M.I.</label>
                            <input type="text" name="nameMiddle" class="form-control" id="registerInputMiddleInitial" placeholder="J">
                        </div>
                        <div class="form-group col-md-5">
                            <label for="registerInputLastName">Last name</label>
                            <input type="text" name="nameLast" class="form-control" id="registerInputLastName" placeholder="Farnsworth">
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
                            <label for="registerInputImageURL">Profile picture URL <small>(32x32 pixels)</small></label>
                            <input type="text" name="imageUrl" class="form-control" id="registerInputImageURL" placeholder="https://i.imgur.com/R8IFHAB.jpg">
                        </div>
                    </div>
                    <button type="submit" name="register" class="btn btn-primary">Submit</button>
                </form>
                <?php if ($insertId !== false) { ?>
                <div class="alert alert-success" role="alert">
                    <strong>Success!</strong> Your account has been registered! You are user #<?php echo ($insertId + 1); ?>. You can now see your account on <a href="widgets-user.php">the user list</a>.
                </div>
                <?php } ?>

            </div>
        </div>
    </main><!-- /.container -->
<?php
printFoot();
