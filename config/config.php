<?php

// Database credentials
$host = 'localhost';
$db = 'jobportal';
$user = 'root';
$pass = '140605';

// Data Source Name (DSN)
$dsn = "mysql:host=$host;dbname=$db";

// Options for PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Set error mode to exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch results as associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Disable emulation of prepared statements
];

try {
    // Create a new PDO instance
    $pdo = new PDO($dsn, $user, $pass, $options);
    //echo 'Connected successfully!';
} catch(PDOException $e) {
    // Handle connection errors
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

define ('DEFAULT_PROFILE_PICTURE', 'http://localhost/job_bridge/uploads/default_profile_pic.png');
define('ERROR_EMAIL_EXISTS', '45000');


?>