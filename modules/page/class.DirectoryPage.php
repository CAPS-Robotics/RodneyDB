<?php
class DirectoryPage extends Page {

    public function __construct($trigger, $core) {
        parent::__construct($trigger, $core);
    }

    public function writePageContent() {
        global $core;
        $content = 
'
<div class="jumbotron">
<h1>Team Directory</h1>
<table class="table table-hover" style="text-align: left; font-size: medium;">
<thead></tr><th>Name</th><th>Email</th><th>Phone Number</th>' . ($core->getUser($_SESSION['email'])['rank'] >= 7 ? '<th>Student ID</th><th>Hours</th>' . ($core->getUser($_SESSION['email'])['rank'] >= 10 ? '<th>Delete</th>' : '') : '') . '</tr></thead>
' . self::getDirectoryTable($core->getUser($_SESSION['email'])['rank']) . '
</table>
</div>
';
        $script =
'
<script>
$(".editable").click(function(){$(this).html(\'<input type="text" class="form-control edit input-sm" value="\'+(($(this).children().length==0)?($(this).text()):($(this).children()[0].value))+\'" autocomplete="off">\');$("input.form-control.edit").focus();$("input.edit").focusout(function(){$(this).replaceWith($(this).val())})})
</script>
';
        $test_usr = $core->getUser('dmattt98@gmail.com');
        echo $content, $script, $test_usr;
    }

    public function getDirectoryTable($rank) {
        global $core;
        $tableStr = "";
        $teamArr = $core->fetchAllUsers();
        foreach ($teamArr as $member) {
            $tableStr .= "<tr><td>" . ($member['rank'] == 5 ? "<a href='?p=confirm&id=" . $member['id'] . "' title='Confirm user'>" : "") . $member['name'] . ($member['rank'] == 5 ? "</a>" : "") . " <span class='label label-" . ($member['rank'] == 7 ? "success"  : ($member['rank'] == 8 ? "danger" : ($member['rank'] == 9 ? "warning" : ($member['rank'] >= 10 ? "primary" : "default" ) ) ) ) . "'>" . Utils::getRankName($member['rank']) . "</span></td><td class='editable'>" . $member['email'] . "</td><td class='editable'>" . Utils::formatPhoneNum($member['phone']) . "</td>" . ($rank >= 7 ? "<td class='editable'>" . $member['studentId'] . "</td>" . "<td class='editable'>" . $member['hours'] . "</td>" . ($rank >= 10 ? "<td><a href='?p=del&id=" . $member['id'] . "'>Delete</a></td>" : "") : "") . "</tr>";
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
