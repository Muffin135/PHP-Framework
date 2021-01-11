<?php include './components/bootstrap.php'; ?>
<?php include './components/navigation.php'; ?>

<?php 
// Start a global session to get session variables
session_start(); 
?>


<div style="padding: 50px 0px 50px 0px;">
    <div class="container">
        <!-- Result page -->
        <?php 
            
            // Gets the array containing the queue of unsent emails from the global session
            $unsent_email_data = $_SESSION["unsent_emails"];

            //print_r($unsent_email_data);

            // Gets the result of the error token from the confirmation page

            // Temporary patch for false "Fail" bug

            
            // If the token reads as "Pass", it diplays a success message
            if($_GET["success"] == "Pass"){
                 
                unset($_GET['success']); 
                echo "Email(s) sucessfully sent. To send another email, please go to the Home tab.";
            }
            // If the token reads as fail, it displays a failure message
            else if ($_GET["success"] == "Fail"){

                unset($_GET['success']);

                echo "ERROR - Several email(s) were not able to send.";
                echo "<br><br>";
                echo "The follwing recipient(s) will not receive your email(s):";

                for ($index = 0; $index < count($unsent_email_data); $index++) {
                    echo "<br>- " . $unsent_email_data[$index];
                }

                echo "<br><br>Please try again.";

            }

        ?>
            
    </div>
</div>

<?php include './components/footer.php'; ?>