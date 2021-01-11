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
$sql = "SELECT * FROM recipients WHERE id='" . $_GET["userid"] . "'";
$result = $conn->query($sql);

$row = $result->fetch_assoc();

$contact_first = $row["first_name"];
$contact_last = $row["last_name"];
$contact_id = $row["id"];
$contact_division = $row["division"];
$contact_email = $row["email"];
$contact_type = $row["web_contact"];
$contact_opt = $row["single_email"];

?>

<?php include '../components/bootstrap.php'; ?>
<?php include '../components/jquery.php'; ?>
<?php include '../components/multiselect.php'; ?>
<?php include '../components/navigation.php'; ?>

<link rel="stylesheet" href="../common/css/forms.css">

<div style="padding: 50px 0px 50px 0px;">
    <div class="container">

    <form class="form-horizontal" method="POST" action="../email/contact_update_sql_pass.php">

        <span style="font-weight:bold;">Edit Info for <?php echo $contact_first . " " . $contact_last . " (" . $contact_division . ")"?></span>

        <div style="display:none;" class="form-group">
            <label for="exampleFormControlTextarea1" class="col-sm-2 col-form-label">Report ID:</label>
            <div class="col-sm-12">
                <textarea name="id" class="form-control form-gray-bg" id="exampleFormControlTextarea1" rows="1" readonly><?php echo $contact_id?></textarea>
            </div>
        </div>

        <div style="display:none;" class="form-group">
            <label for="colFormLabel" class="col-sm-4 col-form-label">Group for Report:</label>
            <div class="col-sm-12">
                <div class="form-gray-bg">
                <select id="example-getting-started" name="division">
                    <option selected value="<?php echo $contact_division ?>"><?php echo $contact_division ?></option>
                </select>
                </div>
            </div>
            </div>

            <div class="form-group">
            <label for="exampleFormControlTextarea1" class="col-sm-2 col-form-label">Contact's Email:</label>
            <div class="col-sm-12">
                <textarea name="email" class="form-control form-gray-bg" id="exampleFormControlTextarea1" rows="1"><?php echo $contact_email?></textarea>
            </div>
            </div>

            <div class="form-group">
            <label for="colFormLabel" class="col-sm-4 col-form-label">Contact's Type:</label>
            <div class="col-sm-12">
                <div class="form-gray-bg">
                <select id="example-getting-started" name="web_contact">
                    <option <?php if ($contact_type == "Primary") {echo "selected";}?> value="Primary">Primary</option>
                    <option <?php if ($contact_type == "Secondary") {echo "selected";}?> value="Secondary">Secondary</option>
                    <option <?php if ($contact_type == "Business") {echo "selected";}?> value="Business">Business</option>
                </select>
                </div>
            </div>
            </div>

            <div class="form-group">
            <label for="colFormLabel" class="col-sm-4 col-form-label">Single Email Opt-In:</label>
            <div class="col-sm-12">
                <div class="form-gray-bg">
                <select id="example-getting-started" name="single_email">
                    <option <?php if ($contact_opt == "Yes") {echo "selected";}?> value="Yes">Yes</option>
                    <option <?php if ($contact_opt == "No") {echo "selected";}?> value="No">No</option>
                </select>
                </div>
            </div>
            </div>

            <div class="form-group">
            <div class="col-sm-10">
                <button type="submit" class="btn btn-default" id="submit-btn">Update Contact</button>
            </div>
            </div>

            </form>
        </div>

</div>

<?php include '../components/footer.php'; ?>

