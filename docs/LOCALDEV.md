# Running a local instance

Often, it's useful to test changes without pushing to the live server. This can be done fairly easily.

These instructions assume a Linux PC, some modifications will be needed for Windows or mac.

## Install PHP & MySQL (Usually MariaDB)

Install through your distro's package manager.

Then, run:

	$> mysql_install_db --user=mysql --basedir=/usr --datadir=/var/lib/mysql

Enable mysqli.so and pdo_mysql.so in php.ini (usually /etc/php/php.ini) by removing the semicolons in front of the lines referencing them
 
## Start MySQL

	$> systemctl start mariadb (or another provider)

and

	$> systemctl enable mariadb(To automatically start on boot)

A mySQL server should now be running on localhost:3306

## Create a database & user table

	mysql> CREATE DATABASE localrodney;
	mysql> USE localrodney;
	mysql [localrodney]> CREATE TABLE IF NOT EXISTS `new_users` (
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
	);

## Clone rodney
	$> git clone https://github.com/CAPS-Robotics/RodneyDB

## Create a config.php in the RodneyDB folder

Something like:

	<?php
	define("MYSQL_HOST", "localhost");
	define("MYSQL_PORT", 3306);
	define("MYSQL_DB", "localrodney");
	define("MYSQL_USER", "root");
	define("MYSQL_PASSWORD", "");
	define("DB_USER_TABLE", "new_users");
	define("DB_USER_HASH_ALGO", "sha256");

	define("CHECKIN_MAX", 10);

	define("TROPO_MESSAGE_TOKEN", "");
	?>

# Start a PHP dev server

	$> php -S localhost:8000

Your Rodney instance is now available at localhost:8000

# Create a new admin account (If needed)

First, create a new account via the web UI

Then,

	mysql> USE localrodney;
	mysql [localrodney]> UPDATE new_users
		-> SET rank = 10
		-> WHERE email = ACCOUNT_EMAIL_HERE;
