<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "BookReservationDb";

// Create a new connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    // Terminate with an error message if the connection fails
    die("Connection failed: " . $conn->connect_error);
}

// Output a success message if the connection is successful
// echo "Connection successful";
// echo "<br>";

// Select the database
if (!$conn->select_db($dbname)) {
    // Terminate with an error message if the database selection fails
    die("Database selection failed: " . $conn->error);
}
?>
