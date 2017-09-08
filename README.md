RodneyDB
========

The user management system for FRC Team 2410

Developed originally by David Maas
Now maintained by Eric Osburn, Thomas Gorham, and Tony Ma, with help from others as well

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

Database user table
==

```sql
CREATE TABLE IF NOT EXISTS `new_users` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `rank` int(2) NOT NULL DEFAULT '5',
  `studentId` int(8) NOT NULL DEFAULT '1',
  `name` varchar(255) COLLATE latin1_bin NOT NULL,
  `email` varchar(255) COLLATE latin1_bin NOT NULL,
  `hours` float NOT NULL DEFAULT '0',
  `phone` bigint(10) NOT NULL DEFAULT '0',
  `password` varchar(64) COLLATE latin1_bin NOT NULL DEFAULT '_',
  `lastHourLog` int(12) NOT NULL,
  `text` int(1) NOT NULL DEFAULT '0',
  `parentPhones` varchar(32) COLLATE latin1_bin NOT NULL,
  `parentName` varchar(255) COLLATE latin1_bin NOT NULL DEFAULT '_',
  `parentEmail` varchar(255) COLLATE latin1_bin NOT NULL DEFAULT '_',
  `parentPhone` bigint(10) NOT NULL DEFAULT '0',
  `school` varchar(5) COLLATE latin1_bin NOT NULL DEFAULT '_',
  `grade` int(3) NOT NULL DEFAULT '0',
  `ethnicity` varchar(25) COLLATE latin1_bin NOT NULL DEFAULT '_',
  `frc` int(1) NOT NULL DEFAULT '0',
  `ftc` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)
```

This is the schema for the users table. Create this table is a mysql database and there you go!

Local instance setup
==

[Instructions to create a local instance of Rodney for development.](docs/LOCALDEV.md)
