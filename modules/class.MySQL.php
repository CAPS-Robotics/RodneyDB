<?php
class MySQL {

    private $conn;

    public function __construct($HOST, $USER, $PASS, $DB) {
        global $conn;
        $conn = new mysqli($HOST, $USER, $PASS, $DB);
        $conn->ping();
    }

    public function getArray($query) {
        global $conn;
        $conn->ping();
        $res = $conn->query($query);
        if ($res->num_rows >= 1) {
            $arr = array();
            while ($row = $res->fetch_assoc()) {
                $arr[] = $row;
            }
            return $arr;
        }
        else {
            return;
        }
    }

    public function query($query) {
        global $conn;
        $conn->ping();
        return $conn->query($query);
    }

    public function getMySQLi() {
        global $conn;
        return $conn;
    }
}
?>