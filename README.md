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