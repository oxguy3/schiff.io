# schiff.io

My personal website -- accessible at [schiff.io](https://www.schiff.io).

## Building

I'm not sure why you'd want to, but you can easily build this site on your local machine. Here are the dependencies:
* Ruby, including dev files and Rubygems (2.4 or greater) (`apt install ruby ruby-dev`)
* gcc, make, git (`apt install gcc make git`)
* Bundler (`gem install bundler`)

Everything else will install itself when you run `bundle install`. Here's the script I use to build it on my web server:

```
#!/usr/bin/env bash
export JEKYLL_ENV=production
cd schiff.io/
git pull
bundle install
jekyll build
```

That builds a production copy of the site in schiff.io/\_site/ -- you need to run your own web server to actually serve it (I use Apache).

If you just want to test the site locally, just run `bundle exec jekyll serve`, and it'll spin up a test web server at http://localhost:4000/ (you need to have already run `bundle install` once first).

## License
Copyright 2017 Hayden Schiff. All rights reserved unless otherwise noted.

However, if there's something in this repo that you want to reuse, feel free to [contact me](https://www.schiff.io/about#contact) or [open an issue](https://github.com/oxguy3/schiff.io/issues) about it. I don't want to use a free license for the entire repository, but if there's a particular bit of theme code or something like that that you want to reuse, I'm sure we could work something out.

### Font Awesome
Font Awesome is an icon set.
Source: http://fontawesome.io/

- The Font Awesome font is licensed under the SIL OFL 1.1:
  - http://scripts.sil.org/OFL
- Font Awesome CSS, LESS, and Sass files are licensed under the MIT License:
  - https://opensource.org/licenses/mit-license.html
- The Font Awesome documentation is licensed under the CC BY 3.0 License:
  - http://creativecommons.org/licenses/by/3.0/
- Attribution is no longer required as of Font Awesome 3.0, but much appreciated:
  - `Font Awesome by Dave Gandy - http://fontawesome.io`
- Full details: http://fontawesome.io/license/

### font_awesome.rb
font_awesome.rb is the Jekyll plugin I use for easily inserting Font Awesome
icons with Liquid syntax.
Source: https://gist.github.com/23maverick23/8532525

The MIT License (MIT)

Copyright (c) 2014 Ryan Morrissey

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

### minima
Minima is the Jekyll theme my site is based on. I have modified it, but my code
is still heavily based on it.
Source: https://github.com/jekyll/minima

The MIT License (MIT)

Copyright (c) 2016 Parker Moore

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
