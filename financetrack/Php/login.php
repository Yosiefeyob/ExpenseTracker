<?php
session_start();
include 'db_conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Perform SQL query to fetch user data by username
    $query = "SELECT * FROM `users` WHERE `username` = '$username'";
    $result = $conn->query($query);

    if ($result && $result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        // Verify the hashed password (use 'password_hash' instead of 'password')
        if (password_verify($password, $row['password_hash'])) {
            // Password is correct
            $_SESSION["user_id"] = $row["id"];  // Assuming 'id' is the primary key column
            $_SESSION["username"] = $username;
            $_SESSION["loggedin"] = true;
            header("Location: dashboard.php");
            exit();
        } else {
            // Password is incorrect
            echo "Invalid username or password.";
        }

    } else {
        echo "Invalid username or password.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../style/Style.css">
</head>
<body>
    <div class="container">
        <div class="box form-box">
            <header>Login</header>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                <div class="field input">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" required>
                </div>

                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Login">
                </div>
                
                <div class="links">
                    Don't have an account? <a href="users.php">Sign up now</a>.
                </div>
            </form>
        </div>
    </div>    
</body>
</html>
