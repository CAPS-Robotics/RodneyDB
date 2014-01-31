<?php
//TODO: Lots of commenting
//Load config and modules
ob_start();
require("config.php");
require("modules/class.Core.php");
require("modules/class.MySQL.php");
require("modules/class.Page.php");
require("modules/class.Utils.php");
require("modules/page/class.HomePage.php");
require("modules/page/class.LoginPage.php");
require("modules/page/class.UserPage.php");
require("modules/page/class.DirectoryPage.php");
require("modules/page/class.CheckinPage.php");
require("modules/page/class.DeletePage.php");
require("modules/page/class.BroadcastPage.php");
require("modules/page/class.ConfirmPage.php");
require("modules/page/class.ErrorPage.php");
require("modules/page/class.Ritterisms.php");
require("modules/page/class.Json.php");
error_reporting(E_ALL ^ E_NOTICE); //Get rid of annoying notices
//Initialize the core with MySQL information
$core = new Core(new MySQL(MYSQL_HOST, MYSQL_PORT, MYSQL_DB, MYSQL_USER, MYSQL_PASSWORD));
//Check if ?p= was not entered
if (!array_key_exists("p", $_GET)) {
	$home = new HomePage("home", $core);
	$home->writePage();
	return;
}
//Switch ?p= and do stuff for each page
switch ($_GET['p']) {
	case "home":
		$page = new HomePage("home", $core);
		$page->writePage();
		break;
	case "login":
		if ($_SESSION['loggedIn']) {
			$_SESSION['loggedIn'] = false;
			$_SESSION['email'] = 'THISISREALLYINSECURETONY';
			header("Location: ?p=home");
		}
		else {
			$page = new LoginPage("login", $core);
			$page->writePage();
		}
		break;
	case "json":
		$page = new Json("json", $core);
		$page->writePage();
		break;
	case "me":
		//Make sure user is signed in before showing me
		if (!$_SESSION['loggedIn']) {
			$page = new ErrorPage("autherror", $core, "Authentication", "You need to be signed in to access this page!");
			$page->writePage();
			break;
		}
		$page = new UserPage("me", $core);
		$page->writePage();
		break;
	case "directory":
		//Make sure user is signed in before showing directory
		if (!$_SESSION['loggedIn']) {
			$page = new ErrorPage("autherror", $core, "Authentication", "You need to be signed in to access this page!");
			$page->writePage();
			break;
		}
		$page = new DirectoryPage("directory", $core);
		$page->writePage();
		break;
	case "del":
		if (!$_SESSION['loggedIn'] || $core->getUser($_SESSION['email'])['rank'] < 10) {
			$page = new ErrorPage("autherror", $core, "Authentication", "You don't have enough permissions to access this page!");
			$page->writePage();
			break;
		}
		if ($core->getUser($_SESSION['email'])['id'] == $_GET['id']) {
			$page = new ErrorPage("autherror", $core, "Action", "You can't delete your own account!");
			$page->writePage();
			break;
		}
		$page = new DeletePage("del", $core);
		$page->writePage();
		break;
	case "checkin":
		if (!$_SESSION['loggedIn'] || $core->getUser($_SESSION['email'])['rank'] < 9) {
			$page = new ErrorPage("autherror", $core, "Authentication", "You don't have enough permissions to access this page!");
			$page->writePage();
			break;
		}
		$page = new CheckinPage("checkin", $core);
		$page->writePage();
		break;
	case "broadcast":
		if (!$_SESSION['loggedIn'] || $core->getUser($_SESSION['email'])['rank'] < 9) {
			$page = new ErrorPage("autherror", $core, "Authentication", "You don't have enough permissions to access this page!");
			$page->writePage();
			break;
		}
		$page = new BroadcastPage("broadcast", $core);
		$page->writePage();
		break;
	case "confirm":
		if (!$_SESSION['loggedIn'] || $core->getUser($_SESSION['email'])['rank'] < 9) {
			$page = new ErrorPage("autherror", $core, "Authentication", "You don't have enough permissions to access this page!");
			$page->writePage();
			break;
		}
		$page = new ConfirmPage("confirm", $core);
		$page->writePage();
		break;
	default:
		$page = new ErrorPage("404", $core, "404", "Page not found.");
		header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found"); //Set 404 HTTP header
		$page->writePage();
		break;
}
ob_end_flush();
?>
