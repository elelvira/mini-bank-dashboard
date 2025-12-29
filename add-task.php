<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare(
        "INSERT INTO tasks (title, type, status) VALUES (?, ?, ?)"
    );
    $stmt->execute([
        $_POST['title'],
        $_POST['type'],
        $_POST['status']
    ]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Task</title>
</head>
<body>

<h2>Add New Task</h2>

<form method="post">
    <input name="title" placeholder="Task title" required>

    <select name="type">
        <option value="development">Development</option>
        <option value="support">Support</option>
    </select>

    <select name="status">
        <option value="new">New</option>
        <option value="in_progress">In progress</option>
        <option value="done">Done</option>
    </select>

    <button type="submit">Add</button>
</form>

</body>
</html>

