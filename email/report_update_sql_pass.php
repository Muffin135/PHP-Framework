<?php
    //try-catch - form not found

    // Gets user data from form on index.php
    $report_data = $_POST;

    // Passes data to confirmation page
    include "../api/update_report.php";

    // first run debug, then formatted for confiramtion screen
    // Write a SQL query to get the list of emails that were sent to
    //instead of message=Success we want to render each person that got an email

    /*
    $custom_message = $data_from_user["email-body"];

    $newURL = "/test.php?message=".$custom_message;

    header('Location: '.$newURL);
    */
?>