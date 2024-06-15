<?php
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM users WHERE username = ? AND role = 'admin'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        
        if (password_verify($password, $admin['password'])) {
            $_SESSION['user_id'] = $admin['id'];
            $_SESSION['role'] = $admin['role'];
            header("Location: dashboard.html");
            exit();
        } else {
            $error = "Invalid password";
        }
    } else {
        $error = "No admin found with that username";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <header>
        <h1>Admin Login</h1>
    </header>
    <main>
        <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
        <form action="login.php" method="post">
            <label for="username">Username</label>
            <input type="text" name="username" required>
            <br>
            <label for="password">Password</label>
            <input type="password" name="password" required>
            <br>
            <button type="submit">Login</button>
        </form>
    </main>
    <footer>
        <p>&copy; 2024 Web Monitor. All rights reserved.</p>
    </footer>
</body>
</html>
