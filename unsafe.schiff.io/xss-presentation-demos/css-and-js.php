<?php
header("Content-Security-Policy: default-src * 'unsafe-eval' 'unsafe-inline'");
?>
<!doctype html>
<html>
  <head>
    <title>This is a webpage!</title>

    <!-- Everything inside the style tag is CSS. -->
    <style>
    body {
      max-width: 600px;
      margin: auto;
      padding: 5px 15px;
    }
    h1 {
      color: blue;
      font-style: italic;
    }
    p.red-box {
      padding: 5px;
      border: 2px solid red;
      width: 400px;
      text-align: center;
    }
    </style>

  </head>
  <body>
    <h1>This is a webpage!</h1>

    <p>Click the button below to see JavaScript in action:</p>
    <button id="myButton">Boop</button>

    <p class="red-box">
      You've clicked the button <span id="myCounter">0</span> times!
    </p>

    <!-- Everything inside the script tag is JavaScript. -->
    <script>
    var count = 0;
    var button = document.getElementById("myButton");
    var counter = document.getElementById("myCounter");

    button.onclick = function(){
      count = count + 1;
      counter.innerText = count;
    }
    </script>
  </body>
</html>
