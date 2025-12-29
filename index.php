<?php
require 'db.php';

// Фильтры из GET
$typeFilter = $_GET['type'] ?? '';
$statusFilter = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';

// Построим запрос с фильтрами
$sql = "SELECT * FROM tasks WHERE 1";
$params = [];

if ($typeFilter) {
    $sql .= " AND type = ?";
    $params[] = $typeFilter;
}

if ($statusFilter) {
    $sql .= " AND status = ?";
    $params[] = $statusFilter;
}

if ($search) {
    $sql .= " AND title LIKE ?";
    $params[] = "%$search%";
}

$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Функция для вывода "время назад"
function timeAgo($datetime) {
    $time = strtotime($datetime);
    $diff = time() - $time;

    if ($diff < 60) return $diff . " seconds ago";
    elseif ($diff < 3600) return floor($diff / 60) . " minutes ago";
    elseif ($diff < 86400) return floor($diff / 3600) . " hours ago";
    else return floor($diff / 86400) . " days ago";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mini Bank Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<h1>📊 Mini Bank Dashboard</h1>

<a href="add-task.php">➕ Add Task</a>

<form method="get" style="margin-top:20px; margin-bottom: 20px;">
    <input type="text" name="search" placeholder="Search title..." value="<?= htmlspecialchars($search) ?>">

    <select name="type">
        <option value="">All types</option>
        <option value="development" <?= $typeFilter == 'development' ? 'selected' : '' ?>>Development</option>
        <option value="support" <?= $typeFilter == 'support' ? 'selected' : '' ?>>Support</option>
    </select>

    <select name="status">
        <option value="">All statuses</option>
        <option value="new" <?= $statusFilter == 'new' ? 'selected' : '' ?>>New</option>
        <option value="in_progress" <?= $statusFilter == 'in_progress' ? 'selected' : '' ?>>In progress</option>
        <option value="done" <?= $statusFilter == 'done' ? 'selected' : '' ?>>Done</option>
    </select>

    <button type="submit">Filter</button>
</form>

<?php if (count($tasks) === 0): ?>
    <p>No tasks found.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Title</th>
            <th>Type</th>
            <th>Status</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($tasks as $task): ?>
            <tr>
                <td><?= htmlspecialchars($task['title']) ?></td>
                <td><?= htmlspecialchars($task['type']) ?></td>
                <td><?= htmlspecialchars($task['status']) ?></td>
                <td><?= timeAgo($task['created_at']) ?></td>
                <td>
                    <a href="edit-task.php?id=<?= $task['id'] ?>">✏️ Edit</a>
                    <a href="delete-task.php?id=<?= $task['id'] ?>" onclick="return confirm('Delete this task?')">🗑️ Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

</body>
</html>
