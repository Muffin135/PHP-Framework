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

$report->id = $report_data["id"];
$report->division = $report_data["division"];
$report->report_url = $report_data["group_url"];

$report_confirm = 0;

// create the user
if($report->update()){
    
    $mini_confirm_page = '
        <div style="padding: 50px 0px 50px 0px;">
        <div class="container">

        Report updated. You will be redirected to the <a style="text-decoration:underline;" href="api/update_url_0.php">Update Reports</a> page.
    
        </div>
    </div>';

    echo $mini_confirm_page;

    include '../components/footer.php';

    header("Refresh: 3; URL=/api/update_url_0.php"); 
  
    exit; 
}
 
// message if unable to create user
else{
    
    $mini_confirm_page = '
        <div style="padding: 50px 0px 50px 0px;">
        <div class="container">

        Report not updated.
    
        </div>
    </div>';

    echo $mini_confirm_page;

    include '../components/footer.php';
}


?>

