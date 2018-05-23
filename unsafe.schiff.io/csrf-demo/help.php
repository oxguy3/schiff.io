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

                <p>You can prototype building your malicious website at <a href="https://codepen.io/">CodePen</a>. To make your life easier, I recommend using jQuery for writing your JavaScript. You can easily import jQuery on a CodePen pen by clicking Settings, going to the JS tab, and selecting jQuery from the Quick-add dropdown menu at the bottom.</p>

                <p>Hinty hint if you're not used to JavaScript: HTTP requests are often called Ajax requests when they're made from JavaScript code running in a browser. You can find info about jQuery's Ajax methods <a href="https://www.w3schools.com/jquery/jquery_ref_ajax.asp">here</a>.</p>
            </div>
        </div>
    </main><!-- /.container -->
<?php
printFoot();
