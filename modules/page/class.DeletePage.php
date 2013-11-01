<?php
class DeletePage extends Page {
	
	public function __construct($trigger, $core) {
		parent::__construct($trigger, $core);
	}

	public function writePageContent() {
		global $core;
		$content = 
'
<div class="jumbotron" style="font-size: medium;">
<h1>Delete Account</h1>
Are you sure you want to delete ' . $core->getUserFromId($_GET['id'])['name'] . '\'s account? This can not be undone.
<div class="btn-group btn-group-justified">
<a class="btn btn-lg btn-success btn-block" href="?p=del&id=' . $_GET['id'] . '&conf">Yes</a>
<a class="btn btn-lg btn-danger btn-block" href="?p=directory">No</a>
</div>
</div>
';
		echo $content;
	}

	public function deleteAccount($id) {
		global $core;
		$core->getDB()->query("DELETE FROM `" . DB_USER_TABLE . "` WHERE `id`='" . $id . "'");
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
				self::deleteAccount($_GET['id']);
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