---
title:  "Defcon preparation checklist"
date:   2017-07-24 12:39:53 -0400
tags: defcon security
---
I'm heading to my first Defcon on Wednesday, and naturally, I'm a little bit anxious about prepping my devices so as to not get hacked/pwned/intercepted/etc. The advice I've seen for this varies wildly -- some people go all out and use a separate phone and laptop for the convention, while others simply turn off wi-fi and Bluetooth for the con.

I'm thinking the best strategy for me is somewhere in the middle -- make sure I'm locked down and have backups, but don't put myself through hell when the odds of anything bad happening are realistically low. I'm not a worthwhile target; anyone who would waste 0days on the likes of me is probably not smart enough to have found a 0day in the first place.

So, I'll be bringing my normal work laptop (MacBook Pro), my normal cell phone (HTC 10), and even my tablet (Asus ZenPad 3S 10 -- probably won't be using this much besides on my flight though). Without further ado, here is the checklist I'll be following to prepare for Defcon:

## Pre-con
* Patch, patch, patch!
    * OS X system updates
    * `brew update; brew upgrade`
    * app updates
    * Android updates (if available)
* Backup everything of value.
* Shut down local web/database servers.
* Port scan myself with nmap -- make sure I'm not running anything else.
* Enable 1Password [Travel Mode](https://support.1password.com/travel-mode/).
* Withdraw enough cash that I never have to use an ATM in Vegas.
* Remove RFID cards from my wallet (namely my office badge).

## During con
* Keep all unnecessary radios off (Wi-fi, Bluetooth, NFC, GPS).
    * Due to [BroadPwn](http://www.techrepublic.com/article/android-security-bulletin-july-2017-what-you-need-to-know/), I'm not gonna be using wi-fi at all on my Android devices (neither of them is up to the July update yet).
* In the con, only use direct-to-internet wi-fi. In the hotel, only use wired.
* Always use VPN, and turn it on _before_ connecting to wi-fi.
* Do not let any of my devices leave my vision.
* Don't trust any device anyone gives me.
    * The last two are obvious, but I'm particularly keen on mentioning now that [PoisonTap](https://samy.pl/poisontap/) is a thing.
* Use [USB condom](http://syncstop.com/) if using public charging station.
* Only communicate using [Signal](https://play.google.com/store/apps/details?id=org.thoughtcrime.securesms) if possible.

So that's my list. It might not be perfect, but I think it should be sufficient for me. What do you think? Am I too carefree, or even too paranoid?
