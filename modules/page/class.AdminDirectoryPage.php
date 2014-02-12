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
<thead></tr><th>Name</th>
'. ($core->getUser($_SESSION['email'])['rank'] == 10 ? '<th>Rank</th>' : '') .'
<th>StudentID</th>
<th style="text-decoration:underline;cursor:progress" onclick="report(3,\'School\')">School</th>
<th style="text-decoration:underline;cursor:progress" onclick="report(4,\'Grade\')">Grade</th>
<th style="text-decoration:underline;cursor:progress" onclick="report(5,\'Ethnicity\')">Ethnicity</th>
<th style="text-decoration:underline;cursor:progress" onclick="toggle(6)">FRC</th>
<th style="text-decoration:underline;cursor:progress" onclick="toggle(7)">FTC</th></tr></thead><tbody>
' . self::getDirectoryTable($core->getUser($_SESSION['email'])['rank']) . '
</tbody></table>
</div>
';
        $script =
'<script src="assets/js/admindirectory.js"></script>
<script src="assets/js/reports.js"></script>';
        echo $content, $script;
    }

    public function getDirectoryTable($rank) {
        global $core;
        $tableStr = "";
        $teamArr = $core->fetchAllUsersNotMentors();
        foreach ($teamArr as $member) {
            $tableStr .= "<tr id=\"". $member['id'] ."\"><td><span class='editable'>". ($member['rank'] == 5 ? "<a href='?p=confirm&id=" . $member['id'] . "' title='Confirm user'>" : "") . $member['name'] . ($member['rank'] == 5 ? "</a>" : "") . "</td>". ($core->getUser($_SESSION['email'])['rank'] == 10 ? "<td><span class='editable'>". $member['rank'] . "</td>" : "") ."<td><span class='editable'>" . $member['studentId'] . "</td><td><span class='editable'>". $member['school'] ."</td><td><span class='editable'>". $member['grade'] ."</td><td><span class='editable'>". $member['ethnicity'] ."</td><td><input type='checkbox' ". ( $member['frc'] ? 'checked' : '' ) ."></td><td><input type='checkbox' ". ( $member['ftc'] ? 'checked' : '' ) ."></td></tr>";
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
