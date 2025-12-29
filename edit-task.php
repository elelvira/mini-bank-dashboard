<?php
require 'db.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = (int)$_GET['id'];

// Получаем таск
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
$stmt->execute([$id]);
$task = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$task) {
    die("Task not found");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE tasks SET title = ?, type = ?, status = ? WHERE id = ?");
    $stmt->execute([
        $_POST['title'],
        $_POST['type'],
        $_POST['status'],
        $id
    ]);
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
</head>
<body>

<h2>Edit Task</h2>

<form method="post">
    <input name="title" value="<?= htmlspecialchars($task['title']) ?>" required>

    <select name="type">
        <option value="development" <?= $task['type'] == 'development' ? 'selected' : '' ?>>Development</option>
        <option value="support" <?= $task['type'] == 'support' ? 'selected' : '' ?>>Support</option>
    </select>

    <select name="status">
        <option value="new" <?= $task['status'] == 'new' ? 'selected' : '' ?>>New</option>
        <option value="in_progress" <?= $task['status'] == 'in_progress' ? 'selected' : '' ?>>In progress</option>
        <option value="done" <?= $task['status'] == 'done' ? 'selected' : '' ?>>Done</option>
    </select>

    <button type="submit">Save</button>
</form>

<a href="index.php">← Back</a>

</body>
</html>
