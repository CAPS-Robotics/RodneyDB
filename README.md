RodneyDB
========

The user management system for FRC Team 2410

Developed originally by David Maas
Now maintained by Eric Osburn, Thomas Gorham, and Tony Ma

config.php
==

The config.php file is not included as part of the repository to streamline the webserver update process.
This is what the config.php file looks like without any options actually defined.
```php
<?php
define("MYSQL_HOST", "");
define("MYSQL_PORT", 3306);
define("MYSQL_DB", "");
define("MYSQL_USER", "");
define("MYSQL_PASSWORD", "");
define("DB_USER_TABLE", "");
define("DB_USER_HASH_ALGO", "sha256");

define("CHECKIN_MAX", 10);

define("TROPO_MESSAGE_TOKEN", "");
?>
```
or https://gist.github.com/tuogex/7015907

Just put that in a file called config.php in the same directory as index.php, put in the configuration you need and go!
