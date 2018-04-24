<?php
require_once('widgets-common.php');

printHead('Help');
?>
    <main role="main" class="container">
        <div class="row">
            <div class="col-12">
                <h1>Help</h1>

                <p>This page is a quick rundown of some simple things you can try to pull off an XSS injection. This is all general advice—it may or may not be applicable to the Widgets Inc demo.</p>
                <p>This is just a handful of tips I threw together, so here are some other links that cover what I didn't:</p>
                <ul>
                    <li><a href="https://www.owasp.org/index.php/XSS_Filter_Evasion_Cheat_Sheet">OWASP Filter Evasion Cheat Sheet</a></li>
                    <li><a href="http://n0p.net/penguicon/php_app_sec/mirror/xss.html">XSS Cheatsheet by RSnake</a></li>
                </ul>

                <h1>Cheatsheet</h1>

                <h2>Plain ole script tag</h2>
                <p>If the web developer has done absolutely no input sanitization whatsoever, you can just drop in a script tag. Websites programmed <em>this</em> badly tend not to stay online very long though, so don't expect this to work often.</p>
                <pre><code>&lt;script&gt;alert('hello');&lt;/script&gt;</code></pre>

                <h2>Image tag</h2>
                <p>Perhaps you've got an image tag somewhere on the target page. Fun fact: If an <code>&lt;img&gt;</code> tag has an <code>onerror</code> attribute, the JavaScript code in that attribute will be run if the image fails to load. You can make sure an image <em>always</em> fails to load by giving it an invalid URL.</p>
                <pre><code>&lt;img src="x" onerror="alert('hello')" /&gt;</code></pre>

                <p>Alternatively, you can also set the URL of the image to a JavaScript function, although modern browsers increasingly block this (the version of Chrome I'm using right now doesn't allow this):</p>
                <pre><code>&lt;img src="javascript:alert('hello')" /&gt;</code></pre>

                <h2>Working without quotes</h2>
                <p>Alright, so maybe the web developer is filtering out single and double quotes. How can you write JavaScript without these beloved punctuation marks? Well, there's a couple ways...</p>

                <h3>a) Just don't include them</h3>
                <p>Most browsers can handle quotation marks being omitted in some places, such as HTML attributes. (The Web has been around for over 20 years now; there's tons of obscure legacy weirdness that shouldn't work, but does.)</p>
                <pre><code>&lt;img src=x onerror=alert(1)&gt;</code></pre>

                <h3>b) Make strings by other means</h3>
                <p>You don't need quotation marks to create a string. The <code>String.fromCharCode()</code> method allows you to create strings from the numeric values of each character (i.e. their ASCII codes).</p>
                <pre><code>&lt;script&gt;alert(String.fromCharCode(72, 101, 108, 108, 111, 33))&lt;/script&gt;</code></pre>
                <p>That code makes a pop-up that says "Hello!". You can find a copy of the table of ASCII table (codes for the most basic characters) <a href="https://ascii.cl/">here</a> (N.B. use the decimal values, not the hex values).</p>

                <h3>c) Use other punctuation</h3>
                <p>A lot of browsers will recognize the grave character (<code>`</code>) as a delimiter. This also lets you use single and double quotes inside an HTML attribute:</p>
                <pre><code>&lt;img src=x onerror=`alert("Look, I can use 'single quotes' in here!")`&gt;</code></pre>

                <h3>

            </div>
        </div>
    </main><!-- /.container -->
<?php
printFoot();
