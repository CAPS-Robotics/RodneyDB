<?php
class AdminDirectoryPage extends Page {

    public function __construct($trigger, $core) {
        parent::__construct($trigger, $core);
    }

    public function writePageContent() {
        global $core;
        $content = 
'
</div>
<div class="container" style="position: relative;">
<div id="alertbox"></div>
</div>
<div class="container" style="margin-top: 55px;">
<div class="jumbotron">
<h1>Team Directory</h1>
<table class="table table-hover" style="text-align: left; font-size: medium;">
<thead></tr><th>Name</th>'. ($core->getUser($_SESSION['email'])['rank'] == 10 ? '<th>Rank</th>' : '') .'<th>StudentID</th><th>School</th><th>Grade</th><th>Ethnicity</th><th>FRC</th><th>FTC</th></tr></thead>
' . self::getDirectoryTable($core->getUser($_SESSION['email'])['rank']) . '
</table>
</div>
';
        $script =
'<script src="assets/js/admindirectory.js"></script>';
        echo $content, $script;
    }

    public function getDirectoryTable($rank) {
        global $core;
        $tableStr = "";
        $teamArr = $core->fetchAllUsersNotMentors();
        foreach ($teamArr as $member) {
            $tableStr .= "<tr id=\"". $member['id'] ."\"><td><span class='editable'>". ($member['rank'] == 5 ? "<a href='?p=confirm&id=" . $member['id'] . "' title='Confirm user'>" : "") . $member['name'] . ($member['rank'] == 5 ? "</a>" : "") . "</td>". ($core->getUser($_SESSION['email'])['rank'] == 10 ? "<td><span class='editable'>". $member['rank'] . "</td>" : "") ."<td><span class='editable'>" . $member['studentId'] . "</td><td><span class='editable'>". $member['school'] ."</td><td><span class='editable'>". $member['grade'] ."</td><td><span class='editable'>". $member['ethnicity'] ."</td><td><span class='editable'>". $member['frc'] ."</td><td><span class='editable'>". $member['ftc'] ."</td></tr>";
        }
        return $tableStr;
    }

    public function writePage() {
        self::writePageStart();
        self::writePageContent();
        self::writePageEnd();
    }

}
?>
