<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$table = "";

// Sends a SQL query to database to get the table for all recipient, then saves the results
$sql = "SELECT id, division, report_url FROM group_reports";
$result = $conn->query($sql);

// Iterates through through each row of the table, where each row is a single recipient
if ($result->num_rows > 0) {
    
    // Gets the current row - current recipient
    while($row = $result->fetch_assoc()) {

        $profile_link = '<a style="font-weight:bold;" href=http://localhost:1234/api/update_url_1.php?userid=' . $row["id"] . '>EDIT</a>';

        $table_row = '<tr style = "border-bottom:1px solid black;height:3em;">' . "<td>" . $row["division"] . "</td>" . "<td>" . '<a style = "color:blue;text-decoration:underline" href="' . $row["report_url"] . '">' . $row["report_url"] . "</a>" . "</td>";
        $table_row .= "<td>" . $profile_link . "</td>";

        $table .= $table_row;
    }

} 

else {
    echo "0 results";
}

?>

<?php
include '../components/bootstrap.php';
include '../components/navigation.php';
?>

<link rel="stylesheet" href="../common/css/forms.css">

<div style="padding: 50px 0px 50px 0px;">
    <div class="container">

    <h2 style="font-weight:bold;">Upate Reports</h2>

    <table style="width:100%">
        <tr tr style = "border-bottom:1px solid black;height:3em;">
            <th>Divison</th>
            <th>URL for Group Report</th>
            <th></th>
        </tr>

        <?php echo $table?>
    </table>

    </div>
</div>

<?php include '../components/footer.php';?>