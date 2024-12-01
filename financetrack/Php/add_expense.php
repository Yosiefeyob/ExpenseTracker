<?php
session_start();
include 'db_conn.php';

$message = "";

// Check if the form is submitted
if (isset($_POST['submit'])) {
    $date = $_POST['date'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];
    $amount = $_POST['amount'];
    $user_id = $_SESSION["user_id"];

    // Insert expense data into the database
    $sql = "INSERT INTO expenses (date, description, category_id, amount, user_id) VALUES ('$date', '$description', '$category_id', '$amount', '$user_id')";
    if (mysqli_query($conn, $sql)) {
        $message = "Expense added successfully";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Expense</title>
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
            <h1>Add Expense</h1>
            <a href="expense.php" class="back-link">Back</a>
            <?php if ($message): ?>
                <div class="message"><?php echo $message; ?></div>
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
                        <!-- Fetch and display categories dynamically -->
                        <?php
                        $categories_query = "SELECT * FROM expense_categories";
                        $categories_result = mysqli_query($conn, $categories_query);
                        while ($row = mysqli_fetch_assoc($categories_result)) {
                            echo "<option value='" . $row['id'] . "'>" . $row['category_name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label>Amount</label>
                    <input type="number" name="amount" min="0" step="0.01" required>
                </div>
                <button type="submit" name="submit">Add Expense</button>
            </form>
            <a href="dashboard.php" class="button">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
