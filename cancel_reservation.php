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

// Check if ISBN is provided in the URL for canceling reservation
if (isset($_GET['cancel_isbn'])) {
    $isbn = $_GET['cancel_isbn'];

    // Validate ISBN format (allowing alphanumeric characters)
    if (!preg_match('/^[a-zA-Z0-9-]+$/', $isbn)) {
        // Redirect to an error page with an error message
        header("Location: error.php?message=Invalid ISBN");
        exit();
    }

    // Cancel reservation
    $cancelReservationQuery = "DELETE FROM Reservations WHERE ISBN = ? AND Username = ?";
    $cancelReservationStmt = $conn->prepare($cancelReservationQuery);

    if (!$cancelReservationStmt) {
        die("Error preparing cancel reservation query: " . $conn->error);
    }

    $cancelReservationStmt->bind_param("ss", $isbn, $_SESSION['Username']);
    $cancelReservationStmt->execute();

    if ($cancelReservationStmt->error) {
        die("Error executing cancel reservation query: " . $cancelReservationStmt->error);
    }

    if ($cancelReservationStmt->affected_rows > 0) {
        // Reservation canceled successfully
        $updateReservationStatusQuery = "UPDATE books SET Reserved = 0 WHERE ISBN = ?";
        $updateReservationStatusStmt = $conn->prepare($updateReservationStatusQuery);

        if (!$updateReservationStatusStmt) {
            die("Error preparing update reservation status query: " . $conn->error);
        }

        $updateReservationStatusStmt->bind_param("s", $isbn);
        $updateReservationStatusStmt->execute();

        if ($updateReservationStatusStmt->error) {
            die("Error executing update reservation status query: " . $updateReservationStatusStmt->error);
        }

        if ($updateReservationStatusStmt->affected_rows > 0) {
            // Reservation status updated successfully
            header("Location: view_reserved_books.php");
            exit();
        } else {
            die("Error updating reservation status: " . $updateReservationStatusStmt->error);
        }

        $updateReservationStatusStmt->close();
    } else {
        die("Error canceling reservation: " . $cancelReservationStmt->error);
    }

    $cancelReservationStmt->close();
}

// Get reserved books for the current user
$username = $_SESSION['Username'];
$sql = "SELECT Reservations.ISBN, BookTitle, Author, ReservedDate FROM Reservations
        JOIN books ON Reservations.ISBN = books.ISBN
        WHERE Reservations.Username = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    // Display reserved books within a box
    echo "<div class='reserved-box'>";
    echo "<h2 class='reserved-heading'>Books Reserved:</h2>";
    echo "<ul class='reserved-list'>";
    while ($row = $result->fetch_assoc()) {
        $cancelLink = "cancel_reservation.php?cancel_isbn=" . $row['ISBN'];
        echo "<li>" . $row['BookTitle'] . ' by ' . $row['Author'] . ' (Reserved on ' . $row['ReservedDate'] . ') 
          <a href="' . $cancelLink . '">Cancel Reservation</a></li>';
    }

    echo "</ul>";
    echo "</div>";

    $stmt->close();
}

// Include the footer
include('footer.php');
?>

</body>
</html>
