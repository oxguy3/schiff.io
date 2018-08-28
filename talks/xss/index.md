---
title: "XSS presentation"
---

**Cross-site scripting: What it is and how we got here**

[Watch the presentation on YouTube.](https://youtu.be/0N2djP0pkjw?t=31m40s)

[View the slides.](https://docs.google.com/presentation/d/1qqFnLR2FjigGJbtTLUssP2DiVLTQh-pGawPf2bJ6lwI/edit?usp=sharing)

Links:
* History of the web
  * [History of the WWW on Wikipedia](https://en.wikipedia.org/wiki/History_of_the_World_Wide_Web)
  * [The first website](http://info.cern.ch/hypertext/WWW/TheProject.html)
* [Demo: CSS and JavaScript](https://unsafe.schiff.io/xss-presentation-demos/css-and-js.php)
* [Demo: Vulnerable comment page](https://unsafe.schiff.io/xss-presentation-demos/comment-page.php)
* The MySpace worm (samy is my hero)
  * [Samy's own write-up](https://samy.pl/popular/)
  * [Samy's technical explanation](https://samy.pl/popular/tech.html)
  * [*Vice* article (2015)](https://motherboard.vice.com/en_us/article/wnjwb4/the-myspace-worm-that-changed-the-internet-forever)
* Self-retweeting tweet
  * [The tweet](https://twitter.com/dergeruhn/status/476764918763749376)
  * [ZDNet article](http://www.zdnet.com/article/tweetdeck-wasnt-actually-hacked-and-everyone-was-silly/)
  * [Tom Scott's explanatory video](https://www.youtube.com/watch?v=zv0kZKC6GAM) (really good!)
* More sites hit by XSS attacks
  * [Tons of banks](https://www.htbridge.com/news/hacking_banking_websites_myth_or_reality.html)
  * [American Express](http://www.theregister.co.uk/2008/12/20/american_express_website_bug_redux/)
  * [BarackObama.com](http://www.zdnet.com/article/obama-site-hacked-redirected-to-hillary-clinton/)
  * [CIA](https://thehackernews.com/2011/06/xss-attack-on-cia-central-itelligence.html)
  * [eBay](https://nakedsecurity.sophos.com/2016/01/13/ebay-xss-bug-left-users-vulnerable-to-almost-undetectable-phishing-attacks/)
  * [Equifax](https://www.forbes.com/sites/thomasbrewster/2017/09/08/equifax-data-breach-history/)
  * [Facebook](http://theharmonyguy.com/oldsite/2011/04/21/recent-facebook-xss-attacks-show-increasing-sophistication/)
  * [FBI](http://www.xssed.com/mirror/81181/)
  * [HMIC (Her Majesty's Inspectorate of Constabulary)](https://shkspr.mobi/blog/2014/09/another-gov-uk-xss-flaw/)
  * [Jira (Atlassian)](https://www.netsparker.com/blog/web-security/apacheorg-and-jira-incident/)
  * [Justin.tv](http://www.zdnet.com/article/xss-worm-at-justin-tv-infects-2525-profiles/)
  * [Hotmail](http://seclists.org/bugtraq/2002/Oct/119)
  * [Kaspersky](http://www.theregister.co.uk/2009/02/08/kaspersky_compromise_report/)
  * [Magento](https://www.msspalert.com/cybersecurity-news/magento-xss-attacks-helpdesk-widget-used-to-target-e-commerce-shops/)
  * [McAfee](https://www.cnet.com/news/mcafee-blasted-for-having-holes-in-its-web-sites/)
  * [Orkut (by Google)](https://techcrunch.com/2010/09/25/born-sabado/)
  * [PayPal](https://www.v3.co.uk/v3-uk/news/2424706/paypal-xss-vulnerability-exposed-by-bitdefender)
  * [Steam](https://blog.horangi.com/real-life-examples-of-web-vulnerabilities-a63bd22d838a)
  * [StrongWebmail](http://www.zdnet.com/article/strongwebmail-ceos-mail-account-hacked-via-xss/)
  * [Symantec](http://www.theregister.co.uk/2009/04/15/symantec_xss_bugs/)
  * [Tesco](https://www.troyhunt.com/why-xss-is-serious-business-and-why/)
  * [Twitter](https://blog.twitter.com/official/en_us/a/2010/all-about-the-onmouseover-incident.html)
  * [WordPress](http://blog.trendmicro.com/trendlabs-security-intelligence/wordpress-vulnerability-puts-millions-of-sites-at-risk-trend-micro-solutions-available/)
  * [Yahoo!](http://www.hotforsecurity.com/blog/yahoo-accounts-hijacked-via-xss-type-attack-5172.html)
* [Interactive demo](https://unsafe.schiff.io/xss-presentation-demos/widgets-register.php)
  * [OWASP XSS Filter Evasion Cheat Sheet](https://www.owasp.org/index.php/XSS_Filter_Evasion_Cheat_Sheet)
* Preventing XSS
  * [OWASP XSS Prevention Cheat Sheet](https://www.owasp.org/index.php/XSS_%28Cross_Site_Scripting	%29_Prevention_Cheat_Sheet)
  * [There's more to HTML escaping than &, <, >, and "](http://wonko.com/post/html-escaping)
* Further reading
  * [XSS on Wikipedia](https://en.wikipedia.org/wiki/Cross-site_scripting) (general audience)
  * [XSS on OWASP](https://www.owasp.org/index.php/Cross-site_Scripting_(XSS)) (more technical)
  * [Troy Hunt's blog](https://www.troyhunt.com/)

These slides and demos are licensed under a [Creative Commons Attribution 4.0 International License](http://creativecommons.org/licenses/by/4.0/).
