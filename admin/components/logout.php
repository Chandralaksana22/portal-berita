<?php
    session_start(); // Start the session
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session

    // Redirect to the login page or any other page after logout
    header("Location: ../../login.php");
    exit();
?>
