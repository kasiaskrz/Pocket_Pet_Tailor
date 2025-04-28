<?php
// Start the session
session_start();

// Destroy all session data
session_unset();

// Destroy the session
session_destroy();

// Redirect the user to the login page after logout
header("Location: home.php"); 
exit();
?>
