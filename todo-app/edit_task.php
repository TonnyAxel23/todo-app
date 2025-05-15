<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$id = (int)$_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $status = $conn->real_escape_string($_POST['status']);
    
    $conn->query("UPDATE tasks SET title='$title', status='$status' WHERE id=$id AND user_id=$user_id");
    $_SESSION['message'] = "Task updated successfully!";
    header("Location: dashboard.php");
    exit();
}

$task = $conn->query("SELECT * FROM tasks WHERE id=$id AND user_id=$user_id")->fetch_assoc();

if (!$task) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task | To-Do App</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Edit Task</h1>
            <a href="dashboard.php" class="btn-danger">Back to Dashboard</a>
        </div>
    </header>

    <div class="container">
        <form method="POST">
            <div>
                <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>" required>
            </div>
            <div>
                <select name="status">
                    <option value="pending" <?= $task['status']=='pending'?'selected':'' ?>>Pending</option>
                    <option value="done" <?= $task['status']=='done'?'selected':'' ?>>Done</option>
                </select>
            </div>
            <button type="submit">Update Task</button>
        </form>
    </div>
</body>
</html>