<?php include '../components/bootstrap.php'; ?>
<?php include '../components/navigation.php'; ?>

<div style="padding: 50px 0px 50px 0px;">
    <div class="container">
        <div class="form-gray-bg">

<?php

// Starts global session for passing variables between files
session_start();

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

// Saves the user's input to the global session
$_SESSION["user_data"]= $data_from_user;

// Initializes email queue
$queue = [];

// Gets selected groups that will receive the email
$search_param= $data_from_user["selected"];
// Gets the message to be included in the body of the email
$custom_message = $data_from_user["email-body"];

// Sets content-type header for sending HTML email 
$headers = "MIME-Version: 1.0" . "\r\n"; 
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n"; 

// Confirmation message prints number and names of groups to be emailed
$confirm_msg = "There are " . count($search_param) . " groups that will receive the email: " . "<br/>";

for ($num_groups = 0; $num_groups < count($search_param); $num_groups++) {
    $confirm_msg .= "- " . $search_param[$num_groups] . "<br/>";
}

// Beginning of the confirmation message
$confirm_msg .= "<br/>Email message: <br/>";
$confirm_msg .= "- " . "'" . $custom_message . "'" . "<br/><br/>";

// Lines 57 through 124 are the process for sending multiple emails

// Repeats the process for each group
for($i = 0; $i < count($search_param); $i++) {
    
    // Finds and saves the name of the current group
    $current_param = $search_param[$i];

    $confirm_msg .= "The following people will be sent an email for the " . $current_param . " group: <br/><ul>";

    // Sends a SQL query to database to get the table for all recipient, then saves the results
    $sql = "SELECT id, division, first_name, last_name, email, web_contact, single_email FROM recipients";
    $result = $conn->query($sql);

    // Iterates through through each row of the table, where each row is a single recipient
    if ($result->num_rows > 0) {
        
        // Gets the current row - current recipient
        while($row = $result->fetch_assoc()) {

            // Checks to see if the recipient is in the current group the email is being sent for
            if ($row["division"] == $current_param) {
                
                // If they are, it will first check their preference for a single, condensed email

                // If the user has set their preference for a single email
                if ($row["single_email"] == "Yes") {

                    // First, adds their email to the confirmation message
                    $confirm_msg .= "<li>" . $row["email"] . "</li>";

                        // Flags the email to be added to the queue
                        $search_result = "False";

                        // If the queue is not empty, it will first check the existing entries to see if they match the current recipient
                        if (count($queue) > 0) {
                            for ($j = 0; $j < count($queue); $j++) {

                                // If there is an matching entry, it adds the current group's email message to that recipient's existing entry
                                if ($queue[$j][0] == $row["email"]) {
                                    array_push($queue[$j], $row["division"]);
                                    // Turns off the single email flag
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
                    $confirm_msg .= "<li>" . $row["email"] . "</li>";
                }
            }
        }
    } 
    
    else {
        echo "0 results";
    }

    $confirm_msg .= "</ul>";
    
}

// Lines 130 through 160 are the process for opening the single-email queue and sending single emails

$confirm_msg .= "The following people will receive a single email with information for multiple groups: <br/><ul>";

// The queue is processed one recipient at a time
for ($queue_index = 0; $queue_index < count($queue); $queue_index++) {

    // Gets the current recipient's email and the message they are supposed to receive
    $current_email = $queue[$queue_index][0];
    $full_message = $queue[$queue_index][1];

    // Initializes a list for the groups the recipient is subscribed to 
    $divisions_for = "";

    // Iterates through the sub-array to get all of the recipient's groups and adds them to the list
    for ($array_index = 2; $array_index < count($queue[$queue_index]); $array_index++) {
        if ($array_index == (count($queue[$queue_index]) - 1)) {
            $divisions_for .= $queue[$queue_index][$array_index];
        }
        else {
            $divisions_for .= $queue[$queue_index][$array_index] . ", ";
        }
    }

    $confirm_msg .= "<li>" . $current_email . " : " . $divisions_for . "</li>";
    
}

$confirm_msg .= "</ul><br/>If these results are correct, press 'Submit' to send. <br/>If not, you can go to the previous page to change your preference.<br/>";

// Prints the confirmation message with all of the recipient and what messages they will be receiving
echo $confirm_msg;

// Closes the connection to the database
$conn->close();

?>

</div>
<link rel="stylesheet" type="text/css" href="../common/css/forms.css">

<br/>

<form method="POST" action="../api/mailing_page.php"> 
    <button type="submit" class="btn btn-default" id="submit-btn">Submit</button>
</form>

</div>
</div>

<?php include '../components/footer.php'; ?>