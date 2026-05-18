<?php
// Database connection details
$host = "localhost";    
$dbname = "hlomedia";   
$username = "root";    
$password = "REMOVED_DB_PASSWORD";        

// Create connection using MySQLi
$con = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}


?>
