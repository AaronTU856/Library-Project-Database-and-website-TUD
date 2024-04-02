<?php

// Check if a session is not already active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is already logged in, redirect them to the search page
if (isset($_SESSION['Username'])) {
    header("Location: search.php");
    exit();
}

// Include the database connection file
require_once "connection.php";

$error = ""; // Initialize an empty variable to store error messages

// Check if the login form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['Username'];
    $password = $_POST['Password'];

    // Prepare and execute the SQL statement to retrieve the stored password
    $stmt = $conn->prepare("SELECT Password FROM Users WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($storedPassword);

    // Check if the user is found
    if ($stmt->fetch()) {
        // Simple password comparison
        if ($password == $storedPassword) {
            // Set the session variable for the logged-in user
            $_SESSION["Username"] = $username;
            $stmt->close();

            // Redirect to the search page
            header("Location: search.php");
            exit(); // Make sure to exit after the header to prevent further execution
        } else {
            // Display an error message for incorrect credentials
            $error = "Incorrect Username or Password!";
        }
    } else {
        // Display an error message for user not found
        $error = "User not found. You must Register";
    }

    // Close the statement after using it
    $stmt->close();
}
?>
