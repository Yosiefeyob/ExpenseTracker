<?php
include 'db_conn.php'; // Ensure this file contains proper error handling for connection

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));
    $email = htmlspecialchars(trim($_POST['email']));

    // Input validation
    if (empty($username) || empty($password) || empty($email)) {
        echo "All fields are required.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL statement to insert user data
    $sql = "INSERT INTO `users` (username, password_hash, email) VALUES (?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("sss", $username, $hashed_password, $email);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Registration successful.";
        } else {
            echo "An error occurred. Please try again later.";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Failed to prepare the SQL statement.";
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../style/Style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;900&display=swap">
</head>
<body>
    <div class="container">
        <div class="box form-box">
            <header>Sign Up</header>
            <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="field input">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" required placeholder="Enter your username">
                </div>
                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required placeholder="Enter your password">
                </div>
                <div class="field input">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required placeholder="Enter your email">
                </div>
                <div class="field">
                    <input type="submit" class="btn" value="Sign Up">
                </div>
                <div class="links">
                    Already have an account? <a href="login.php">Login here</a>.
                </div>
            </form>
        </div>
    </div>
</body>
</html>
