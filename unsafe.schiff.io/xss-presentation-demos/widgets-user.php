<?php
require_once('widgets-common.php');
$db = getPDO();

$mode = 'easy';
if ($_GET['mode'] == 'easy' || $_GET['mode'] == 'hard') {
    $mode = $_GET['mode'];
} else {
    header('Location: widgets-user.php?mode=easy');
    die();
}

printHead('Registered users');
?>
    <main role="main" class="container">
        <div class="row">
            <div class="col-12">
                <h2>The XSS Champion is: <span id="champion">no one</span>!</h2>
                <p>If you want to be the champion, you have to perform an XSS injection that runs this JavaScript function: <code>declareChampion(myName);</code> (replace <code>myName</code> with a string of your name)</p>
                <p>You are currently on <strong><?php echo $mode; ?></strong> mode.</p>

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
                        if ($mode == 'easy') {
                            $imageUrl = htmlspecialchars($row['imageUrl'], ENT_NOQUOTES | ENT_HTML401);
                        } else {
                            $imageUrl = htmlspecialchars($row['imageUrl'], ENT_QUOTES | ENT_HTML401);
                        }
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
<?php
printFoot();
