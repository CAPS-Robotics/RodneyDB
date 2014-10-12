<?php
class HomePage extends Page {
	public function __construct($trigger, $core) {
		parent::__construct($trigger, $core);
	}

	public function writePageContent() {
		$content = 
'
<div style="text-align:left;">
<a href="https://github.com/CAPS-Robotics/RodneyDB"><img style="position: absolute; border: 0; padding-top:15px;" src="https://github-camo.global.ssl.fastly.net/567c3a48d796e2fc06ea80409cc9dd82bf714434/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f6c6566745f6461726b626c75655f3132313632312e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_left_gray_6d6d6d.png"></a>
</div>
<div class="jumbotron">
<img src="assets/img/mustang.svg" width="250"></img>
<h1>Rodney</h1>
<p class="lead">Team member management database for <a href="http://mmr2410.com/">FRC Team 2410</a>.</p>
</div>
';
		echo $content;
	}

	public function writePage() {
		self::writePageStart();
		self::writePageContent();
		self::writePageEnd();
	}

	public function render() {

		$mustache = new Mustache_Engine([
			'cache' => dirname(__FILE__).'/tmp/cache/mustache',
			'loader' => new Mustache_Loader_FilesystemLoader(__DIR__.'/../../views'),
			'partials_loader' => new Mustache_Loader_FilesystemLoader(__DIR__.'/../../views/partials')
		]);

		$page = $mustache->loadTemplate('home');
		echo $page->render([
			"loggedIn" => $_SESSION['loggedIn'],
			"aboveMember" => $core->getUser($_SESSION['email'])['rank'] > 7,
			"user" => $core->getUser($_SESSION['email']),
			"home" => "active"
		]);
	}
}
?>
