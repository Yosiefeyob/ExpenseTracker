<?php
session_start();
include 'db_conn.php';

// Fetch the data securely
$user_id = $_SESSION['user_id']; // Assuming user_id is set in the session after login

// Current month's expense
$current_expense_query = "SELECT SUM(amount) AS total_expense FROM expenses 
                          WHERE user_id = ? AND MONTH(date) = MONTH(CURRENT_DATE()) AND YEAR(date) = YEAR(CURRENT_DATE())";
$current_expense_stmt = $conn->prepare($current_expense_query);
$current_expense_stmt->bind_param("i", $user_id);
$current_expense_stmt->execute();
$current_expense_result = $current_expense_stmt->get_result();
$current_expense = $current_expense_result->fetch_assoc()['total_expense'] ?? 0;

// Current month's income
$current_income_query = "SELECT SUM(amount) AS total_income FROM income 
                         WHERE user_id = ? AND MONTH(date) = MONTH(CURRENT_DATE()) AND YEAR(date) = YEAR(CURRENT_DATE())";
$current_income_stmt = $conn->prepare($current_income_query);
$current_income_stmt->bind_param("i", $user_id);
$current_income_stmt->execute();
$current_income_result = $current_income_stmt->get_result();
$current_income = $current_income_result->fetch_assoc()['total_income'] ?? 0;

// Total expense
$total_expense_query = "SELECT SUM(amount) AS total_expense FROM expenses WHERE user_id = ?";
$total_expense_stmt = $conn->prepare($total_expense_query);
$total_expense_stmt->bind_param("i", $user_id);
$total_expense_stmt->execute();
$total_expense_result = $total_expense_stmt->get_result();
$total_expense = $total_expense_result->fetch_assoc()['total_expense'] ?? 0;

// Total income
$total_income_query = "SELECT SUM(amount) AS total_income FROM income WHERE user_id = ?";
$total_income_stmt = $conn->prepare($total_income_query);
$total_income_stmt->bind_param("i", $user_id);
$total_income_stmt->execute();
$total_income_result = $total_income_stmt->get_result();
$total_income = $total_income_result->fetch_assoc()['total_income'] ?? 0;

// Calculate the current balance
$current_balance = $total_income - $total_expense;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
            <h1>Dashboard</h1>
            <div class="dashboard-box">
                <div class="box">
                    <h3>Current Expense This Month</h3>
                    <p>$<?= number_format($current_expense, 2); ?></p>
                </div>
                <div class="box">
                    <h3>Current Income This Month</h3>
                    <p>$<?= number_format($current_income, 2); ?></p>
                </div>
            </div>
            <div class="dashboard-box">
                <div class="box">
                    <h3>Your Total Expense</h3>
                    <p>$<?= number_format($total_expense, 2); ?></p>
                </div>
                <div class="box">
                    <h3>Your Current Total Balance</h3>
                    <p>$<?= number_format($current_balance, 2); ?></p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>