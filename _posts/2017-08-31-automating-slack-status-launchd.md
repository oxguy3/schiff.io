---
title: "Automating my Slack status based on my wi-fi location"
date: 2017-08-31 16:08:23 -0400
tags: slack mac
---
I alternate between working at home and in the office a lot, and I like to keep my coworkers informed about where I am. Earlier this year, [Slack added statuses](https://slackhq.com/set-your-status-in-slack-28a793914b98), which allows you to show an emoji next to your name to indicate what you're up to.

I immediately took to this feature and always tried to set "Working remotely" or "In the office" each morning, but I'm very forgetful, and would often notice at the end of the day that I had been set to the wrong location for the entire day. My immediate thought when I'm getting fed up with a tedious daily task is, "can I automate this?"

The answer is **yes!**, and it turned out to be easier than I expected. Using Mac OS X's launchd, I was able to fully automate my Slack status by having my laptop send a request to Slack's API based on which wi-fi network I connected to. Here's how:

_Heads up: This blog post describes the process of building this script. If you don't care about that and just want the script to copy for yourself, skip down to "[The final script](#the-final-script)"._

## What is launchd?

launchd is Mac OS X's system for managing recurring/long-running programs ("daemons", in fancier lingo). It can automatically start programs based on certain conditions and keep them running and provide them network sockets and so forth. It's very nifty; I definitely recommend reading more about it on [launchd.info](http://www.launchd.info/) or [Apple's developer docs](https://developer.apple.com/library/content/documentation/MacOSX/Conceptual/BPSystemStartup/Chapters/CreatingLaunchdJobs.html).

One of the conditions it can monitor is if a file changes. You can pick one or a few files for it to monitor, and whenever those files get modified, launchd will instantly start your program. We want our program to run whenever our wi-fi network changes, so are there any files that get updated when that happens? Yes there are; these three:

* `/etc/resolv.conf` (contains the DNS servers your computer is currently using)
* `/Library/Preferences/SystemConfiguration/NetworkInterfaces.plist` (contains settings for all your network interface devices)
* `/Library/Preferences/SystemConfiguration/com.apple.airport.preferences.plist` (contains settings and preferences for wi-fi networks)

I've listed what each of those files contains, but really, we don't care what's in them; all that matters is that at least one of them will get modified when our network connection changes.

To add a new daemon to launchd, we put an XML file in the folder `~/Library/LaunchAgents/`; here's what our XML file (which I've named "local.slackstatus.plist") looks like:

{% highlight xml %}
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple Computer//DTD PLIST 1.0//EN"  "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
  <key>Label</key>
  <string>local.slackstatus</string>

  <key>LowPriorityIO</key>
  <true/>

  <key>Program</key>
  <string>/Users/hayden/Library/Application Support/slackstatus.sh</string>

  <key>WatchPaths</key>
  <array>
    <string>/etc/resolv.conf</string>
    <string>/Library/Preferences/SystemConfiguration/NetworkInterfaces.plist</string>
    <string>/Library/Preferences/SystemConfiguration/com.apple.airport.preferences.plist</string>
  </array>

  <key>RunAtLoad</key>
  <true/>
</dict>
</plist>
{% endhighlight %}

I haven't gone in depth on how launchd's config files work (again, check out [launchd.info](http://www.launchd.info/) if you're interested in learning), but hopefully you can see the basics of what's going on here. When any of those three files is modified, the script at `/Users/hayden/Library/Application Support/slackstatus.sh` will be run. Now we just need to write that script.

## Writing the Bash script

I chose to write the script in Bash because it really doesn't need to do much -- we just need to figure out which wi-fi network we're connected to (that is, what the SSID is), and send the appropriate update to the Slack API. So, first things first, how do we look up the SSID?

Mac OS X actually ships with a secret little executable called `airport` (located at `/System/Library/PrivateFrameworks/Apple80211.framework/Versions/Current/Resources/airport`) that lets you look up information about the wi-fi adapter. In particular, we can pass it the flag `-I` to get a bit of info that looks like this:

```
$ /System/Library/PrivateFrameworks/Apple80211.framework/Versions/Current/Resources/airport -I
     agrCtlRSSI: -48
     agrExtRSSI: 0
    agrCtlNoise: -92
    agrExtNoise: 0
          state: running
        op mode: station
     lastTxRate: 217
        maxRate: 217
lastAssocStatus: 0
    802.11 auth: open
      link auth: wpa2-psk
          BSSID: ab:cd:ef:12:34:56
           SSID: MyHomeWifi
            MCS: 23
        channel: 6
```
That output includes the SSID of our current network! With a little bit of `awk` magic, we can extract just the SSID from that output. Now we've got the start of our `slackstatus.sh` script; here's what it looks like:

{% highlight bash %}
#!/bin/bash
ssid=`/System/Library/PrivateFrameworks/Apple80211.framework/Versions/Current/Resources/airport -I | awk '/ SSID/ {print substr($0, index($0, $2))}'`
if [ "$ssid" == "MyWorkplaceWifi" ]; then
    # set status to "In the office"
elif [ "$ssid" == "MyHomeWifi" ] || [ "$ssid" == "MyOtherHomeWifi" ]; then
    # set status to "Working remotely"
fi
{% endhighlight %}

Next up, we need to fill in those comments with code that actually connects to the Slack API.

## Updating our status with the Slack API

Slack's API is very robust and very well-documented, so interacting with it is pretty easy. A quick google search revealed [this page](https://api.slack.com/docs/presence-and-status) which describes how to update a user's status with the API. Essentially, you just put the emoji and description for your new status in a JSON object, and POST that object to a certain endpoint. Here's an example of the JSON object:

{% highlight json %}
{
    "status_text": "riding a train",
    "status_emoji": ":mountain_railway:"
}
{% endhighlight %}

And here's what the HTTP request basically looks like (with the JSON object URL-encoded):

```
POST https://slack.com/api/users.profile.set

token=super_secret_token
&profile=%7B%22status_text%22%3A%22riding%20a%20train%22%2C%22status_emoji%22%3A%22%3Amountain_railway%3A%22%7D
```

And since we're doing this from a Bash script, we'll be using curl to make the request, which will look like this:

```
/usr/bin/curl https://slack.com/api/users.profile.set --data 'token=super_secret_token&profile=%7B%22status_text%22%3A%22riding%20a%20train%22%2C%22status_emoji%22%3A%22%3Amountain_railway%3A%22%7D'
```

The only part missing now is the `super_secret_token`. To get that, we first need to create an app at [api.slack.com](https://api.slack.com/). Then we go to the "OAuth & Permissions" page, and add the "users.profile:write" permission, which is necessary for updating the status. Lastly, we install it on our team, so that it has access to perform actions on my account as me. The OAuth access token that Slack gives us after completing that last step is what we use in our API call.

## The final script

At the end of this mini-project, I ended up with two files -- the launchd config and the bash script. Here is each of them:

**/Users/hayden/Library/LaunchAgents/local.slackstatus.plist**

{% highlight xml %}
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple Computer//DTD PLIST 1.0//EN"  "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
  <key>Label</key>
  <string>local.slackstatus</string>

  <key>LowPriorityIO</key>
  <true/>

  <key>Program</key>
  <string>/Users/hayden/Library/Application Support/slackstatus.sh</string>

  <key>WatchPaths</key>
  <array>
    <string>/etc/resolv.conf</string>
    <string>/Library/Preferences/SystemConfiguration/NetworkInterfaces.plist</string>
    <string>/Library/Preferences/SystemConfiguration/com.apple.airport.preferences.plist</string>
  </array>

  <key>RunAtLoad</key>
  <true/>
</dict>
</plist>
{% endhighlight %}

**/Users/hayden/Library/Application Support/slackstatus.sh**

{% highlight bash %}
#!/bin/bash
slack_token="super_secret_token" # obtained from api.slack.com
ssid=`/System/Library/PrivateFrameworks/Apple80211.framework/Versions/Current/Resources/airport -I | awk '/ SSID/ {print substr($0, index($0, $2))}'`
if [ "$ssid" == "MyWorkplaceWifi" ]; then
    /usr/bin/curl https://slack.com/api/users.profile.set --data 'token='$slack_token'&profile=%7B%22status_text%22%3A%20%22In%20the%20office%22%2C%22status_emoji%22%3A%20%22%3Aoffice%3A%22%7D' > /dev/null
elif [ "$ssid" == "MyHomeWifi" ] || [ "$ssid" == "MyOtherHomeWifi" ]; then
    /usr/bin/curl https://slack.com/api/users.profile.set --data 'token='$slack_token'&profile=%7B%22status_text%22%3A%20%22Working%20remotely%22%2C%22status_emoji%22%3A%20%22%3Ahouse_with_garden%3A%22%7D' > /dev/null
elif [ "$ssid" == "attwifi" ] || [ "$ssid" == "Google Starbucks" ]; then
    /usr/bin/curl https://slack.com/api/users.profile.set --data 'token='$slack_token'&profile=%7B%22status_text%22%3A%20%22At%20the%20coffee%20shop%22%2C%22status_emoji%22%3A%20%22%3Acoffee%3A%22%7D' > /dev/null
elif [ -n "$ssid" ]; then
    /usr/bin/curl https://slack.com/api/users.profile.set --data 'token='$slack_token'&profile=%7B%22status_text%22%3A%20%22Somewhere%20unknown...%22%2C%22status_emoji%22%3A%20%22%3Ainterrobang%3A%22%7D' > /dev/null
fi
{% endhighlight %}

_(I added additional statuses to be set when I'm connected to Starbucks wi-fi or when I'm connected to an unknown wi-fi network -- you can customize it with whatever wi-fi networks you regularly connect to!)_

With those two files created, we just need to load the launchd config file to get it going. The command for that is:

```
launchd load ~/Library/LaunchAgents/local.slackstatus.plist
```

And that's it; our Slack status is now automatically updated whenever we change wi-fi networks, with no risk of forgetting! launchd is really great for these types of minute automations; I also use it to automatically login to the captive portal on my workplace's wi-fi.

Hope you found this interesting! Let me know if you've done any other cool things with launchd.

[[discuss on /r/programming](https://www.reddit.com/r/programming/comments/6x9fx0/automating_my_slack_status_with_launchd/)]  
[[discuss on Hacker News](https://news.ycombinator.com/item?id=15143489)]
