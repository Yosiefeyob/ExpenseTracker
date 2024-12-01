<?php
session_start();
include 'db_conn.php';

$message = ""; // Initialize an empty message variable

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    $date = $_POST['date'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $category_id = $_POST['category_id'];
    $user_id = $_SESSION["user_id"];

    // Insert Income data into the database
    
    $sql = "INSERT INTO income (date, description, amount, category_id, user_id) VALUES ('$date', '$description', '$amount', '$category_id', '$user_id')";
    if (mysqli_query($conn, $sql)) {
        $message = "Income added successfully"; // Set success message
    } else {
        $message = "Error: " . mysqli_error($conn); // Set error message
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Income</title>
    <link rel="stylesheet" href="../style/income_expense.css">

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
            <h1>Add Income</h1>
            <a href="income.php" class="back-link">Back</a>
            <?php if ($message): ?>
                <div class="<?php echo strpos($message, 'Error') === false ? 'message' : 'error'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            <form method="post">
                <div>
                    <label>Date</label>
                    <input type="date" name="date" required>
                </div>
                <div>
                    <label>Description</label>
                    <input type="text" name="description" required>
                </div>
                <div>
                    <label>Category</label>
                    <select name="category_id" required>
                        <option value="">Select Category</option>
                        <?php
                        // Retrieve categories for income
                        $categories_query = "SELECT * FROM income_categories";
                        $categories_result = mysqli_query($conn, $categories_query);

                        // Check if categories are fetched
                        if (!$categories_result) {
                            echo "Error: " . mysqli_error($conn);
                        } else {
                            while ($row = mysqli_fetch_assoc($categories_result)) {
                                // Make sure the correct column names are used
                                echo "<option value='" . $row['id'] . "'>" . $row['category_name'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label>Amount</label>
                    <input type="number" name="amount" min="0" step="0.01" required>
                </div>
                <button type="submit" name="submit">Add Income</button>
            </form>
            <a href="dashboard.php" class="button">Back to Dashboard</a>
        </div>
    </div>
</body>

</html>