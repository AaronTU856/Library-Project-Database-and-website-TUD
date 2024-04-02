<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library - Search Results</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Header section -->
<div class="header">
    <!-- Display a welcome message with the username if the user is logged in -->
    <h1>Welcome to the Library, <?php echo $_SESSION['Username']; ?>!</h1>
    
    <!-- Navigation links -->
    <a href="search.php">Search</a>
    
    <!-- Display a link to view reserved books for the logged-in user -->
    <a href="view_reserved_books.php"><?php echo $_SESSION['Username']; ?> Reserved Books</a>
    
    <!-- Logout link -->
    <a href="logout.php">Logout</a>
</div>

</body>
</html>
