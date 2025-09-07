<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $user_id = $_SESSION['user_id'];
    
    $conn->query("INSERT INTO tasks (title, user_id) VALUES ('$title', $user_id)");
    $_SESSION['message'] = "Task added successfully!";
}

header("Location: dashboard.php");
exit();

?>

