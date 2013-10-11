<?php
//TODO: Lots of commenting
//Load config and modules
ob_start();
require("config.php");
require("modules/class.Core.php");
require("modules/class.MySQL.php");
require("modules/class.Page.php");
require("modules/page/class.HomePage.php");
require("modules/page/class.LoginPage.php");
require("modules/page/class.UserPage.php");
require("modules/page/class.DirectoryPage.php");
require("modules/page/class.ErrorPage.php");
error_reporting(E_ALL ^ E_NOTICE); //Get rid of annoying notices
//Initialize the core with MySQL information
$core = new Core(new MySQL(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB));
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
			header("Location: ?p=home");
		}
		else {
			$page = new LoginPage("login", $core);
			$page->writePageStart();
			if (array_key_exists("email", $_POST) && array_key_exists("password", $_POST)) {
				if (strlen($_POST['email']) === 0 || strlen($_POST['password']) === 0) {
					$page->alert('danger', 'Error!', 'One or more required fields are missing!');
				}
				else if ((strlen($_POST['email']) !== 0 && strlen($_POST['password']) !== 0) && (strlen($_POST['checkPassword']) === 0 && strlen($_POST['studentId']) === 0 && strlen($_POST['phoneNum']) === 0)) {
					$page->authUser($_POST['email'], hash(DB_USER_HASH_ALGO, $_POST['password']));
				}
				else if ((strlen($_POST['email']) !== 0 && strlen($_POST['password']) !== 0) && (strlen($_POST['checkPassword']) === 0 || strlen($_POST['studentId']) === 0 || strlen($_POST['phoneNum']) === 0)) {
					$page->alert('danger', 'Error!', 'One or more required fields are missing!');
				}
				else {
					$page->createUser($_POST['email'], $_POST['password'], $_POST['checkPassword'], $_POST['name'], $_POST['studentId'], $_POST['texting'], $_POST['phoneNum']);
				}
			}
			$page->writePageContent();
			$page->writePageEnd();
		}
		break;
	case "me":
		//Make sure user is signed in before showing me
		if (!$_SESSION['loggedIn']) {
			$page = new ErrorPage("autherror", $core, "Authentication", "You need to be signed in to access this page!");
			$page->writePage();
			break;
		}
		if (array_key_exists("edit", $_GET)) {
			//TODO: Edit user
		}
		$page = new UserPage("me", $core);
		$page->writePageStart();
		//TODO: Dynamic stuff
		$page->writePageContent();
		$page->writePageEnd();
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
		$core->getDB()->query("DELETE FROM `" . DB_USER_TABLE . "` WHERE `id`='" . $core->getDB()->getMySQLi()->real_escape_string($_GET['id']) . "'");
		header("Location: ?p=directory");
		break;
	default:
		$page = new ErrorPage("404", $core, "404", "Page not found.");
		header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found"); //Set 404 HTTP header
		$page->writePage();
		break;
}
ob_end_flush();
?>
