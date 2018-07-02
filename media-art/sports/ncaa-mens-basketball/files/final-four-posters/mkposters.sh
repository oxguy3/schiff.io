#!/usr/bin/env bash

for f in logo-[0-9][0-9][0-9][0-9].*
do
    magick convert \
        "$f" \
        \( +clone -background white -shadow 100x3+0+0 -channel A -level 0,50% +channel \) \
        +swap -gravity center -composite +repage "glow-${f%.*}.png"
    magick composite \( "glow-${f%.*}.png" -resize 900x1200 \) bg.png -gravity center "poster-${f%.*}.png"
    echo $f
done
