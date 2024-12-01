<?php
session_start();
include 'db_conn.php';

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Check if income ID is provided
if (!isset($_GET["income_id"])) {
    echo "Income ID not provided.";
    exit;
}

// Retrieve user ID from session
$user_id = $_SESSION["user_id"];

// Retrieve income details
$income_id = $_GET["income_id"];
$query = "SELECT * FROM income WHERE income_id = '$income_id' AND user_id = '$user_id'";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    echo "Income not found or does not belong to you.";
    exit;
}

$row = $result->fetch_assoc();

$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST["date"];
    $description = $_POST["description"];
    $category_id = $_POST["category_id"];
    $amount = $_POST["amount"];

    // Validate if category_id exists in income_categories table
    $check_category_query = "SELECT * FROM income_categories WHERE id = '$category_id'";
    $check_category_result = $conn->query($check_category_query);
    if ($check_category_result->num_rows == 0) {
        $message = "Category ID does not exist.";
    } else {
        // Update income record
        $update_query = "UPDATE income SET date = '$date', description = '$description', category_id = '$category_id', amount = '$amount' WHERE income_id = '$income_id'";
        if ($conn->query($update_query) === TRUE) {
            $message = "Income updated successfully";
        } else {
            $message = "Error updating income: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Income</title>
    <link rel="stylesheet" href="../style/edit.css">   
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
            <h1>Edit Income</h1>
            <a href="income.php" class="back-link">Back</a>
            <?php if ($message): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>
            <form method="post">
                <div>
                    <label>Date</label>
                    <input type="date" name="date" value="<?php echo $row['date']; ?>" required>
                </div>
                <div>
                    <label>Description</label>
                    <input type="text" name="description" value="<?php echo $row['description']; ?>" required>
                </div>
                <div>
                    <label>Category</label>
                    <select name="category_id" required>
                        <!-- Retrieve and populate categories dynamically -->
                        <?php
                        $categories_query = "SELECT * FROM income_categories";
                        $categories_result = $conn->query($categories_query);
                        while ($category = $categories_result->fetch_assoc()) {
                            echo "<option value='" . $category['id'] . "' ";
                            if ($category['id'] == $row['category_id']) {
                                echo "selected";
                            }
                            echo ">" . $category['category_name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label>Amount</label>
                    <input type="number" name="amount" value="<?php echo $row['amount']; ?>" min="0" step="0.01" required>
                </div>
                <button type="submit">Update Income</button>
            </form>
            <a href="dashboard.php" class="button">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
