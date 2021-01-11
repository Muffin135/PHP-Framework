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

// Sends a SQL query to database to get the table for all recipient, then saves the results
$sql = "SELECT * FROM group_reports WHERE id='" . $_GET["userid"] . "'";
$result = $conn->query($sql);

$row = $result->fetch_assoc();

$report_id = $row["id"];
$report_division = $row["division"];
$report_url = $row["report_url"];

?>

<?php include '../components/bootstrap.php'; ?>
<?php include '../components/jquery.php'; ?>
<?php include '../components/multiselect.php'; ?>
<?php include '../components/navigation.php'; ?>

<link rel="stylesheet" href="../common/css/forms.css">

<div style="padding: 50px 0px 50px 0px;">
    <div class="container">

    <form class="form-horizontal" method="POST" action="../email/report_update_sql_pass.php">

        <span style="font-weight:bold;">Edit Report for <?php echo $report_division?></span>

        <div style="display:none;" class="form-group">
            <label for="exampleFormControlTextarea1" class="col-sm-2 col-form-label">Report ID:</label>
            <div class="col-sm-12">
                <textarea name="id" class="form-control form-gray-bg" id="exampleFormControlTextarea1" rows="1" readonly><?php echo $report_id?></textarea>
            </div>
        </div>

        <div style="display:none;" class="form-group">
            <label for="colFormLabel" class="col-sm-4 col-form-label">Group for Report:</label>
            <div class="col-sm-12">
                <div class="form-gray-bg">
                <select id="example-getting-started" name="division">
                    <option selected value="<?php echo $report_division ?>"><?php echo $report_division ?></option>
                </select>
                </div>
            </div>
            </div>

            <div class="form-group">
            <label for="exampleFormControlTextarea1" class="col-sm-2 col-form-label">URL to <?php echo $report_division?> Report:</label>
            <div class="col-sm-12">
                <textarea name="group_url" class="form-control form-gray-bg" id="exampleFormControlTextarea1" rows="3"><?php echo $report_url?></textarea>
            </div>
            </div>

            <div class="form-group">
            <div class="col-sm-10">
                <button type="submit" class="btn btn-default" id="submit-btn">Update Group Report</button>
            </div>
            </div>

            </form>
        </div>

</div>

<?php include '../components/footer.php'; ?>

