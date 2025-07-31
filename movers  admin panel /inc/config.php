<?php
// Start the session if it hasn't been started already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



try {
    // Create a new mysqli connection
    $host = 'localhost';
    $username = 'username';
    $password = 'password';
    $database = 'database';
    $service = new mysqli($host, $username, $password, $database);

    // Set the character set to utf8mb4
    $service->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    // Log the error message
    error_log($e->getMessage());

    // Show a user-friendly error message
    echo "Oops! Something went wrong. Please try again later.";
    exit;
}

$set = $service->query("SELECT * FROM `tbl_setting`")->fetch_assoc();
date_default_timezone_set($set['timezone']);

$maindata = 	$service->query("SELECT * FROM `lorrydoc`")->fetch_assoc();