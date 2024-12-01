<?php
session_start();
include 'db_conn.php';

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Retrieve user_id from session if set
$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;

// Fetch income
$query = "SELECT income_id, date, description, category_id, amount FROM income WHERE user_id = '$user_id' ORDER BY date DESC";
$result = $conn->query($query);

// Handle income deletion
if (isset($_POST['delete_income'])) {
    $income_id = $_POST['income_id'];
    $query = "DELETE FROM income WHERE income_id = '$income_id' AND user_id = '$user_id'";
    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Income deleted successfully";
        // Refresh the page after deletion
        header("Location: income.php");
        exit;
    } else {
        $_SESSION['error'] = "Error deleting income: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Income Tracker</title>
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
            <h1>Income Tracker</h1>
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
                            echo "<a href='edit_income.php?income_id=" . $row['income_id'] . "'>Edit</a> ";
                            // Delete form
                            echo "<form method='post' style='display:inline-block;'>";
                            echo "<input type='hidden' name='income_id' value='" . $row['income_id'] . "'>";
                            echo "<button type='submit' name='delete_income'>Delete</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No income found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <a href="add_income.php" class="button">Add New Income</a>
            <a href="logout.php" class="button">Logout</a>
        </div>
    </div>
</body>
</html>
