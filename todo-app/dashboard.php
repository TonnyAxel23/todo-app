<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Handle task completion
if (isset($_GET['complete'])) {
    $task_id = (int)$_GET['complete'];
    $conn->query("UPDATE tasks SET status='done' WHERE id=$task_id AND user_id=$user_id");
}

// Handle task undo
if (isset($_GET['undo'])) {
    $task_id = (int)$_GET['undo'];
    $conn->query("UPDATE tasks SET status='pending' WHERE id=$task_id AND user_id=$user_id");
}

$result = $conn->query("SELECT * FROM tasks WHERE user_id=$user_id ORDER BY status, created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | To-Do App</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Welcome, <?= htmlspecialchars($username) ?></h1>
            <a href="logout.php" class="btn-danger">Logout</a>
        </div>
    </header>

    <div class="container">
        <form method="POST" action="add_task.php" class="task-form">
            <input type="text" name="title" placeholder="What needs to be done?" required>
            <button type="submit">Add Task</button>
        </form>

        <h2>Your Tasks</h2>
        
        <?php if ($result->num_rows > 0): ?>
            <ul class="task-list">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li class="task-item">
                        <span class="task-title"><?= htmlspecialchars($row['title']) ?></span>
                        <span class="task-status status-<?= $row['status'] ?>">
                            <?= ucfirst($row['status']) ?>
                        </span>
                        <div class="task-actions">
                            <?php if ($row['status'] == 'pending'): ?>
                                <a href="?complete=<?= $row['id'] ?>">Complete</a>
                            <?php else: ?>
                                <a href="?undo=<?= $row['id'] ?>">Undo</a>
                            <?php endif; ?>
                            <a href="edit_task.php?id=<?= $row['id'] ?>">Edit</a>
                            <a href="delete_task.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No tasks found. Add your first task above!</p>
        <?php endif; ?>
    </div>
</body>
</html>