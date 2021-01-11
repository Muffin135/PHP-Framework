<?php

include '../components/bootstrap.php';
include '../components/navigation.php';

// required headers
header("Access-Control-Allow-Origin: http://localhost/rest-api-web-contacts/");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
session_start();

// files needed to connect to database
include_once 'config/database.php';
include_once 'objects/contact.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate product object
$contact = new Contact($db);
 
// set product property values

$contact->id = $report_data["id"];
$contact->division = $report_data["division"];
$contact->email = $report_data["email"];
$contact->web_contact = $report_data["web_contact"];
$contact->single_email = $report_data["single_email"];

$report_confirm = 0;

// create the user
if($contact->update()){
    
    $mini_confirm_page = '
        <div style="padding: 50px 0px 50px 0px;">
        <div class="container">

        Contact updated. You will be redirected to the <a style="text-decoration:underline;" href="/api/update_con_0.php">Update Contacts</a> page.
    
        </div>
    </div>';

    echo $mini_confirm_page;
}
 
// message if unable to create user
else{
    
    $mini_confirm_page = '
        <div style="padding: 50px 0px 50px 0px;">
        <div class="container">

        Contact not updated. You will be redirected to the <a style="text-decoration:underline;" href="/api/update_con_0.php">Update Contacts</a> page.
    
        </div>
    </div>';

    echo $mini_confirm_page;
}

include '../components/footer.php';

header("Refresh: 3; URL=/api/update_con_0.php"); 

?>