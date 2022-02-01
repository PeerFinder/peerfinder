# PeerFinder Web Application

## Installation

1. Clone Git Repository
2. Install php dependencies: composer install --dev
3. Install js dependencies: npm install


## Testing

For code coverage add this to php.ini:

<pre>
[XDebug]
xdebug.mode = coverage
</pre>

Find the current php.ini:
<pre>
php -r "phpinfo();" | grep "php.ini"
</pre>

Install XDebug with "pecl install xdebug". If it fails, run "brew upgrade" and remove shown directories manually.

## Installing Imagick on Mac

<pre>
brew install imagemagick
brew install pcre2

sudo ln -s /opt/homebrew/include/pcre2.h /usr/local/include/
pecl install imagick
valet restart
</pre>

## Icons used in the project

* Many Heroicons
* https://fontawesome.com/v5.15/icons/twitter-square
* https://fontawesome.com/v5.15/icons/linkedin
* https://fontawesome.com/v5.15/icons/facebook-square
* https://fontawesome.com/v5.15/icons/xing-square