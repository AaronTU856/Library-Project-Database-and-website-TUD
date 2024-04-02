<?php

// Start the session
session_start();

// Include the database connection file
require_once "connection.php";

// Check if the user is already logged in, redirect them to the search page
if (isset($_SESSION['Username'])) {
    header("Location: search.php");
    exit();
}

$error = ""; // Initialize an empty variable to store error messages

// Process the login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['Username'];
    $password = $_POST['Password'];

    // Prepare and execute a query to fetch the stored password for the given username
    $stmt = $conn->prepare("SELECT Password FROM Users WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($storedPassword);

    // Check if the username exists
    if ($stmt->fetch()) {
        // Check if the entered password matches the stored password
        if (trim($password) == $storedPassword) {
            $_SESSION["Username"] = $username;
            $stmt->close();

            // Check if search.php exists
            $searchPage = "search.php";
            if (file_exists($searchPage)) {
                header("Location: " . $searchPage);
                exit(); // Make sure to exit after the header to prevent further execution
            } else {
                echo "Error: search.php not found!";
            }
        } else {
            $error = "Incorrect Username or Password!";
        }
    } else {
        $error = "User not found. You must Register";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Library</title>
</head>
<body>

<div class="header">
    <?php if (isset($_SESSION['Username'])) : ?>
        <!-- Display a welcome message if the user is logged in -->
        <h1>Welcome to the Library Website <?php echo htmlentities($_SESSION['Username']); ?>!</h1>
    <?php else : ?>
        <!-- Display a welcome message if the user is not logged in -->
        <h1>Welcome to the Library Website!</h1>
    <?php endif; ?>
    <a href="register.php">Register</a>
</div>
    
<h3>Please Login or Register to Library Account</h3>


<!-- Login form -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="Username">Username:</label>
    <input id="Username" name="Username" required type="text" />
    <label for="Password">Password:</label>
    <input id="Password" name="Password" required type="password" />
    <button type="submit">Login</button>
    <button onclick="location.href='register.php'">Register</button>
</form>

<?php if ($error) : ?>
    <!-- Display an error message if there is an error -->
    <div class="error-message">
        <?php echo htmlentities($error); ?>
    </div>
<?php endif; ?>
</body>
</html>

<?php
// Include the footer
include('footer.php');
$conn->close();  // Close the connection after using it in the index.php or search.php file
?>
