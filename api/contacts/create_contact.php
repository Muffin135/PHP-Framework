<?php

include '../components/bootstrap.php';
include '../components/navigation.php';

// required headers
header("Access-Control-Allow-Origin: http://localhost/rest-api-web-contacts/");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// files needed to connect to database
include_once 'config/database.php';
include_once 'objects/contact.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate product object
$contact = new Contact($db);
 
// get posted data
//$data = json_decode(file_get_contents("php://input"));
 
// set product property values

$contact->division = $contact_data["division"];
$contact->first_name = $contact_data["first_name"];
$contact->last_name = $contact_data["last_name"];
$contact->email = $contact_data["email"];
$contact->web_contact = $contact_data["web_contact"];
$contact->single_email = $contact_data["single_email"];

// create the user
if(
    !empty($contact->division) &&
    !empty($contact->first_name) &&
    !empty($contact->last_name) &&
    !empty($contact->email) &&
    !empty($contact->web_contact) &&
    !empty($contact->single_email) &&
    $contact->create()
){
 
    // set response code
    http_response_code(200);
    $report_confirm = 1;
    // display message: user was created
    //echo json_encode(array("message" => "Report was created."));
}
 
// message if unable to create user
else{
 
    // set response code
    http_response_code(400);
    $report_confirm = 1;
    // display message: unable to create user
    //echo json_encode(array("message" => "ERROR: Report was not created."));
}


$mini_confirm_page = '
<div style="padding: 50px 0px 50px 0px;">
    <div class="container">';

if ($report_confirm = 1) {
  $mini_confirm_page .= 'New contact created. You will be redirected to the <a style="text-decoration:underline;" href="/api/contacts_page.php">Update Contacts</a> page.';
}
else if ($report_confirm = 2) {
  $mini_confirm_page .= 'Contact not created. You will be redirected to the <a style="text-decoration:underline;" href="/api/contacts_page.php">Update Contacts</a> page.';
}

$mini_confirm_page .= "
    </div>
</div>
";

echo $mini_confirm_page;

header("Refresh: 3; URL=/api/contacts_page.php"); 

include '../components/footer.php';

?>