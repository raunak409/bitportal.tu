<?php
session_start();
if (isset($_SESSION['user_id'])) { header("Location: portal.php"); exit(); }
require 'db.php';
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        // Check if username or email already exists
        $check = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $check->execute([$username, $email]);
        if ($check->fetch()) {
            $error = "Username or email already taken. Please choose another.";
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hash]);
            $success = "Account created successfully!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register – BIT Portal</title>
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
        <h2>Create Account</h2>
        <?php if ($error)   echo "<p class='error'>$error</p>"; ?>
        <?php if ($success) echo "<p class='success'>$success <a href='login.php'>Login now</a></p>"; ?>
        <?php if (!$success): ?>
        <form method="POST">
            <label>Username</label>
            <input type="text" name="username" placeholder="e.g. keshav123" required>
            <label>Email</label>
            <input type="email" name="email" placeholder="your@email.com" required>
            <label>Password</label>
            <input type="password" name="password" placeholder="min. 6 characters" required>
            <label>Confirm Password</label>
            <input type="password" name="confirm" placeholder="Confirm password" required>
            <button type="submit">Create Account</button>
        </form>
        <?php endif; ?>
        <p class="auth-link">Already have an account? <a href="login.php">Login</a></p>
        <p class="auth-link"><a href="index.php">← Back to Home</a></p>
    </div>
</div>
</body>
</html>