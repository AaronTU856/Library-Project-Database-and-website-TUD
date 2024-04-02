<?php

// Start a session
session_start();

// Include the database connection file
require_once "connection.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="header">
    <h1>Welcome to the Library</h1>
    <a href="index.php">Login</a>
</div>

<!-- Registration Form -->
<div class="form-container">
    <h2>Registration Page:</h2>

    <!-- Registration Instructions -->
    <h4>• Mobile numbers must contain 10 characters & numeric:
        <p>• Password must contain six characters:</p>
        • Choose a unique username:</h4>

    <?php
    // Use output buffering to prevent any output before the header function
    ob_start();

    // Check if the registration form is submitted
    if (
        isset($_POST['Username']) &&
        isset($_POST['Firstname']) &&
        isset($_POST['Surname']) &&
        isset($_POST['Password']) &&
        isset($_POST['ConfirmPassword']) &&
        isset($_POST['AddressLine']) &&
        isset($_POST['AddressLine2']) &&
        isset($_POST['City']) &&
        isset($_POST['Telephone']) &&
        isset($_POST['Mobile'])
    ) {
        // Sanitize and retrieve form data
        $n = htmlentities($_POST['Username']);
        $f = htmlentities($_POST['Firstname']);
        $s = htmlentities($_POST['Surname']);
        $Password = htmlentities($_POST['Password']);
        $ConfirmPassword = htmlentities($_POST['ConfirmPassword']);
        $a = htmlentities($_POST['AddressLine']);
        $b = htmlentities($_POST['AddressLine2']);
        $c = htmlentities($_POST['City']);
        $t = htmlentities($_POST['Telephone']);
        $e = htmlentities($_POST['Mobile']);

        // Check if the username is already taken
        $checkUsernameQuery = "SELECT COUNT(*) as count FROM Users WHERE Username = ?";
        $checkUsernameStmt = $conn->prepare($checkUsernameQuery);

        if ($checkUsernameStmt) {
            $checkUsernameStmt->bind_param("s", $n);
            $checkUsernameStmt->execute();
            $checkUsernameResult = $checkUsernameStmt->get_result();

            if ($checkUsernameResult) {
                $count = $checkUsernameResult->fetch_assoc()['count'];

                if ($count > 0) {
                    // Username already exists, display an error message
                    echo '<p>Username is already taken. Please choose a different username.</p>';
                    $checkUsernameStmt->close();
                    exit();
                }
            } else {
                echo "Error checking username existence: " . $checkUsernameStmt->error;
                $checkUsernameStmt->close();
                exit();
            }

            $checkUsernameStmt->close();
        } else {
            echo "Error in username existence check preparation: " . $conn->error;
            exit();
        }

        // Check if password and confirm password match
        if ($ConfirmPassword !== $Password) {
            echo '<p>Password and Confirm Password do not match!! <a href="register.php">Go back to registration page</a></p>';
            ob_end_flush(); // Flush the output buffer and turn off output buffering
            exit();
        }

        // Validate password exactly 6 characters
        if (strlen($Password) !== 6) {
            echo '<p>Invalid Password!! Password must be exactly 6 characters in length. <a href="register.php">Go back to registration page</a></p>';
            ob_end_flush(); // Flush the output buffer and turn off output buffering
            exit();
        }

        // Validate mobile phone number
        $Mobile = $_POST['Mobile'];
        if (!is_numeric($Mobile) || strlen($Mobile) !== 10) {
            echo '<p>Invalid mobile phone number!! It should be numeric and 10 characters in length. <a href="register.php">Go back to registration page</a></p>';
            ob_end_flush(); // Flush the output buffer and turn off output buffering
            exit();
        }

        // Prepare and execute the SQL statement to insert user data
        $stmt = $conn->prepare("INSERT INTO Users (Username, Firstname, Surname, Password, AddressLine, AddressLine2, City, Telephone, Mobile) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $n, $f, $s, str_pad($Password, 6), $a, $b, $c, $t, $e);

        if ($stmt->execute()) {
            // Display a success message and a link to the login page
            echo '<p>Record added successfully. <a href="index.php">Go to login page</a></p>';
        } else {
            // Display an error message if there's an issue with the SQL execution
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }

    // Flush the output buffer and turn off output buffering
    ob_end_flush();
    ?>

   <!-- Registration Form -->
   <form method="post" class="user-form">
        <label for="Username">Username<span class="required">*</span>:</label>
        <input type="text" name="Username" required>

        <label for="Firstname">First Name<span class="required">*</span>:</label>
        <input type="text" name="Firstname" required>

        <label for="Surname">Surname<span class="required">*</span>:</label>
        <input type="text" name="Surname" required>

        <label for="Password">Password<span class="required">*</span>:</label>
        <input type="Password" name="Password" required>

        <label for="ConfirmPassword">Confirm Password<span class="required">*</span>:</label>
        <input type="password" name="ConfirmPassword" required>

        <label for="AddressLine">Address Line<span class="required">*</span>:</label>
        <input type="text" name="AddressLine" required>

        <label for="AddressLine2">Address Line:</label>
        <input type="text" name="AddressLine2">

        <label for="City">City<span class="required">*</span>:</label>
        <input type="text" name="City" required>

        <label for="Telephone">Telephone<span class="required">*</span>:</label>
        <input type="text" name="Telephone" required>

        <label for="Mobile">Mobile<span class="required">*</span>:</label>
        <input type="number" name="Mobile" required>

        <p><input type="submit" value="Add New"/></p>
    </form>
</div>

</body>
</html>

<?php
// Include the footer
include('footer.php');
?>