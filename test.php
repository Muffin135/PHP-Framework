<?php include './components/bootstrap.php'; ?>
<?php include './components/navigation.php'; ?>

<div style="padding: 50px 0px 50px 0px;">
    <div class="container">
        Test result page:

        <?php
            if(isset($_GET['message'])){
                echo "YEEE WE SENT THIS MESSAGE: ".$_GET['message']; // display the message
                unset($_GET['message']); // clear the value so that it doesn't display again
            }
        ?>
    </div>
</div>

<?php include './components/footer.php'; ?>