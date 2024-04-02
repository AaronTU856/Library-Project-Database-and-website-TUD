<?php
// Start session
session_start();

// Include database connection file
require_once "connection.php";

// Check if the user is not logged in, redirect them to the login page
if (!isset($_SESSION['Username'])) {
    header("Location: login.php");
    exit();
}

// Logic for reserving a book
if (isset($_GET['isbn'])) {
    $isbn = $_GET['isbn'];

    // Validate ISBN as a sanitized URL
    $filteredIsbn = filter_var($isbn, FILTER_SANITIZE_URL);

    if ($filteredIsbn === false) {
        // Redirect to an error page with an error message
        header("Location: search.php?message=Invalid ISBN");
        exit();
    }

    // Check if the book is already reserved
    $checkReservationQuery = "SELECT Reserved FROM books WHERE ISBN = ?";
    $checkReservationStmt = $conn->prepare($checkReservationQuery);

    if ($checkReservationStmt) {
        $checkReservationStmt->bind_param("s", $filteredIsbn);
        $checkReservationStmt->execute();

        $currentReservation = $checkReservationStmt->get_result()->fetch_assoc();
        $checkReservationStmt->close();

        // Check if the book is already reserved
        if ($currentReservation['Reserved'] == 1) {
            header("Location: search.php?message=The book is already reserved by someone else.");
            exit();
        } else {
            // Insert a new reservation into the Reservations table
            $insertReservationQuery = "INSERT INTO Reservations (Username, ISBN, ReservedDate) VALUES (?, ?, NOW())";
            $insertReservationStmt = $conn->prepare($insertReservationQuery);

            if ($insertReservationStmt) {
                $insertReservationStmt->bind_param("ss", $_SESSION['Username'], $filteredIsbn);
                $insertReservationStmt->execute();

                if (!$insertReservationStmt->error) {
                    // Update the Reserved column in the books table to 1
                    $updateReservationStatusQuery = "UPDATE books SET Reserved = 1 WHERE ISBN = ?";
                    $updateReservationStatusStmt = $conn->prepare($updateReservationStatusQuery);

                    if ($updateReservationStatusStmt) {
                        $updateReservationStatusStmt->bind_param("s", $filteredIsbn);
                        $updateReservationStatusStmt->execute();

                        if (!$updateReservationStatusStmt->error) {
                            header("Location: search.php?message=The book has been successfully reserved!");
                            exit();
                        } else {
                            header("Location: search.php?message=Error updating books table: " . $updateReservationStatusStmt->error);
                            exit();
                        }

                        $updateReservationStatusStmt->close();
                    } else {
                        header("Location: search.php?message=Error preparing update statement: " . $conn->error);
                        exit();
                    }
                } else {
                    // Handle the error in inserting into the Reservations table
                    header("Location: search.php?message=Error in reservation: " . $insertReservationStmt->error);
                    exit();
                }

                $insertReservationStmt->close();
            } else {
                header("Location: search.php?message=Error preparing insert statement: " . $conn->error);
                exit();
            }
        }
    } else {
        header("Location: search.php?message=Error preparing check reservation statement: " . $conn->error);
        exit();
    }
}

// Redirect back to the search page if the reservation was successful
header("Location: search.php");
exit();
?>

<?php
// Include the footer
include('footer.php');
// Close the connection after using it
$conn->close();
?>
