<?php
session_start();
include 'db_conn.php';

$message = "";

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Handle adding new income category
if (isset($_POST['add_income_category'])) {
    $category_name = $_POST['category_name'];
    
    // Prevent SQL Injection by escaping input
    $category_name = mysqli_real_escape_string($conn, $category_name);

    // Insert into income_categories
    $income_query = "INSERT INTO income_categories (category_name) VALUES ('$category_name')";
    
    // Execute the query
    $income_result = mysqli_query($conn, $income_query);

    // Check if the insertion was successful
    if ($income_result) {
        $message = "Income category added successfully.";
    } else {
        $message = "Error adding income category: " . mysqli_error($conn);
    }
}

// Handle adding new expense category
if (isset($_POST['add_expense_category'])) {
    $category_name = $_POST['category_name'];
    
    // Prevent SQL Injection by escaping input
    $category_name = mysqli_real_escape_string($conn, $category_name);

    // Insert into expense_categories
    $expense_query = "INSERT INTO expense_categories (category_name) VALUES ('$category_name')";
    
    // Execute the query
    $expense_result = mysqli_query($conn, $expense_query);

    // Check if the insertion was successful
    if ($expense_result) {
        $message = "Expense category added successfully.";
    } else {
        $message = "Error adding expense category: " . mysqli_error($conn);
    }
}

// Handle income category deletion
if (isset($_GET['delete_income_category'])) {
    $category_id = $_GET['delete_income_category'];
    
    // Prevent SQL Injection
    $category_id = mysqli_real_escape_string($conn, $category_id);

    // Delete income category
    $delete_income_query = "DELETE FROM income_categories WHERE id = '$category_id'";
    $delete_income_result = mysqli_query($conn, $delete_income_query);

    if ($delete_income_result) {
        $message = "Income category deleted successfully.";
    } else {
        $message = "Error deleting income category: " . mysqli_error($conn);
    }
}

// Handle expense category deletion
if (isset($_GET['delete_expense_category'])) {
    $category_id = $_GET['delete_expense_category'];
    
    // Prevent SQL Injection
    $category_id = mysqli_real_escape_string($conn, $category_id);

    // Delete expense category
    $delete_expense_query = "DELETE FROM expense_categories WHERE id = '$category_id'";
    $delete_expense_result = mysqli_query($conn, $delete_expense_query);

    if ($delete_expense_result) {
        $message = "Expense category deleted successfully.";
    } else {
        $message = "Error deleting expense category: " . mysqli_error($conn);
    }
}


// Retrieve income categories from the database
$income_query = "SELECT * FROM income_categories";
$income_result = $conn->query($income_query);

// Retrieve expense categories from the database
$expense_query = "SELECT * FROM expense_categories";
$expense_result = $conn->query($expense_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="../style/categories.css">
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <h2>Menu</h2>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="expense.php">Expense</a></li>
                <li><a href="income.php">Income</a></li>
                <li><a href="categories.php">Categories</a></li>
            </ul>
        </div>
        <div class="right-panel">
            <h1>Manage Categories</h1>

            <!-- Display success or error message -->
            <?php if ($message): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>

            <!-- Add Income Category Form -->
            <h2>Add Income Category</h2>
            <form method="post">
                <div>
                    <label>Category Name</label>
                    <input type="text" name="category_name" placeholder="Enter new income category" required>
                </div>
                <button type="submit" name="add_income_category">Add Income Category</button>
            </form>

            <!-- Add Expense Category Form -->
            <h2>Add Expense Category</h2>
            <form method="post">
                <div>
                    <label>Category Name</label>
                    <input type="text" name="category_name" placeholder="Enter new expense category" required>
                </div>
                <button type="submit" name="add_expense_category">Add Expense Category</button>
            </form>

            <h2>Income Categories</h2>
            <ul>
                <?php
                if ($income_result->num_rows > 0) {
                    while ($row = $income_result->fetch_assoc()) {
                        echo "<li>" . $row["category_name"] . " 
                            <a href='?delete_income_category=" . $row["id"] . "' class='delete-link'>Delete</a></li>";
                    }
                } else {
                    echo "<li>No income categories available.</li>";
                }
                ?>
            </ul>

            <h2>Expense Categories</h2>
            <ul>
                <?php
                if ($expense_result->num_rows > 0) {
                    while ($row = $expense_result->fetch_assoc()) {
                        echo "<li>" . $row["category_name"] . " 
                            <a href='?delete_expense_category=" . $row["id"] . "' class='delete-link'>Delete</a></li>";
                    }
                } else {
                    echo "<li>No expense categories available.</li>";
                }
                ?>
            </ul>

            <a href="dashboard.php" class="back-link">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
