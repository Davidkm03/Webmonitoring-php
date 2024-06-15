<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user's sites
$sql = "SELECT * FROM sites WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$sites = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <header>
        <h1>User Dashboard</h1>
    </header>
    <main>
        <h2>Welcome, User</h2>
        <h3>Your Monitored Sites</h3>
        <table>
            <thead>
                <tr>
                    <th>URL</th>
                    <th>Status</th>
                    <th>Last Checked</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sites as $site) { ?>
                    <tr>
                        <td><?php echo $site['url']; ?></td>
                        <td><?php echo $site['status']; ?></td>
                        <td><?php echo $site['last_checked']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <form action="add_site.php" method="post">
            <label for="url">Add New Site</label>
            <input type="url" name="url" required>
            <button type="submit">Add</button>
        </form>
    </main>
    <footer>
        <p>&copy; 2024 Web Monitor. All rights reserved.</p>
    </footer>
</body>
</html>
