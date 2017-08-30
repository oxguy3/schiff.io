---
title:  "Reverse engineering the Humble Bundle app to get API access"
date:   2017-07-21 14:40:29 -0400
tags: reverse-engineering humble-bundle android
---

If you've been living under a rock for the past few years, you might not know there's this cool site called [Humble Bundle](https://www.humblebundle.com/) that sells games and ebooks in very affordable pay-what-you-want bundles. They donate some of the profits to charity, and they have some really cool offerings -- definitely worth checking out if you've never seen it before.

I've been a patron of Humble Bundle for quite a few years and have built a fairly sizable collection of digital goodies. I'd like to be able to download these in bulk in the formats of my choice. Unfortunately, Humble Bundle doesn't make this easy -- they expect you to manually click "Download" for each and every item in your library.

This is no good -- I want automation! So how can I get access to my library, and all the downloads within it, in a programmatic way? I could scrape the website and dig the links out that way, but I'd really rather not -- scraping is difficult, slow, and liable to stop working whenever they update their site design. What I want is an API.

Humble Bundle doesn't offer an official public API, but they do have [an Android app](https://www.humblebundle.com/app). Surely the app is talking to Humble Bundle's servers using some sort of private, undocumented API -- I just have to figure out how it works. So how can we do that?

*Heads up: This post is going to go fairly in-depth on the technical process of reverse-engineering an Android app. If you don't care about any of that, and just want documentation for Humble Bundle's API, [click here]({{ site.url }}/projects/humble-bundle-api).*

## Intercepting the app's communications

My initial thinking was that I'd figure out how the API works by intercepting the app's network traffic. If I listened from in between the app and Humble Bundle's servers, I could see how the app was making requests, and how the server was answering those requests. This is a tried-and-true method I've used on many web apps and desktop apps.

Unfortunately, it's a bit trickier to accomplish with Android. My phone isn't rooted, and I don't have an Android emulator installed on my laptop (and I'd rather not get one -- my hard drive is very small and low on space). The only way I can intercept the app's traffic is by adding some sketchy third-party app on my phone that emulates a VPN and intercepts the traffic that way. The app I tried for doing this didn't seem to work on first test, and frankly, I felt uneasy about trusting some random app with so much power on my phone, so I deleted it and went back to the drawing board.

_UPDATE: A lot of people have told me about various ways I could have made this approach work without using a sketchy app -- for example, having Android use an HTTP proxy that is running on my computer. I thought about trying this approach, but was worried I might run into gotchas such as certificate pinning or custom HTTP code that ignores my proxy. Decompiling seemed less likely to have such holdups (and, most importantly, it seemed like more fun)._

## Decompiling the app

Well, if there's no easy way to spy on the app as it's running, what if I just took it apart and figured out how it worked from the inside? This method is not the easiest, but for an app as simple as Humble Bundle's, it just might be feasible.

First things first, I need a copy of the app; that is, the APK file. Normally, for an app on the Google Play Store, I'd have to use a site like [this one](https://apps.evozi.com/apk-downloader/) to pull down the APK file. However, the Humble Bundle app isn't actually on the Play Store -- you just download it as an APK [right from their site](https://www.humblebundle.com/app).

Now we need to take the APK file apart. This used to be a bit of a laborious, multi-part process, but nowadays it's quite easy using a utility called [apktool](https://ibotpeaches.github.io/Apktool/). I just had to install apktool and run `apktool d HumbleBundle-2.2.2.apk`, and it does all the heavy lifting.

When apktool completed running, I ended up with a directory that looked like this:

```
HumbleBundle-2.2.2/
    AndroidManifest.xml
    apktool.yml
    assets/
    original/
    res/
    smali/
```

There's a lot of interesting stuff here -- AndroidManifest.xml tells you about the app's permissions and so forth, res/ contains the images used by the app, etc -- but I really want is the app's executable source code. That's in the smali/ folder.

## What is Smali?

Inside the smali/ directory (or, more precisely, inside the directory smali/com/humblebundle/library/) is all the code for this app, written in a format called [Smali](https://github.com/JesusFreke/smali). Odds are, you've probably never heard of Smali, and you might be thinking "I thought Android apps were written in Java."

Well, you'd be right, they are (usually) written in Java! However, APK files don't get published with the original source code -- most developers would probably object to their source code being made so easily readable.

Instead, Android apps ship their code in [Dalvik Executable format](https://source.android.com/devices/tech/dalvik/dex-format), commonly known as .dex format. This is a type of very low-level code that is not meant to be written or read by a human, but instead is generated when a human compiles code that they wrote in a higher-level language (in this case, probably Java). This code contains the raw instructions that will be directly executed by the Java Virtual Machine (JVM).

I don't want to get too far into the weeds here, so if you want to learn more about the JVM, check out [this article](http://www.makeuseof.com/tag/java-virtual-machine-work-makeuseof-explains/). The important thing to know is that .dex is a very low-level language format not intended to be read by humans. In fact, .dex is pretty much impossible for humans to read -- it's a binary format that looks like pure gibberish if you tried to read it directly.

Fortunately, apktool has done us a great favor and disassembled the .dex bytecode into [Smali](https://github.com/JesusFreke/smali), a format that represents the .dex code in a text format. It's still a very obtuse and unfriendly language, but at least we can read it in a text editor.

## Reading Smali code

It's hard to talk about Smali code without actually seeing it, so let's show off some code samples. Here's the classic HelloWorld application, in both Java and Smali formats:

{% highlight java %}
public class HelloWorld {
    public static void main(String[] args) {
        System.out.println("Hello World!");
    }
}
{% endhighlight %}

{% highlight smali %}
.class public LHelloWorld;

.super Ljava/lang/Object;

.method public static main([Ljava/lang/String;)V
    .registers 2

    sget-object v0, Ljava/lang/System;->out:Ljava/io/PrintStream;

    const-string v1, "Hello World!"

    invoke-virtual {v0, v1}, Ljava/io/PrintStream;->println(Ljava/lang/String;)V

    return-void
.end method
{% endhighlight %}

Immediately you'll notice that Smali is a much more verbose format. When we say that machine code is "low-level", what we mean is that the language is not going to do any of the work for you. You can't just declare a variable and have it magically pop into existence. Instead, you have to manually define the [register](https://en.wikipedia.org/wiki/Processor_register) that your data will be stored in, and move data between registers, and so forth.

Here's the Smali version again, but I've added comments to explain each line of code:

{% highlight smali %}
# declare a class called HelloWorld ('L' indicates a class name)
.class public LHelloWorld;

# this class doesn't extend another class, so it implicitly extends java.lang.Object
.super Ljava/lang/Object;

# method which takes a String array (array indicated by '[') and returns
# void (indicated by 'V')
.method public static main([Ljava/lang/String;)V
    # declare that we need 2 registers, which we can then access as v0 and v1
    .registers 2

    # retrieve the System.out object and store a reference to it in v0
    sget-object v0, Ljava/lang/System;->out:Ljava/io/PrintStream;

    # create a String object and store a reference to it in v1
    const-string v1, "Hello World!"

    # on the v0 object (System.out), invoke the 'println' method with the
    # parameter v1 (our "Hello World!" string)
    invoke-virtual {v0, v1}, Ljava/io/PrintStream;->println(Ljava/lang/String;)V

    # end the method
    return-void
.end method
{% endhighlight %}

I'm not going to go super in-depth on how machine code works, but hopefully you can see the basics of what's happening with the comments I've added. You can read more about Smali syntax at [its GitHub page](https://github.com/JesusFreke/smali) (in particular, I definitely recommend checking out the useful links in the README and the files in the 'examples' directory).

## Finding the code we want

Now that we have a basic understanding of Smali, we can start making sense of the Humble Bundle app code. Here's a listing of all the app's code in the Smali directory:

```
a/
    a.smali
    a$1.smali
    b.smali
    c.smali
    d.smali
accounts/
    GenericAccountService.smali
    GenericAccountService$a.smali
a.smali
AboutActivity.smali
AdvancedActivity.smali
AdvancedActivity$1.smali
AdvancedActivity$2.smali
AdvancedActivity$3.smali
AdvancedActivity$4.smali
b.smali
c.smali
CaptchaActivity.smali
CaptchaActivity$a.smali
ClaimingActivity.smali
ClaimingActivity$1.smali
ClaimingActivity$a.smali
ClaimingActivity$b.smali
ClaimingActivity$b$1.smali
ClaimingActivity$b$2.smali
ClaimingActivity$b$3.smali
ClaimingActivity$b$4.smali
ClaimingActivity$c.smali
ClaimingActivity$d.smali
d.smali
d$1.smali
d$2.smali
d$2$1.smali
d$2$2.smali
d$2$3.smali
d$3.smali
d$a.smali
d$a$1.smali
d$b.smali
d$c.smali
d$c$1.smali
d$c$2.smali
DownloadListActivity.smali
DownloadListActivity$1.smali
DownloadListActivity$a.smali
DownloadListFragment.smali
DownloadListFragment$1.smali
DownloadListFragment$1$1.smali
DownloadListFragment$2.smali
DownloadListFragment$3.smali
DummyProvider.smali
e.smali
f.smali
FilterFragment.smali
FilterFragment$1.smali
FilterFragment$a.smali
ForceUpdateActivity.smali
g.smali
h.smali
HBApplication.smali
HBApplication$a.smali
HBBroadcastReceiver.smali
i.smali
i$a.smali
i$b.smali
i$c.smali
j.smali
k.smali
l.smali
LoginActivity.smali
LoginActivity$1.smali
LoginActivity$2.smali
LoginActivity$a.smali
LoginActivity$b.smali
LoginActivity$c.smali
m.smali
n.smali
NoConnectionActivity.smali
NoConnectionActivity$1.smali
NoConnectionActivity$1$1.smali
NoConnectionActivity$2.smali
o.smali
p.smali
p$1.smali
p$a.smali
PostSignupActivity.smali
SettingsActivity.smali
SettingsActivity$1.smali
SettingsActivity$2.smali
SettingsActivity$3.smali
SettingsActivity$4.smali
SettingsActivity$5.smali
SettingsActivity$6.smali
SettingsActivity$7.smali
SettingsActivity$8.smali
SyncService.smali
```

That's a lot of files to go through! What's more, many of them have meaningless single-letter names that tell us nothing about what they do. In fact, poking around inside some of these files, it seems that most of the method and variable names were also changed to single letters. This obfuscation is yet another way that app developers try to make it harder to read their source code. But we can work past this!

Even though most method and class names have been obfuscated, string constants remain untouched throughout the code. We're looking for code that talks to the API, so why don't we try searching for string constants that begin with "http":

[![Searching for '"http' in the code directory][screenshot-search-http-string]][screenshot-search-http-string]

Sweet, we got a bunch of promising looking results. It looks like https://hr-humblebundle.appspot.com is the domain name all the API calls get made to, and it also looks like i.smali is the class that handles most of the API interaction. Progress, heck yeah!

## Make a login request

The first thing we'll probably need to accomplish before we can download files from Humble Bundle is logging in with our account, so let's investigate that "https://hr-humblebundle.appspot.com/processlogin" URL. Here's the full method that it appears in in the first instance:

{% highlight smali linenos %}
.method public a(Ljava/lang/String;Ljava/lang/String;Ljava/lang/String;Ljava/lang/String;Ljava/lang/String;)[Ljava/lang/String;
    .locals 9

    const/4 v8, 0x0

    const/4 v7, 0x2

    const/4 v6, 0x1

    const/4 v1, 0x0

    const-string v0, "https://hr-humblebundle.appspot.com/processlogin"

    new-instance v2, Ljava/util/ArrayList;

    invoke-direct {v2}, Ljava/util/ArrayList;-><init>()V

    new-instance v3, Lorg/apache/http/message/BasicNameValuePair;

    const-string v4, "ajax"

    const-string v5, "true"

    invoke-direct {v3, v4, v5}, Lorg/apache/http/message/BasicNameValuePair;-><init>(Ljava/lang/String;Ljava/lang/String;)V

    invoke-interface {v2, v3}, Ljava/util/List;->add(Ljava/lang/Object;)Z

    new-instance v3, Lorg/apache/http/message/BasicNameValuePair;

    const-string v4, "username"

    invoke-direct {v3, v4, p1}, Lorg/apache/http/message/BasicNameValuePair;-><init>(Ljava/lang/String;Ljava/lang/String;)V

    invoke-interface {v2, v3}, Ljava/util/List;->add(Ljava/lang/Object;)Z

    new-instance v3, Lorg/apache/http/message/BasicNameValuePair;

    const-string v4, "password"

    invoke-direct {v3, v4, p2}, Lorg/apache/http/message/BasicNameValuePair;-><init>(Ljava/lang/String;Ljava/lang/String;)V

    invoke-interface {v2, v3}, Ljava/util/List;->add(Ljava/lang/Object;)Z

    new-instance v3, Lorg/apache/http/message/BasicNameValuePair;

    const-string v4, "authy-token"

    invoke-direct {v3, v4, p5}, Lorg/apache/http/message/BasicNameValuePair;-><init>(Ljava/lang/String;Ljava/lang/String;)V

    invoke-interface {v2, v3}, Ljava/util/List;->add(Ljava/lang/Object;)Z

    new-instance v3, Lorg/apache/http/message/BasicNameValuePair;

    const-string v4, "recaptcha_challenge_field"

    invoke-direct {v3, v4, p3}, Lorg/apache/http/message/BasicNameValuePair;-><init>(Ljava/lang/String;Ljava/lang/String;)V

    invoke-interface {v2, v3}, Ljava/util/List;->add(Ljava/lang/Object;)Z

    new-instance v3, Lorg/apache/http/message/BasicNameValuePair;

    const-string v4, "recaptcha_response_field"

    invoke-direct {v3, v4, p4}, Lorg/apache/http/message/BasicNameValuePair;-><init>(Ljava/lang/String;Ljava/lang/String;)V

    invoke-interface {v2, v3}, Ljava/util/List;->add(Ljava/lang/Object;)Z

    invoke-static {v0, v2}, Lcom/humblebundle/library/a/a;->b(Ljava/lang/String;Ljava/util/List;)Lorg/apache/http/HttpResponse;

    move-result-object v0

    new-instance v2, Lorg/json/JSONObject;

    invoke-static {v0}, Lcom/humblebundle/library/a/a;->a(Lorg/apache/http/HttpResponse;)Ljava/lang/String;

    move-result-object v3

    invoke-direct {v2, v3}, Lorg/json/JSONObject;-><init>(Ljava/lang/String;)V

    invoke-direct {p0, v2}, Lcom/humblebundle/library/i;->a(Lorg/json/JSONObject;)Ljava/lang/String;

    move-result-object v2

    const-string v3, ""

    invoke-virtual {v2, v3}, Ljava/lang/String;->equals(Ljava/lang/Object;)Z

    move-result v3

    if-nez v3, :cond_0

    new-array v0, v7, [Ljava/lang/String;

    aput-object v2, v0, v1

    aput-object v8, v0, v6

    :goto_0
    return-object v0

    :cond_0
    const-string v2, "set-cookie"

    invoke-interface {v0, v2}, Lorg/apache/http/HttpResponse;->getHeaders(Ljava/lang/String;)[Lorg/apache/http/Header;

    move-result-object v2

    array-length v3, v2

    move v0, v1

    :goto_1
    if-ge v0, v3, :cond_2

    aget-object v4, v2, v0

    invoke-interface {v4}, Lorg/apache/http/Header;->getValue()Ljava/lang/String;

    move-result-object v4

    const-string v5, "\_simpleauth_sess="

    invoke-virtual {v4, v5}, Ljava/lang/String;->contains(Ljava/lang/CharSequence;)Z

    move-result v5

    if-eqz v5, :cond_1

    new-instance v0, Lcom/humblebundle/library/o;

    iget-object v2, p0, Lcom/humblebundle/library/i;->a:Landroid/content/Context;

    invoke-direct {v0, v2}, Lcom/humblebundle/library/o;-><init>(Landroid/content/Context;)V

    invoke-virtual {v0, v4}, Lcom/humblebundle/library/o;->a(Ljava/lang/String;)V

    invoke-virtual {v0, p1}, Lcom/humblebundle/library/o;->b(Ljava/lang/String;)V

    new-array v0, v7, [Ljava/lang/String;

    const-string v2, ""

    aput-object v2, v0, v1

    aput-object v4, v0, v6

    goto :goto_0

    :cond_1
    add-int/lit8 v0, v0, 0x1

    goto :goto_1

    :cond_2
    new-array v0, v7, [Ljava/lang/String;

    const-string v2, "network"

    aput-object v2, v0, v1

    aput-object v8, v0, v6

    goto :goto_0
.end method
{% endhighlight %}

That's a bit daunting to look at, but we can break it down. This is a method that accepts five strings as parameters (which will be stored in p1, p2, p3, p4, p5 -- we skip p0 because p0 is automatically set to `this`) and returns a string. A lot of the bulk in this function is pretty straightforward -- it creates an ArrayList in v2 (line 14), then creates a bunch of BasicNameValuePair objects to put in the ArrayList. This seems to be building the list of arguments that will be passed to the API server -- if we look up [the BasicNameValuePair class](https://hc.apache.org/httpcomponents-core-ga/httpcore/apidocs/org/apache/http/message/BasicNameValuePair.html), we can see that's precisely what it's intended for.

After it builds the name-value pair list, it passes that ArrayList as and the 'processlogin' URL to a method called 'b' in the class 'com/humblebundle/library/a/a' (this is on line 68). Then it takes the HttpResponse returned by the 'b' method, and passes it to another method in the same class called 'a' to get back a string. It then creates a JSONObject from that string. The rest of the code doesn't seem too important, but skimming it, we can see that it handles the cookies and then does something with a Context (which probably means something is changing in the app's UI, like going to a new View or something).

We could dig into the com/humblebundle/library/a/a.smali file and figure out what those methods do, but it's also not hard to just guess. We passed in a URL and a list of name-value pairs, and we got back some JSON, so those methods probably execute the HTTP request to the server. At this point, we can guess that a login request probably looks something like this:

```
POST /processlogin HTTP/1.1
Host: hr-humblebundle.appspot.com

ajax=true
&username=???
&password=???
&authy-token=???
&recaptcha_challenge_field=???
&recaptcha_response_field=???
```

We've got a few blanks to fill in, but some of them are obvious. Username is your email address, password is your password, and authy-token is the two-factor authentication code (either from the Authy app or from an SMS). But it looks like the server is also expecting us to solve a captcha.

## Solving the captcha

We need to solve a captcha apparently, so obviously there must be code somewhere else in the app that downloads a captcha for the user to solve. Let's go back to that search for strings starting with "http" that we did a while ago:

[![Searching for '"http' in the code directory][screenshot-search-http-string]][screenshot-search-http-string]

One of the other results there is https://hr-humblebundle.appspot.com/user/captcha. It turns out, that URL is actually a web page that shows you a captcha:

[![Humble Bundle captcha][screenshot-captcha-page]][screenshot-captcha-page]

If you load the page yourself, solve the captcha, and click the Submit button, you'll find that nothing actually happens for some reason. Looking in the source code of the web page, we find this bit of JavaScript that seems to control what happens when that Submit button is clicked:

{% highlight javascript %}
var captcha = new Recaptcha2('captcha-holder');
$('input[type=submit]').click(function(e){
  e.preventDefault();
  // recaptcha v2 only cares about response, but we can let the Android app interface stay the same
  var challenge = '';
  var response = captcha.get_response();
  var android_defined = false;
  if (typeof Android != 'undefined') {
    Android.setCaptchaResponse(challenge, response);
  }
})
{% endhighlight %}

After the user clicks the Submit button, it looks for an object called `Android` and tries to pass the reCAPTCHA challenge and response to a `setCaptchaResponse` method. However, the Android object does not exist on our machine, so this code ends up doing essentially nothing. It's likely that, when this web page is rendered inside the Humble Bundle app, it injects an object called `Android` into the page -- since we're using our web browser instead of the app, the object never gets created.

_(As a matter of fact, you can find the code that does this in CaptchaActivity.smali. The onCreate method in that class makes a WebView, then sets an instance of the class 'CaptchaActivity$a' as the JavaScript interface for that WebView. Thus, the real setCaptchaResponse method can be found in CaptchaActivity$a.smali)_

The easiest way to solve this is by creating our own version of the `Android.setCaptchaResponse` method. I opened the JavaScript console on the web page and typed in this:

{% highlight javascript %}
window.Android = {
    setCaptchaResponse: function(challenge, response) {
        console.log(response);
    }
}
{% endhighlight %}

This simply prints out the reCAPTCHA response in the console (I didn't bother printing the challenge because, as you can see in the original JavaScript, it's always an empty string). Now, if we solve the captcha and click Submit, we see this in the console:

[![Captcha response in JavaScript console][screenshot-captcha-logged]][screenshot-captcha-logged]

That big ugly string is our recaptcha_response_field, which is just what we needed for the /processlogin request.

## Making the login request

Now we seem to have all the parts, so we can try making a login request.

```
POST /processlogin HTTP/1.1
Host: hr-humblebundle.appspot.com

ajax=true
&username=johndoe@example.com
&password=hunter21
&authy-token=1234567
&recaptcha_challenge_field=
&recaptcha_response_field=BigLongRandomStringFromTheCaptchaPage
```

And then the server will respond with this:

```
{"errors": {"_all": ["Invalid request."]}, "success": false}
```

That's no good, where did we go wrong?

Well, I'm gonna save you the debugging and cut to the chase. Remember back in the "[Make a login request](#make-a-login-request)" section, when I skipped over reading the 'com/humblebundle/library/a/a.smali' file, and just took an educated guess about what it does? Well, it turns out that 'a/a.smali' adds a header `X-Requested-By: hb_android_app` to all POST requests it sends.

Once we add that header to our request, the server will return this:

```
{"goto": "/home", "success": true}
```

We're in! It also sets some session cookies, which we can just include on future requests to remain logged in.

## More API endpoints

Now that we've logged in, we need to figure out how to get our list of games. Finding the URLs for doing this was easy with the methods I've already described, and frankly this blog post is getting to be obscenely long, so I'm not going to continue the in-depth walkthrough of my process.

To get the list of everything in our library, you make a GET request to https://hr-humblebundle.appspot.com/api/v1/user/order (while logged in and including the X-Requested-By header), and it returns something like this:

```
[
    {
        "gamekey": "SomeRandomString"
    },
    {
        "gamekey": "AnotherRandomString"
    },
    ...
]
```

To get the details for any particular gamekey, make a GET request to https://hr-humblebundle.appspot.com/api/v1/order/YOUR_GAMEKEY_HERE. You'll get back a response with a ton of info, like this one:

```
{
    "amount_spent": 2.9900000000000002,
    "product": {
        "category": "storefront",
        "machine_name": "siryouarebeinghunted_storefront",
        "post_purchase_text": "",
        "supports_canonical": false,
        "human_name": "Sir, You Are Being Hunted",
        "automated_empty_tpkds": {},
        "partial_gift_enabled": false
    },
    "gamekey": "REDACTED",
    "uid": "REDACTED",
    "created": "2015-09-12T17:56:51.284820",
    "subproducts": [
        {
            "machine_name": "siryouarebeinghunted",
            "url": "http://www.big-robot.com/",
            "downloads": [
                {
                    "machine_name": "siryouarebeinghunted_windows",
                    "platform": "windows",
                    "download_struct": [
                        {
                            "human_size": "499.5 MB",
                            "name": "64-bit",
                            "url": {
                                "web": "https://dl.humble.com/Sir_You_Are_Being_Hunted_win64.zip?gamekey=REDACTED&ttl=REDACTED&t=REDACTED",
                                "bittorrent": "https://dl.humble.com/torrents/Sir_You_Are_Being_Hunted_win64.zip.torrent?gamekey=REDACTED&ttl=REDACTED&t=REDACTED"
                            },
                            "timestamp": 1445303789,
                            "file_size": 523717776,
                            "small": 0,
                            "md5": "a4ddbc59835a6f34f740766158fafce8"
                        },
                        {
                            "sha1": "f82792bf79672b986d82e1da4e2f9029f39ff06b",
                            "name": "32-bit",
                            "url": {
                                "web": "https://dl.humble.com/Sir_You_Are_Being_Hunted_win32.zip?gamekey=REDACTED&ttl=REDACTED&t=REDACTED",
                                "bittorrent": "https://dl.humble.com/torrents/Sir_You_Are_Being_Hunted_win32.zip.torrent?gamekey=REDACTED&ttl=REDACTED&t=REDACTED"
                            },
                            "timestamp": 1445303788,
                            "human_size": "495.7 MB",
                            "file_size": 519799641,
                            "small": 0,
                            "md5": "a9951d52d7b2787e39e446cd8544c0ab"
                        }
                    ],
                    "options_dict": {},
                    "download_identifier": "",
                    "android_app_only": false,
                    "download_version_number": null
                },
                {
                    "machine_name": "siryouarebeinghunted_mac",
                    "platform": "mac",
                    "download_struct": [
                        {
                            "sha1": "8e4712e37402249a969e9e9d0ffa1d42decde8ba",
                            "name": "Download",
                            "url": {
                                "web": "https://dl.humble.com/Sir_You_Are_Being_Hunted_osx.zip?gamekey=REDACTED&ttl=REDACTED&t=REDACTED",
                                "bittorrent": "https://dl.humble.com/torrents/Sir_You_Are_Being_Hunted_osx.zip.torrent?gamekey=REDACTED&ttl=REDACTED&t=REDACTED"
                            },
                            "timestamp": 1445303758,
                            "human_size": "499.2 MB",
                            "file_size": 523448909,
                            "small": 0,
                            "md5": "f23d62bf09cb90a2d88b6ebe9a85e5f3"
                        }
                    ],
                    "options_dict": {},
                    "download_identifier": "",
                    "android_app_only": false,
                    "download_version_number": null
                },
                {
                    "machine_name": "siryouarebeinghunted_linux",
                    "platform": "linux",
                    "download_struct": [
                        {
                            "sha1": "91eb71e65efd3f6e4cbe227481d0d035fc70a182",
                            "name": "Download",
                            "url": {
                                "web": "https://dl.humble.com/Sir_You_Are_Being_Hunted_linux.zip?gamekey=REDACTED&ttl=REDACTED&t=REDACTED",
                                "bittorrent": "https://dl.humble.com/torrents/Sir_You_Are_Being_Hunted_linux.zip.torrent?gamekey=REDACTED&ttl=REDACTED&t=REDACTED"
                            },
                            "timestamp": 1445303753,
                            "human_size": "500.9 MB",
                            "file_size": 525247206,
                            "small": 0,
                            "md5": "5c824f20dc7e54f1f93e1188af3d0b1e"
                        }
                    ],
                    "options_dict": {},
                    "download_identifier": "",
                    "android_app_only": false,
                    "download_version_number": null
                }
            ],
            "custom_download_page_box_html": "",
            "payee": {
                "human_name": "Big Robot",
                "machine_name": "bigrobot"
            },
            "human_name": "Sir, You Are Being Hunted",
            "library_family_name": "",
            "icon": "https://humblebundle-a.akamaihd.net/misc/files/hashed/89cd9b0090a963aa87c3c61b6667548ff8a7ccc6.png"
        }
    ],
    "currency": "USD",
    "is_giftee": false,
    "claimed": true,
    "total": 2.9900000000000002,
    "path_ids": [
        "REDACTED",
        "REDACTED"
    ]
}
```

I also found the URL https://hr-humblebundle.appspot.com/androidapp/v2/service_check to be mildly interesting -- it returns a simple array of all game bundles that are currently available. It doesn't require authentication or the X-Requested-By header to access. Here's what its response looks like right now, for sake of example:

```
[
    {
        "url": "https://www.humblebundle.com/telltale-bundle-2017",
        "bundle_name": "Humble Telltale Bundle",
        "bundle_machine_name": "telltale2_bundle"
    },
    {
        "url": "https://www.humblebundle.com/capcom-rising-bundle",
        "bundle_name": "Humble Capcom Rising Bundle",
        "bundle_machine_name": "capcomrising_bundle"
    },
    {
        "url": "https://www.humblebundle.com/mobile/kemco-hyperdevbox-mobile-bundle",
        "bundle_name": "Humble Mobile Bundle: KEMCO X HyperDevbox",
        "bundle_machine_name": "kemcoxhyperdevbox_mobilebundle"
    }
]
```

There also appear to be a few other API endpoints in the app, related to signing up for a new account, claiming a bundle, finding unclaimed orders, and checking for app updates, but none of those seemed interesting enough to investigate. Feel free to copy my methods and figure them out yourself.

[I've written documentation for all the API endpoints over here]({{ site.url }}/projects/humble-bundle-api), including a collection for Postman if you just want to test it out.

Ultimately, I want to use this API to make some sort of cool automated download tool, but that'll have to wait for another day. Let me know if you make anything cool with this API!

[screenshot-search-http-string]: {{ site.url }}/assets/humble-bundle-api/screenshot-search-http-string.png
[screenshot-captcha-page]: {{ site.url }}/assets/humble-bundle-api/screenshot-captcha-page.png
[screenshot-captcha-logged]: {{ site.url }}/assets/humble-bundle-api/screenshot-captcha-logged.png

[[discuss on /r/ReverseEngineering](https://www.reddit.com/r/ReverseEngineering/comments/6vk459/reverse_engineering_the_humble_bundle_android_app/)]
