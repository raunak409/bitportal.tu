<?php
session_start();
if (isset($_SESSION['user_id'])) { header("Location: portal.php"); exit(); }
require 'db.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];
        header("Location: portal.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – BIT Portal</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <script>
        // Persist theme across pages
        (function(){
            if(localStorage.getItem('theme')==='light') document.body.classList.add('light-mode');
        })();
    </script>
<div class="app">
    <div class="auth-box display-card">
        <h2>Welcome Back</h2>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <label>Email</label>
            <input type="email" name="email" placeholder="your@email.com" required>
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
            <button type="submit">Login</button>
        </form>
        <p class="auth-link">No account? <a href="register.php">Register here</a></p>
        <p class="auth-link"><a href="index.php">← Back to Home</a></p>
    </div>
</div>
</body>
</html>