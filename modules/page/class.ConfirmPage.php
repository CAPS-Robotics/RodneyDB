<?php
class ConfirmPage extends Page {
	
	public function __construct($trigger, $core) {
		parent::__construct($trigger, $core);
	}

	public function writePageContent() {
		global $core;
?>

<div class="jumbotron" style="font-size: medium;">
	<h1>Confirm Account</h1>
	Are you sure you want to confirm <?php $core->getUserFromId($_GET['id'])['name']; ?>'s account?
	<div class="btn-group btn-group-justified">
		<a class="btn btn-lg btn-success btn-block" href="?p=confirm&id=<?php $_GET['id']?>&conf">Yes</a>
		<a class="btn btn-lg btn-danger btn-block" href="?p=directory">No</a>
	</div>
</div>

<?php

	}

	public function confirmAccount($id) {
		global $core;
		$core->getDB()->query("UPDATE `" . DB_USER_TABLE . "` SET `rank`='6' WHERE `id`='" . $id . "'");
		header("Location: ?p=directory");
	}

	public function writePage() {
		global $core;
		if (is_null($core->getUserFromId($_GET['id']))) {
			ob_clean();
			$page = new ErrorPage("error", $core, "Action", "This user does not exist!");
			$page->writePage();
			return;
		}
		self::writePageStart();
		if (array_key_exists("conf", $_GET)) {
			if (is_numeric($_GET['id'])) {
				self::confirmAccount($_GET['id']);
			}
			else {
				ob_clean();
				$page = new ErrorPage("error", $core, "Action", "Invalid account ID.");
				$page->writePage();
				return;
			}
		}
		self::writePageContent();
		self::writePageEnd();
	}

}
?>