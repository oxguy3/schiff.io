---
title: "NCAA Men's Basketball media art"
permalink: /media-art/sports/ncaa-mens-basketball/
---
[&larr; Back to media art index]({{site.url}}/media-art/)

I've put together some decent looking posters for NCAA Men's Basketball. In my Plex library, I use a TV Shows library for all my sports, and I have "NCAA Men's Basketball" as one show (for regular season games) and "NCAA Men's Basketball Tournament" as another.

If you don't care to read my explanations, and just want to dive right into my image files, you can find all of them [here](files/).

## Tournament
Nothing fancy for the main poster and background; here they are:
* [poster](files/march-madness-poster.jpg)
* [background](files/march-madness-background.jpg)

For each season, however, I put in a little more effort. I gathered the logos of every Final Four from 1979 to 2019, added a subtle white glowing outline, and superimposed them on a standard background (a stock image of a basketball court). You can find all of the posters here: <https://imgur.com/a/O768cpr>

I generated these posters using an ImageMagick bash script: [mkposters.sh](files/final-four-posters/mkposters.sh). One known issue with the script is that the size of the glowing outline will vary based on how high the resolution of the logo source file is. I didn't care enough to fix this, but if you want to, you could do so by resizing all the logos to a standard resolution before adding the glow. You can find all the source files (logos, background, etc) in [this directory](files/final-four-posters).

## Regular season
Eventually I want to make a nice background and individual posters for each season, but for now, all I have is a main poster:

[poster](files/ncaa-mens-basketball-poster.png) ([GIMP source](files/ncaa-mens-basketball-poster.xcf))
