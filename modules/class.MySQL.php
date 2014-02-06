<?php
class MySQL extends PDO{

    private $engine; 
    private $host; 
    private $port;
    private $database; 
    private $user; 
    private $pass; 
    
    public function __construct($HOST, $PORT, $DB, $USER, $PASSWORD){ 
        $this->engine = 'mysql'; 
        $this->host = $HOST; 
        $this->port = $PORT;
        $this->database = $DB; 
        $this->user = $USER; 
        $this->pass = $PASSWORD; 
        $dns = $this->engine.':dbname='.$this->database.";host=".$this->host.";port=".$this->port; 
        parent::__construct( $dns, $this->user, $this->pass ); 
    }

    public function getArray($query) {
        $stmt = $this->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function updateDB($id,$field,$value) {
        $query = "UPDATE `". DB_USER_TABLE ."` SET `". $field ."`=\"". $value ."\" WHERE `id`='". $id ."'";
        return $this->exec($query);
    }

}
?>