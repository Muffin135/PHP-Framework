<?php
// 'report' object
class Report{
 
    // database connection and table name
    private $conn;
    private $table_name = "group_reports";
 
    // object properties
    public $id;
    public $division;
    public $report_url;
 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
 
    // create new user record
    function create(){
    
        // insert query
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    division = :division,
                    report_url = :report_url";
    
        // prepare the query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->division=htmlspecialchars(strip_tags($this->division));
        $this->first_name=htmlspecialchars(strip_tags($this->report_url));
    
        // bind the values
        $stmt->bindParam(':division', $this->division);
        $stmt->bindParam(':report_url', $this->first_name);
    
        // execute the query, also check if query was successful
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }

    function update(){

        $query = "UPDATE " . $this->table_name . "
                SET
                    division = :division,
                    report_url = :report_url
                WHERE id = :id";
    
        // prepare the query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->report_url=htmlspecialchars(strip_tags($this->report_url));
    
        // bind the values from the form
        $stmt->bindParam(':division', $this->division);
        $stmt->bindParam(':report_url', $this->report_url);
    
        // unique ID of record to be edited
        $stmt->bindParam(':id', $this->id);
    
        // execute the query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }
    
}