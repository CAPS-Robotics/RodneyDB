<?php
// TODO: Lots of commenting
// Load config and modules
ob_start();
require_once 'vendor/autoload.php';
require_once 'config.php';

// Get rid of annoying notices
error_reporting(E_ALL ^ E_NOTICE);

// Initialize the core with MySQL information
$core = new Core(new MySQL(MYSQL_HOST, MYSQL_PORT, MYSQL_DB, MYSQL_USER, MYSQL_PASSWORD));

// Check if ?p= was not entered
if (!array_key_exists("p", $_GET)) {
	require_once "modules/page/class.HomePage.php";

	$home = new HomePage("home", $core);
	$home->render();
	return;
}

// Switch ?p= and do stuff for each page
switch ($_GET['p']) {
	case "home":
		$page = new HomePage("home", $core);
		$page->render();
		break;

	case "login":
		if ($_SESSION['loggedIn']) {
			$_SESSION['loggedIn'] = false;
			unset($_SESSION['email']);
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
		// Make sure user is signed in before showing me
		if (!$_SESSION['loggedIn']) {
			$page = new ErrorPage("autherror", $core, "Authentication", "You need to be signed in to access this page!");
			$page->writePage();

			break;
		}

		$page = new UserPage("me", $core);
		$page->writePage();

		break;

	case "directory":
		// Make sure user is signed in before showing directory
		if (!$_SESSION['loggedIn']) {
			$page = new ErrorPage("autherror", $core, "Authentication", "You need to be signed in to access this page!");
			$page->writePage();

			break;
		}

		$page = new DirectoryPage("directory", $core);
		$page->writePage();

		break;

	case "admindir":
		// Make sure user is signed in before showing directory
		if (!$_SESSION['loggedIn'] || $core->getUser($_SESSION['email'])['rank'] < 8) {
			$page = new ErrorPage("autherror", $core, "Authentication", "You don't have enough permissions to access this page!");
			$page->writePage();

			break;
		}

		$page = new AdminDirectoryPage("admindir", $core);
		$page->writePage();

		break;

	case "parentdir":
		// Make sure user is signed in before showing directory
		if (!$_SESSION['loggedIn'] || $core->getUser($_SESSION['email'])['rank'] < 8) {
			$page = new ErrorPage("autherror", $core, "Authentication", "You don't have enough permissions to access this page!");
			$page->writePage();

			break;
		}

		$page = new ParentDirectoryPage("parentdir", $core);
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
		header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found"); // Set 404 HTTP header
		$page->writePage();

		break;
}
ob_end_flush();
?>
