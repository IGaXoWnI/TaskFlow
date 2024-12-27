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
            <a href="new-task.php" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-600">
                    + New Task
                </a>
                <a href="my-tasks.php" 
                   class="text-white hover:text-gray-200">
                    My Tasks
                </a>
                <a href="logout.php" 
                   class="text-white hover:text-gray-200">
                    Exit
                </a>

                <span class="text-white">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
            </div>
        </div>
    </div>
</nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- To Do Column -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-4 bg-gray-50 rounded-t-lg border-b">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <span class="h-2 w-2 bg-blue-400 rounded-full mr-2"></span>
                        À faire
                    </h2>
                </div>
                <div class="p-4 space-y-4">
                    <?php foreach ($allTasks as $task): ?>
                        <?php if ($task->getStatus() === 'todo'): ?>
                            <div class="bg-white border rounded-lg p-4 hover:shadow-md transition-shadow">
                                <?php 
                                $borderColor = match($task->getType()) {
                                    'bug' => 'border-red-500',
                                    'feature' => 'border-green-500',
                                    default => 'border-gray-200'
                                };
                                ?>
                                <div class="border-l-4 <?= $borderColor ?> pl-3">
                                    <h3 class="font-medium text-gray-900"><?= htmlspecialchars($task->getTitle()) ?></h3>
                                    <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($task->getDescription()) ?></p>
                                    <?php if ($task->getAssignee()): ?>
                                        <div class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <?= htmlspecialchars($task->getAssignee()) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- In Progress Column -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-4 bg-gray-50 rounded-t-lg border-b">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <span class="h-2 w-2 bg-yellow-400 rounded-full mr-2"></span>
                        En cours
                    </h2>
                </div>
                <div class="p-4 space-y-4">
                    <?php foreach ($allTasks as $task): ?>
                        <?php if ($task->getStatus() === 'in_progress'): ?>
                            <div class="bg-white border rounded-lg p-4 hover:shadow-md transition-shadow">
                                <?php 
                                $borderColor = match($task->getType()) {
                                    'bug' => 'border-red-500',
                                    'feature' => 'border-green-500',
                                    default => 'border-gray-200'
                                };
                                ?>
                                <div class="border-l-4 <?= $borderColor ?> pl-3">
                                    <h3 class="font-medium text-gray-900"><?= htmlspecialchars($task->getTitle()) ?></h3>
                                    <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($task->getDescription()) ?></p>
                                    <?php if ($task->getAssignee()): ?>
                                        <div class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <?= htmlspecialchars($task->getAssignee()) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Done Column -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-4 bg-gray-50 rounded-t-lg border-b">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <span class="h-2 w-2 bg-green-400 rounded-full mr-2"></span>
                        Terminé
                    </h2>
                </div>
                <div class="p-4 space-y-4">
                    <?php foreach ($allTasks as $task): ?>
                        <?php if ($task->getStatus() === 'done'): ?>
                            <div class="bg-white border rounded-lg p-4 hover:shadow-md transition-shadow">
                                <?php 
                                $borderColor = match($task->getType()) {
                                    'bug' => 'border-red-500',
                                    'feature' => 'border-green-500',
                                    default => 'border-gray-200'
                                };
                                ?>
                                <div class="border-l-4 <?= $borderColor ?> pl-3">
                                    <h3 class="font-medium text-gray-900"><?= htmlspecialchars($task->getTitle()) ?></h3>
                                    <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($task->getDescription()) ?></p>
                                    <?php if ($task->getAssignee()): ?>
                                        <div class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <?= htmlspecialchars($task->getAssignee()) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>
</body>
</html>