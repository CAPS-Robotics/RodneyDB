<?php
class ParentDirectoryPage extends Page {

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
        <h1>Team Directory</h1>
            <table class="table table-hover" style="text-align: left; font-size: medium;">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Parent</th>
                        <th>Parent Email</th>
                        <th>Parent Phone</th>
                    </tr>
                </thead>
                <?php echo self::getDirectoryTable($core->getUser($_SESSION['email'])['rank']); ?>
            </table>
    </div>

    <script src="assets/js/parentdirectory.js"></script>
    <script src="assets/js/reports.js"></script>

<?php

    }

    public function getDirectoryTable($rank) {
        global $core;
        $tableStr = "";
        $teamArr = $core->fetchAllUsersNotMentors();
        foreach ($teamArr as $member) {
            $tableStr .= "<tr id=\"". $member['id'] ."\"><td><span class='editable'>". ($member['rank'] == 5 ? "<a href='?p=confirm&id=" . $member['id'] . "' title='Confirm user'>" : "") . $member['name'] . ($member['rank'] == 5 ? "</a>" : "") . "</td><td><span class='editable'>" . $member['parentName'] . "</td><td><span class='editable'>" . $member['parentEmail'] . "</td><td><span class='editable'>". $member['parentPhone'] ."</td></tr>";
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
