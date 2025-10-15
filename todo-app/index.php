<?php
session_start();
include 'db.php';

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    
    $res = $conn->query("SELECT * FROM users WHERE username='$username'");
    if ($res->num_rows > 0) {
        $user = $res->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | To-Do App</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome to To-Do App</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <h2>Login</h2>
            <div>
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn-success">Login</button>
        </form>
        
        <p>Don't have an account? <a href="register.php">Sign up here</a></p>
    </div>
</body>
</html>
