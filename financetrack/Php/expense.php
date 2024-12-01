<?php
session_start();
include 'db_conn.php';

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Retrieve expenses for the logged-in user from the database
$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;

// Fetch expenses
$query = "SELECT expense_id, date, description, category_id, amount FROM expenses WHERE user_id = '$user_id' ORDER BY date DESC";
$result = $conn->query($query);

// Handle expense deletion
if (isset($_POST['delete_expense'])) {
    $expense_id = $_POST['expense_id'];
    $query = "DELETE FROM expenses WHERE expense_id = '$expense_id' AND user_id = '$user_id'";
    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Expense deleted successfully";
        // Refresh the page after deletion
        header("Location: expense.php");
        exit;
    } else {
        $_SESSION['error'] = "Error deleting expense: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Expense Tracker</title>
    <link rel="stylesheet" href="../style/dashboard.css">
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
            <h1>Expense Tracker</h1>
            <?php
            if (isset($_SESSION['message'])) {
                echo "<div class='message'>" . $_SESSION['message'] . "</div>";
                unset($_SESSION['message']);
            }
            if (isset($_SESSION['error'])) {
                echo "<div class='error'>" . $_SESSION['error'] . "</div>";
                unset($_SESSION['error']);
            }
            ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Category ID</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["date"] . "</td>";
                            echo "<td>" . $row["description"] . "</td>";
                            echo "<td>" . $row["category_id"] . "</td>";
                            echo "<td>" . $row["amount"] . "</td>";
                            echo "<td>";
                            // Edit link
                            echo "<a href='edit_expense.php?expense_id=" . $row['expense_id'] . "'>Edit</a> ";
                            // Delete form
                            echo "<form method='post' style='display:inline-block;'>";
                            echo "<input type='hidden' name='expense_id' value='" . $row['expense_id'] . "'>";
                            echo "<button type='submit' name='delete_expense'>Delete</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No expenses found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <a href="add_expense.php" class="button">Add New Expense</a>
            <a href="logout.php" class="button">Logout</a>
        </div>
    </div>
</body>
</html>
