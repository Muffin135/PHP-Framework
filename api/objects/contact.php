<?php
// 'contact' object
class Contact{
 
    // database connection and table name
    private $conn;
    private $table_name = "recipients";
 
    // object properties
    public $id;
    public $division;
    public $first_name;
    public $last_name;
    public $email;
    public $web_contact;
    public $single_email;
 
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
                    first_name = :first_name,
                    last_name = :last_name,
                    email = :email,
                    web_contact = :web_contact,
                    single_email = :single_email";
    
        // prepare the query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->division=htmlspecialchars(strip_tags($this->division));
        $this->first_name=htmlspecialchars(strip_tags($this->first_name));
        $this->last_name=htmlspecialchars(strip_tags($this->last_name));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->web_contact=htmlspecialchars(strip_tags($this->web_contact));
        $this->single_email=htmlspecialchars(strip_tags($this->single_email));
    
        // bind the values
        $stmt->bindParam(':division', $this->division);
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':web_contact', $this->web_contact);
        $stmt->bindParam(':single_email', $this->single_email);
    
        // execute the query, also check if query was successful
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }

    function update(){

        $query = "UPDATE " . $this->table_name . "
                SET
                    email = :email,
                    web_contact = :web_contact,
                    single_email = :single_email
                WHERE id = :id";
    
        // prepare the query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->web_contact=htmlspecialchars(strip_tags($this->web_contact));
        $this->single_email=htmlspecialchars(strip_tags($this->single_email));
    
        // bind the values from the form
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':web_contact', $this->web_contact);
        $stmt->bindParam(':single_email', $this->single_email);
    
        // unique ID of record to be edited
        $stmt->bindParam(':id', $this->id);
    
        // execute the query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }
    
    // emailExists() method will be here
}