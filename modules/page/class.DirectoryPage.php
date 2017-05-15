<?php
class DirectoryPage extends Page {

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
                        <th>Email</th>
                        <th>Phone Number</th>
                        <?php echo ($core->getUser($_SESSION['email'])['rank']> 7 ? '<th>Student ID</th><th>Hours</th>' . ($core->getUser($_SESSION['email'])['rank']>= 10 ? '<th>Delete</th>' : '') : '') . '</tr></thead>
' . self::getDirectoryTable($core->getUser($_SESSION['email'])['rank']); ?>
            </table>
        </div>
    </div>
</div>

<script src="assets/js/directory.js"></script>

<?php

    }

    public function getDirectoryTable($rank) {
        global $core;
        $tableStr = "";
        $teamArr = $core->fetchAllUsers();
        foreach ($teamArr as $member) {
            if ($member['rank'] == 11)
            {
                continue;
            }
            $tableStr .= "<tr id=\"". $member['id'] ."\"><td>" . ($rank > 7 ? "<span class='editable'>" : "") . ( $rank > 7 ? ($member['rank'] == 5 ? "<a href='?p=confirm&id=" . $member['id'] . "' title='Confirm user'>" : "") : "" ) . $member['name'] . ( $rank > 7 ? ($member['rank'] == 5 ? "</a>" : "") : "" ) . ($rank > 7 ? "</span>" : "") . "&nbsp;<span class='label label-" . ($member['rank'] == 7 ? "success"  : ($member['rank'] == 8 ? "danger" : ($member['rank'] == 9 ? "warning" : ($member['rank']>= 10 ? "primary" : "default" ) ) ) ) . "'>" . Utils::getRankName($member['rank']) . "</span></td><td>".($rank>7?"<span class='editable'/>":"") . $member['email'] . "</td><td>".($rank>7?"<span class='editable'/>":"") . $member['phone'] . "</td>" . ($rank> 7 ? "<td>".($rank>7?"<span class='editable'/>":"") . $member['studentId'] . "</td>" . "<td>".($rank>9?"<span class='editable'/>":"") . $member['hours'] . "</td>" . ($rank>= 10 ? "<td><a href='?p=del&id=" . $member['id'] . "'>Delete</a></td>" : "") : "") . "</tr>";
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
