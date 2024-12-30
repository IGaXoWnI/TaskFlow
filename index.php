<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header('Location: login.php');
    exit();
}

require_once 'src/Task.php';
require_once 'src/Bug.php';
require_once 'src/Feature.php';
require_once 'src/TaskManager.php';
require_once 'con/con.Class.php';

$db = new database();
$pdo = $db->connect();
$taskManager = new TaskManager($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id']) && isset($_POST['new_status'])) {
    $taskManager->updateTaskStatus((int)$_POST['task_id'], $_POST['new_status']);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

$allTasks = $taskManager->getAllTasks();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-indigo-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="index.php" class="flex items-center">
                    <span class="text-white text-2xl font-bold">TaskFlow</span>
                </a>
                <div class="flex items-center space-x-4">
                    <a href="new-task.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-600">
                        + New Task
                    </a>
                    <!-- <a href="my-tasks.php" class="text-white hover:text-gray-200">My Tasks</a> -->
                    <a href="logout.php" class="text-white hover:text-gray-200">Exit</a>
                    <span class="text-white">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow">
                <div class="p-4 bg-gray-50 rounded-t-lg border-b">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <span class="h-2 w-2 bg-blue-400 rounded-full mr-2"></span>
                        To Do
                    </h2>
                </div>
                <div class="p-4 space-y-4">
                    <?php foreach ($allTasks as $task): ?>
                        <?php if ($task->getStatus() === 'todo'): ?>
                            <?php include 'templates/task-card.php'; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow">
                <div class="p-4 bg-gray-50 rounded-t-lg border-b">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <span class="h-2 w-2 bg-yellow-400 rounded-full mr-2"></span>
                        In Progress
                    </h2>
                </div>
                <div class="p-4 space-y-4">
                    <?php foreach ($allTasks as $task): ?>
                        <?php if ($task->getStatus() === 'in_progress'): ?>
                            <?php include 'templates/task-card.php'; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow">
                <div class="p-4 bg-gray-50 rounded-t-lg border-b">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <span class="h-2 w-2 bg-green-400 rounded-full mr-2"></span>
                        Done
                    </h2>
                </div>
                <div class="p-4 space-y-4">
                    <?php foreach ($allTasks as $task): ?>
                        <?php if ($task->getStatus() === 'done'): ?>
                            <?php include 'templates/task-card.php'; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>
</body>
</html>