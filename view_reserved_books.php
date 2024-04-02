<?php
// Start the session
session_start();

// Include the database connection file
require_once "connection.php";

// Check if the user is not logged in, redirect them to the login page
if (!isset($_SESSION['Username'])) {
    header("Location: login.php");
    exit();
}

// Get reserved books from the Reservations table
$sql = "SELECT Reservations.ISBN, BookTitle, Author, Edition, Year, ReservedDate
        FROM Reservations
        JOIN books ON Reservations.ISBN = books.ISBN
        WHERE Reservations.Username = ?";
$stmt = $conn->prepare($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library - Reserved Books</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="header">
    <h1>Welcome to the Library, <?php echo $_SESSION['Username']; ?>!</h1>
    <a href="logout.php">Logout</a>
    <a href="search.php">Search</a>
</div>

<?php
// Check if the prepared statement is successfully created
if ($stmt) {
    // Bind the parameters and execute the query
    $stmt->bind_param("s", $_SESSION['Username']);
    $stmt->execute();
    
    // Get the result set
    $result = $stmt->get_result();

    // Check if there are reserved books
    if ($result->num_rows > 0) {
        echo "<div class='reserved-box'>";
        echo "<h2 class='reserved-heading'>Reserved Books:</h2>";
        echo "<table class='reserved-table'>";
        echo "<tr><th>Book Title</th><th>Author</th><th>Edition</th><th>Year</th><th>Reserved Date</th><th>Action</th></tr>";

        // Display reserved books in a table
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['BookTitle'] . "</td>";
            echo "<td>" . $row['Author'] . "</td>";
            echo "<td>" . $row['Edition'] . "</td>";
            echo "<td>" . $row['Year'] . "</td>";
            echo "<td>" . $row['ReservedDate'] . "</td>";
            
            // Add the cancel reservation link
            $cancelLink = "cancel_reservation.php?cancel_isbn=" . $row['ISBN'];
            echo "<td><a href='$cancelLink'>Cancel Reservation</a></td>";

            echo "</tr>";
        }

        echo "</table>";
        echo "</div>";
    } else {
        echo "<p>No books are currently reserved.</p>";
    }

    // Close the prepared statement
    $stmt->close();
} else {
    // Display an error message if there's an issue with query preparation
    echo "Error in query preparation: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
<?php
// Include the footer
include('footer.php');
// Close the connection after using it
$conn->close();
?>

</body>
</html>
