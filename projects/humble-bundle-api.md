---
title: Humble Bundle API documentation
permalink: /projects/humble-bundle-api
---

Here's a high-level summary of all the API endpoints I know about for Humble
Bundle's API, used by their Android app. You can read more about this API (and
about how I found these endpoints) in
[my blog post]({{ site.url }}/blog/2017/07/21/reverse-engineering-humble-bundle-api.html).
All of these requests should be made to https://hr-humblebundle.appspot.com. You
also need to include the header `X-Requested-By: hb_android_app` with every
request.

## Getting a captcha
To login or request an SMS code (via the /processlogin endpoint), you need to
first solve a captcha. To do this, first visit
[this page](https://hr-humblebundle.appspot.com/user/captcha) in a web browser.
Open the JavaScript console in your browser, and paste in the following code:
```
window.Android = {
    setCaptchaResponse: function(challenge, response) {
        console.log(response);
    }
}
```
Now, solve the captcha and click Submit. When you do, a big long string will
appear in the JavaScript console like this:

![Humble Bundle captcha]({{ site.url }}/assets/humble-bundle-api/screenshot-captcha-logged.png)

That string is your 'recaptcha_response_field' to use in your API calls.

## API methods

Here is each endpoint, broken down to details. If you use the
[Postman](https://www.getpostman.com/) app, I've already built a collection with
all of these endpoints that you can import. You can download it
[here]({{ site.url }}/assets/humble-bundle-api/humble-bundle.postman_collection.json).
You'll need to set the following variables in your Postman environment:
username, password, authy_token, recaptcha_response
(read [this](https://www.getpostman.com/docs/postman/environments_and_globals/manage_environments)
if you don't know what Postman environments are).

### Request an SMS 2FA code be sent
Method: POST  
Must be logged in: no  
URL: https://hr-humblebundle.appspot.com/processlogin

Parameters (x-www-form-urlencoded):

| Key | Value | Notes |
| --- | ----- | ----- |
| ajax | true | |
| username | [your email address] | |
| password | [your password] | |
| recaptcha_challenge_field | | Leave it blank. |
| recaptcha_response_field | [recaptcha code] | See 'Getting a captcha' section above. |
| send_sms | true | |

### Log in
Method: POST  
Must be logged in: no  
URL: https://hr-humblebundle.appspot.com/processlogin

Parameters (x-www-form-urlencoded):

| Key | Value | Notes |
| --- | ----- | ----- |
| ajax | true | |
| username | [your email address] | |
| password | [your password] | |
| recaptcha_challenge_field | | Leave it blank. |
| recaptcha_response_field | [recaptcha code] | See 'Getting a captcha' section above. |
| authy-token | [your 2FA code] | You can get this from your Authy app or via SMS (see above). |

Example responses:
```
{"goto": "/home", "success": true}
```
```
{"errors": {"captcha": ["Please answer the CAPTCHA"]}, "captcha_required": true, "success": false}
```

### Get orders list
Method: GET  
Must be logged in: yes  
URL: https://hr-humblebundle.appspot.com/api/v1/user/order

Example response:
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

### Get order details
Method: GET  
Must be logged in: yes  
URL: https://hr-humblebundle.appspot.com/api/v1/order/{gamekey}

Replace `{gamekey}` with a gamekey, which you can get from the 'get orders list'
API endpoint.

Example response:
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

### List game bundles
Method: GET  
Must be logged in: no  
URL: https://hr-humblebundle.appspot.com/androidapp/v2/service_check

Example response:
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
