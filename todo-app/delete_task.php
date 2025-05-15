<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$id = (int)$_GET['id'];

$conn->query("DELETE FROM tasks WHERE id=$id AND user_id=$user_id");
$_SESSION['message'] = "Task deleted successfully!";
header("Location: dashboard.php");
exit();
?>