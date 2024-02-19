<?php


session_start();

// Hardcoded username and password
$valid_username = '';
$valid_password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_username = $_POST['username'];
    $entered_password = $_POST['password'];

    // Check if the entered username and password match the hardcoded credentials
    if ($entered_username === $valid_username && $entered_password === $valid_password) {
        // Authentication successful
        $_SESSION['logged_in'] = true;
        echo "Login successful!";
        // Redirect to your application's main page or dashboard
        header("Location: index.php");
        exit();
    } else {
        echo "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
         body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .login-container {
            padding: 20px;
            width: max-content;
            height: max-content;
            text-align: center;
            position: absolute;
            top: 0; 
            left: 40%;
            /* transform: translateX(-50%); */
        }
    </style>
</head>
<body>
<div class="login-container">
        <h2>Login</h2>
        <form method="post" action="">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br><br>
            
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>
            
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>

