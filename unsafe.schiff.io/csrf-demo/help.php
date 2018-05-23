<?php
require_once('common.php');

requireLoggedIn();

printHead('Help');
printNavbar();
?>
    <main role="main" class="container">
        <div class="row">
            <div class="col-12">
                <h1>Help</h1>

                <p>This site is a demo from Cyber@UC Meeting #53. You can find the slides and video for this demo <a href="http://cyberatuc.org/meetings/53">here</a>.</p>

                <p>You can prototype building your malicious website at <a href="https://codepen.io/">CodePen</a>.</p>
            </div>
        </div>
    </main><!-- /.container -->
<?php
printFoot();
