<?php

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include the database connection file
require_once "connection.php";

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['Username'])) {
    header("Location: login.php");
    exit();
}

// Initialize variables
$searchTitle = $searchAuthor = $searchCategory = "";
$result = null;

$resultsPerPage = 5;
$totalResults = 0;
$totalPages = 0;
$currentPage = 1;

// Check for reservation messages in the URL
$reservationMessage = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';

// Process form submission or handle pagination
if ($_SERVER["REQUEST_METHOD"] == "POST" || ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['page']))) {
    // Process form submission or handle pagination
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $searchTitle = $_POST['title'];
        $searchAuthor = $_POST['author'];
        $searchCategory = $_POST['category'];
    } elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['page'])) {
        $currentPage = (int)$_GET['page'];
    }

    // Build WHERE clause for SQL query
    $whereClause = "1";

    if (!empty($searchTitle)) {
        $whereClause .= " AND BookTitle LIKE '%$searchTitle%'";
    }

    if (!empty($searchAuthor)) {
        $whereClause .= " AND Author LIKE '%$searchAuthor%'";
    }

    if (!empty($searchCategory)) {
        $whereClause .= " AND CategoryID = $searchCategory";
    }

    // Count total results for pagination
    $countSql = "SELECT COUNT(*) as total FROM books WHERE $whereClause";
    $countResult = $conn->query($countSql);
    $totalCountRow = $countResult->fetch_assoc();
    $totalResults = $totalCountRow['total'];

    // Calculate total pages and ensure current page is within a valid range
    $totalPages = ceil($totalResults / $resultsPerPage);
    $currentPage = max(1, min($currentPage, $totalPages));

    // Calculate offset for LIMIT clause
    $offset = ($currentPage - 1) * $resultsPerPage;

    // Build main SQL query
    $sql = "SELECT ISBN, BookTitle, Author, Edition, Year, CategoryID, Reserved FROM books WHERE $whereClause LIMIT $offset, $resultsPerPage";

    // Execute main SQL query
    $result = $conn->query($sql);
}

// Include the header
include('header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library - Search Results</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Add the path to your CSS file -->
    <!-- Add any additional meta tags or linked stylesheets here -->
</head>
<body>

<!-- Display reservation message if present -->
<?php if (!empty($reservationMessage)): ?>
    <div class="message-box"><?php echo $reservationMessage; ?></div>
<?php endif; ?>

<div class="books-container">
    <h2>Search Results:</h2>
    <ul>
        <?php
        // Display search results
        if ($result && $result->num_rows > 0) {
            while ($book = $result->fetch_assoc()) {
                echo "<li>" . $book['BookTitle'] . ' by ' . $book['Author'];

                // Create reservation link
                $reserveLink = 'reservation_process.php?isbn=' . $book['ISBN'] . '&reserved=true';
                echo ' - <a href="' . $reserveLink . '">Reserve</a>';

                echo "</li>";
            }
        } else {
            echo "No books found.";
        }
        ?>
    </ul>

    <div class='pagination'>
        <?php
        // Display pagination links
        for ($page = 1; $page <= $totalPages; $page++) {
            $queryParams = array(
                'page' => $page,
                'title' => $searchTitle,
                'author' => $searchAuthor,
                'category' => $searchCategory
            );
            $queryString = http_build_query($queryParams);
            $url = 'search.php?' . $queryString;

            echo "<a href='$url'>$page</a>";
        }
        ?>
    </div>
</div>

<!-- Search form -->
<form action="search.php" method="post">
    <label for="title">Title:</label>
    <input type="text" name="title" value="<?php echo $searchTitle; ?>">

    <label for="author">Author:</label>
    <input type="text" name="author" value="<?php echo $searchAuthor; ?>">

    <label for="category">Category:</label>
    <select name="category">
        <option value="" <?php echo ($searchCategory === '') ? 'selected' : ''; ?>>All Categories</option>
        <?php
        // Display category options
        $categoriesQuery = "SELECT CategoryID, CategoryDetails FROM Category";
        $categoriesStmt = $conn->prepare($categoriesQuery);
        $categoriesStmt->execute();
        $categoriesResult = $categoriesStmt->get_result();

        while ($row = $categoriesResult->fetch_assoc()) {
            // Format CategoryID with leading zeros
            $formattedCategoryID = sprintf('%03d', $row['CategoryID']);

            // Set the selected attribute based on the current search category
            $selected = ($formattedCategoryID == $searchCategory) ? 'selected' : '';

            echo "<option value='" . $formattedCategoryID . "' $selected>" . $row['CategoryDetails'] . "</option>";
        }
        ?>
    </select>

    <button type="submit">Search</button>
</form>

<?php
// Include the footer
include('footer.php');
// Close the connection after using it
$conn->close();
?>

</body>
</html>
