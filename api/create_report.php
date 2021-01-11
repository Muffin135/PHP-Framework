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
include_once 'objects/report.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate product object
$report = new Report($db);
 
// set product property values

$report->division = $report_data["division"];
$report->report_url = $report_data["group_url"];

$report_confirm = 0;

// create the user
if(
    !empty($report->division) &&
    !empty($report->report_url) &&
    $report->create()
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
  $mini_confirm_page .= 'Report created. You will be redirected to the <a style="text-decoration:underline;" href="api/reports_page.php">Create Reports</a> page.';
}
else if ($report_confirm = 2) {
  $mini_confirm_page .= 'Report not created. You will be redirected to the <a style="text-decoration:underline;" href="api/reports_page.php">Create Reports</a> page.';
}

$mini_confirm_page .= "
    </div>
</div>
";

echo $mini_confirm_page;

include '../components/footer.php';

header("Refresh: 3; URL=/api/reports_page.php"); 

?>

