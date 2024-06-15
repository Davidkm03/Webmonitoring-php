<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch all sites
$sql = "SELECT sites.*, users.username FROM sites JOIN users ON sites.user_id = users.id";
$result = $conn->query($sql);
$sites = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
    </header>
    <main>
        <h2>Welcome, Admin</h2>
        <h3>All Monitored Sites</h3>
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>URL</th>
                    <th>Status</th>
                    <th>Last Checked</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sites as $site) { ?>
                    <tr>
                        <td><?php echo $site['username']; ?></td>
                        <td><?php echo $site['url']; ?></td>
                        <td><?php echo $site['status']; ?></td>
                        <td><?php echo $site['last_checked']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>
    <footer>
        <p>&copy; 2024 Web Monitor. All rights reserved.</p>
    </footer>
</body>
</html>
