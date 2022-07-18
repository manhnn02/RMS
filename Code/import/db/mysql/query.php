<?php
namespace DB\MySQL; 
use DB\MySQL as mysql; 

class Query extends mysql{ 

    public $resource_id; 	
    private $record_set; 

    function __construct( $sql ){ 	
        global $DB_SETTINGS; 	
        $this->record_set = Array();
        $this->resource_id = parent::__construct($DB_SETTINGS);	
        $this->query($sql);
    }

    public function query($sql) {
        $this->resource_id = mysqli_query($this->connection, $sql);
        if(!$this->resource_id){
            throw new Exception("Error Processing Request", 1);
        }
        return $this;
    }

    public function fetch() {
        $record_set = Array();
        while($row = mysqli_fetch_assoc($this->resource_id)) {
            $record_set[] = $row;
        }
        return $record_set;
    }	
    public function inserted_id() {        
        return mysqli_insert_id($this->connection);
    }	
}
?>