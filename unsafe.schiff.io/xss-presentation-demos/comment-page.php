<?php
if (isset($_GET['insecure'])) {
    header('X-XSS-Protection: 0');
}
?>
<!doctype html>
<html>
<head>
    <title>Demo: Contact page with XSS vulnerability</title>
    <style>
    .your-comment {
        padding: 10px;
        border: 1px solid black;
        width: 400px;
    }
    </style>
    <script>
    document.cookie = "username=hayden";
    document.cookie = "auth_token=KCDNocKwIM2cypYgzaHCsCk=";
    </script>
</head>
<body>
    <h1>Demo: Contact page with XSS vulnerability</h1>
    <form method="get">
        <p>To contact us, please submit a message below:</p>
        <textarea id="commentBox" name="comment" cols="60" rows="8"></textarea>
        <br />
        <input type="submit" />
    </form>

    <p>
        Examples of malicious messages:
        <select id="demoComments">
            <option value="">
                -- Select one --
            </option>
            <option value="&lt;strong&gt;This is bold!&lt;/strong&gt; &lt;em&gt;This is italicized!&lt;/em&gt; &lt;font color=&quot;blue&quot;&gt;This is blue!&lt;/font&gt;">
                Some HTML formatting
            </option>
            <option value="&lt;script&gt;alert(&quot;Hacked! I just stole all your cookies: \n&quot;+document.cookie);&lt;/script&gt;">
                JavaScript popup
            </option>
            <option value="&lt;div style=&quot;position:absolute; top:0; left:0; width:100%; height:100%; background: white; padding: 15px;&quot;&gt;&lt;h1&gt;Please log back in&lt;/h1&gt;&lt;p&gt; You've been logged out due to inactivity. Please click &lt;a href=&quot;https://youtu.be/dQw4w9WgXcQ&quot;&gt;this totally legit, definitely-not-evil link&lt;/a&gt; to log in again.&lt;/p&gt;&lt;/div&gt;">
                Fake login page
            </option>
        </select>
    </p>
    <script>
    document.getElementById("demoComments").onchange = function()
    {
        document.getElementById("commentBox").innerText = this.value;
    }
    </script>

    <?php if (isset($_GET['comment'])) { ?>
        <hr>
        <h2>Thanks for your submission!</h2>
        <p>Your message was:</p>
        <div class="your-comment">
            <?php echo $_GET['comment']; ?>
        </div>
    <?php } ?>
</body>
</html>
