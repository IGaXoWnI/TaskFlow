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

$stmt = $pdo->query("SELECT name FROM users ORDER BY name");
$users = $stmt->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['taskTitle'] ?? '';
    $description = $_POST['taskDescription'] ?? '';
    $type = $_POST['taskType'] ?? 'basic';
    $assignee = $_POST['taskAssignee'] ?? null;
    $status = $_POST['status'] ?? 'todo';

    $task = $taskManager->createTask($title, $description, $type);
    $task->setStatus($status);
    if ($assignee) {
        $task->setAssignee($assignee);
    }

    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskFlow - New Task</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <nav class="bg-indigo-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="index.php" class="flex items-center">
                    <span class="text-white text-2xl font-bold">TaskFlow</span>
                </a>
            </div>
        </div>
    </nav>

    <main class="max-w-2xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-6">Create New Task</h2>
            
            <form action="new-task.php" method="POST" class="space-y-4">
 
                <div>
                    <label for="taskTitle" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="taskTitle" id="taskTitle" required
                        class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

          
                <div>
                    <label for="taskDescription" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="taskDescription" id="taskDescription" rows="4" required
                        class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>

                <div>
                    <label for="taskType" class="block text-sm font-medium text-gray-700">Task Type</label>
                    <select name="taskType" id="taskType"
                        class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="basic">Basic Task</option>
                        <option value="bug">Bug</option>
                        <option value="feature">Feature</option>
                    </select>
                </div>



                <div id="featureFields" class="hidden">
                    <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                    <select name="priority" id="priority"
                        class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status"
                        class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="todo">To Do</option>
                        <option value="in_progress">In Progress</option>
                        <option value="done">Done</option>
                    </select>
                </div>


                <div>
                    <label for="taskAssignee" class="block text-sm font-medium text-gray-700">Assignee</label>
                    <select name="taskAssignee" id="taskAssignee"
                        class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select Assignee</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= htmlspecialchars($user) ?>" 
                                <?= ($user === $_SESSION['user_name']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($user) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Create Task
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        document.getElementById('taskType').addEventListener('change', function() {
            const bugFields = document.getElementById('bugFields');
            const featureFields = document.getElementById('featureFields');
            
            bugFields.classList.add('hidden');
            featureFields.classList.add('hidden');
            
            if (this.value === 'bug') {
                bugFields.classList.remove('hidden');
            } else if (this.value === 'feature') {
                featureFields.classList.remove('hidden');
            }
        });
    </script>
</body>
</html> 