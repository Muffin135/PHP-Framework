<?php

// Starts global session for passing variables between files
session_start();

// Sets the type of content that will be printed to the page
header('Content-Type: application/json');

// Credentials for logging into databse server
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test_database";

// Creates connection to database
$conn = new mysqli($servername, $username, $password, $dbname);
// Checks connection to database, ends program if connection fails
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Gets selected groups that will receive the email
$search_param= $_SESSION["user_data"]["selected"];
// Gets the message to be included in the body of the email
$custom_message = $_SESSION["user_data"]["email-body"];

// Set content-type header for sending HTML email 
$headers = "MIME-Version: 1.0" . "\r\n"; 
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n"; 

// Initializes email queue
$queue = [];

// Keeps track of emails that did not sent correctly
$unsent_mail = [];

// Error token to check for emails that did not send
$success_token = "Pass";

// Lines 42 through 143 are the process for sending multiple emails

// Repeats the process for each group
for($i = 0; $i < count($search_param); $i++) {

    // Finds and saves the name of the current group
    $current_param = $search_param[$i];

    // Sends a SQL query to database to get the table for all recipient, then saves the results
    $sql = "SELECT id, division, first_name, last_name, email, web_contact, single_email FROM recipients";
    $result = $conn->query($sql);

    // Iterates through through each row of the table, where each row is a single recipient
    if ($result->num_rows > 0) {
       
        // Gets the current row - current recipient
        while($row = $result->fetch_assoc()) {

            $current_single_email = $row["email"];

            // Checks to see if the recipient is in the current group the email is being sent for
            if ($row["division"] == $current_param) {
                
                 // If they are, it will first check their preference for a single, condensed email

                // If the user has set their preference for a single email
                if ($row["single_email"] == "Yes") {
                   
                    // Flags the email to be added to the queue
                    $search_result = "False";

                     // If the queue is not empty, it will first check the existing entries to see if they match the current recipient
                    if (count($queue) > 0) {
                        for ($j = 0; $j < count($queue); $j++) {

                            // If there is an matching entry, it adds the current group's email message to that recipient's existing entry
                            if ($queue[$j][0] == $row["email"]) {
                                echo " Preference already logged at index " . $j . ". Updating queue entry.\n";
                                array_push($queue[$j], $row["division"]);
                                // Turns off single email flag
                                $search_result = "True";
                            }

                        }
                    }

                     // If the flag is still on by this point, it means that the recipient is not in the queue
                    if ($search_result == "False") {
                        // A new entry is created for the recipient with their email and the current group and is pushed to the queue
                        $new_entry = [$row["email"],$custom_message,$row["division"]];
                        array_push($queue, $new_entry);
                    }
    
                }
                // If the recipient does not have a preference for a single email, it sends the email as-is
                else {

                    $opt_in_url = "http://localhost:1234/api/opt-in.php?userid=" . $row["email"];

                    // Email to be sent for the multiple email option
                    $htmlContent = ' 
                    <html>  
                    <body> 
                        <p style="color:black;font-family=arial;font-size:15px">
                            Dear ';
                            
                    // Replaces |insert name here| with first_name and last_name in the SQL query
                    $htmlContent .= $row["first_name"] . " " . $row["last_name"] . ",<br><br>";

                    $htmlContent .= "Attached to this email is a linked report that included Accessibility issues with one or more web sites managed by your college or department.<br><br>";
                    $htmlContent .= "We want to thank you again for taking the time to remediate the issues outlined in the report and remind you that there are resources available ";
                    $htmlContent .= "including the campus web developer training or monthly web accessibility open labs.";
                    $htmlContent .= "<br><br>". $current_param .":<br>";
                    
                    $sql_two = "SELECT id, division, report_url FROM group_reports";
                    $result_two = $conn->query($sql_two);

                    // Iterates through through each row of the table, where each row is a single recipient
                    if ($result_two->num_rows > 0) {
       
                        // Gets the current row - current recipient
                        while($row = $result_two->fetch_assoc()) {
                            if ($row["division"] == $current_param) {
                                $htmlContent .= '<a href="' . $row["report_url"] . '">'. $current_param . " Report</a>";
                                $htmlContent .= "<br>";
                            }
                        }

                    }

                    $htmlContent .= "<br>We are aware of issues related to the Drupal template and are currently working to address them. Next quarter’s report should reflect template changes and reduce the number of issues generated by the automated tool.";
                    $htmlContent .= '<br><br> The next scheduled scan will occur in early January, 2020. We value your suggestions regarding this quarterly report process. If you have any questions or concerns, please email ATI-Compliance@csulb.edu.';
                    $htmlContent .= "<br><br>Web Development Center<br>Division of IT";
                    $htmlContent .= "<br><br>To opt-in for a single email regarding the departments you are assign, click the following link:<br>";
                    $htmlContent .= "<a href=" . $opt_in_url . ">Opt-In Link</a>";
                    $htmlContent .= '
                        </p>
                    </body> 
                    </html>';
                    //
                    
                    // If the email is successfully sent, it prints a success message
                    if (mail($current_single_email,"Test Multiple Email",$htmlContent,$headers)) {
                        echo "Email sent to " . $current_single_email . "\n";
                    }
                    // If the email is not successfully sent, it prints an error message and sets the error token to fail
                    else {
                        echo "Email not sent to " . $current_single_email . "\n";

                        $unsent_message = $current_single_email . "," . $current_param;
                        array_push($unsent_mail, $unsent_message);

                        $success_token = "Fail";
                    }
                }
            }
        }
    } 
    
    else {
        echo "0 results";
    }

    // Debug message to see current email queue
    echo "\n";
    print_r($queue);
    echo "\n"."Emails in queue: ". (count($queue)) . "\n";
}

// Lines 147 through 213 are the process for opening the single-email queue and sending single emails

for ($queue_index = 0; $queue_index < count($queue); $queue_index++) {

    // Gets the current recipient's email and the message they are supposed to receive
    $current_email = $queue[$queue_index][0];
    $full_message = $queue[$queue_index][1];
    $name = "";

    // Calls another SQL query to get secondary information used in composing emails
    $sql = "SELECT id, division, first_name, last_name, email, web_contact, single_email FROM recipients";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            if ($row["email"] == $current_email) {
                $name = $row["first_name"] . " " . $row["last_name"];
            }
        }
    }

    // Initializes a list for the groups the recipient is subscribed to 
    $divisions_for = "";

    $opt_out_url = "http://localhost:1234/api/opt-out.php?userid=" . $current_email;

    // Email to be sent for the single email option
    $new_htmlContent = ' 
    <html>  
    <body> 
        <p style="color:black;font-family=arial;font-size:15px">
            Dear ';
            
    // replace |insert name here| with first_name and last_name in the SQL query
    $new_htmlContent .= $name . ",<br><br>";

    $new_htmlContent .= "Attached to this email is a linked report that included Accessibility issues with one or more web sites managed by your college or department.<br><br>";
    $new_htmlContent .= "We want to thank you again for taking the time to remediate the issues outlined in the report and remind you that there are resources available ";
    $new_htmlContent .= "including the campus web developer training or monthly web accessibility open labs.";
    $new_htmlContent .= "<br>";

    for ($array_index = 2; $array_index < count($queue[$queue_index]); $array_index++) {
        
        $current_group = $queue[$queue_index][$array_index];

        $new_htmlContent .= "<br>" . $current_group .":<br>";
        
        $sql_two = "SELECT id, division, report_url FROM group_reports";
        $result_two = $conn->query($sql_two);

        // Iterates through through each row of the table, where each row is a single recipient
        if ($result_two->num_rows > 0) {

            // Gets the current row - current recipient
            while($row = $result_two->fetch_assoc()) {
                if ($row["division"] == $current_group) {
                    $new_htmlContent .= '<a href="' . $row["report_url"] . '">' . $current_group . " Report</a>";
                    $new_htmlContent .= "<br>";
                }
            }

        }

    }

    $new_htmlContent .= "<br>We are aware of issues related to the Drupal template and are currently working to address them. Next quarter’s report should reflect template changes and reduce the number of issues generated by the automated tool.";
    $new_htmlContent .= '<br><br> The next scheduled scan will occur in early January, 2020. We value your suggestions regarding this quarterly report process. If you have any questions or concerns, please email <a href="ATI-Compliance@csulb.edu">ATI-Compliance@csulb.edu</a>.';
    $new_htmlContent .= "<br><br>Web Development Center<br>Division of IT";
    $new_htmlContent .= "<br><br>To opt-in for a single email regarding the departments you are assign, click the following link:<br>";
    $new_htmlContent .= "<a href=" . $opt_out_url . ">Opt-Out Link</a>";
    $new_htmlContent.= '
        </p>
    </body> 
    </html>';
    //

    // Set content-type header for sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n"; 
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n"; 

    // Same "email sent" check as the multiple email option
    if (mail($current_email,"Test Single Email",$new_htmlContent,$headers)) {
        echo "Email sent to " . $current_email . "\n";
    }
    else {
        echo "Email not sent to " . $current_email . "\n";

        $unsent_message = $current_email;
        array_push($unsent_mail, $unsent_message);

        $success_token = "Fail";
    }
}

// Closes the connection to the databas
$conn->close();

$_SESSION["unsent_emails"] = $unsent_mail;

// Save success criteria in SESSION variable 
// Wrap in a try-catch 

// Generates a URL for the results page which passes the error token as its parameter
$newURL = "../result.php?success=".$success_token;

// Redirects to the the results page
header('Location: '.$newURL);

?>