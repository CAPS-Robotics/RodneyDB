<?php
class AdminDirectoryPage extends Page {

    public function __construct($trigger, $core) {
        parent::__construct($trigger, $core);
    }

    public function writePageContent() {
        global $core;

?>

</div>
<div class="container" style="position: relative;">
    <div id="alertbox"></div>
</div>

<div class="container" style="margin-top: 55px;">
    <div class="jumbotron">
        <h1 style="text-decoration:underline;cursor:progress" onclick="countfrcftc(5,6)">Team Directory</h1>
        <table class="table table-hover" style="text-align: left; font-size: medium;">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>StudentID</th>
                    <th style="text-decoration:underline;cursor:progress" onclick="report(2,\'School\')">School</th>
                    <th style="text-decoration:underline;cursor:progress" onclick="report(3,\'Grade\')">Grade</th>
                    <th style="text-decoration:underline;cursor:progress" onclick="report(4,\'Ethnicity\')">Ethnicity</th>
                    <th style="text-decoration:underline;cursor:progress" onclick="toggle(5)">FRC</th>
                    <th style="text-decoration:underline;cursor:progress" onclick="toggle(6)">FTC</th>
                    <?php echo ($core->getUser($_SESSION['email'])['rank'] == 10 ? '<th>Rank</th>' : ''); ?>
                </tr>
            </thead>
            <tbody>
                <?php echo self::getDirectoryTable($core->getUser($_SESSION['email'])['rank']); ?>
            </tbody>
        </table>
    </div>
</div>
    
<script src="assets/js/admindirectory.js"></script>
<script src="assets/js/reports.js"></script>

<?php

    }

    public function getDirectoryTable($rank) {
        global $core;
        $tableStr = "";
        $teamArr = $core->fetchAllUsers();
        foreach ($teamArr as $member) {
            $tableStr .= "<tr id=\"". $member['id'] ."\"><td><span class='editable'>". ($member['rank'] == 5 ? "<a href='?p=confirm&id=" . $member['id'] . "' title='Confirm user'>" : "") . $member['name'] . ($member['rank'] == 5 ? "</a>" : "") . "</td><td><span class='editable'>" . $member['studentId'] . "</td><td><span class='editable'>". $member['school'] ."</td><td><span class='editable'>". $member['grade'] ."</td><td><span class='editable'>". $member['ethnicity'] ."</td><td><input type='checkbox' ". ( $member['frc'] ? 'checked' : '' ) ."></td><td><input type='checkbox' ". ( $member['ftc'] ? 'checked' : '' ) ."></td>". ($core->getUser($_SESSION['email'])['rank'] == 10 ? "<td><span class='editable'>". $member['rank'] . "</td>" : "") ."</tr>";
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
