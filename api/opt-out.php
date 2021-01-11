<?php

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

$user_email = $_GET["userid"];

// Sends a SQL query to database to get the table for all recipient, then saves the results
$sql = "SELECT id, division, first_name, last_name, email, web_contact, single_email FROM recipients";
$result = $conn->query($sql);

// Iterates through through each row of the table, where each row is a single recipient
if ($result->num_rows > 0) {
       
    // Gets the current row - current recipient
    while($row = $result->fetch_assoc()) {

        if($user_email == $row["email"]){
            
            $preference_change = "UPDATE recipients SET single_email = 'No' WHERE email = '" . $user_email ."' ";

            $sql_pass = $conn->query($preference_change);

            echo "\nShould pass";
        }

    }
} 
    
else {
    echo "0 results";
}

unset($_GET["userid"]);
    

// Closes the connection to the databas
$conn->close();
?>

<?php include '../components/bootstrap.php'; ?>
<?php include '../components/navigation.php'; ?>

<div style="padding: 50px 0px 50px 0px;">
    <div class="container">
        
        You have opted out of the single email option. From now on, you will be sent a seperate email with a report for each group you are assigned to.
            
    </div>
</div>

<?php include '../components/footer.php'; ?>