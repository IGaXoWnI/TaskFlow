<?php
$borderColor = match($task->getType()) {
    'bug' => 'border-red-500',
    'feature' => 'border-green-500',
    default => 'border-gray-200'
};
?>
<div class="bg-white border rounded-lg p-4 hover:shadow-md transition-shadow">
    <div class="border-l-4 <?= $borderColor ?> pl-3">
        <h3 class="font-medium text-gray-900"><?= htmlspecialchars($task->getTitle()) ?></h3>
        <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($task->getDescription()) ?></p>
        
        <?php if ($task instanceof Bug): ?>
            <div class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mr-2">
                Severity: <?= htmlspecialchars($task->getSeverity()) ?>
            </div>
        <?php endif; ?>
        
        <?php if ($task instanceof Feature): ?>
            <div class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-2">
                Priority: <?= htmlspecialchars($task->getPriority()) ?>
            </div>
        <?php endif; ?>
        
        <?php if ($task->getAssignee()): ?>
            <div class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                <?= htmlspecialchars($task->getAssignee()) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="mt-2">
            <input type="hidden" name="task_id" value="<?= $task->getId() ?>">
            <select name="new_status" onchange="this.form.submit()" class="text-sm rounded-md border-gray-300">
                <option value="todo" <?= $task->getStatus() === 'todo' ? 'selected' : '' ?>>To Do</option>
                <option value="in_progress" <?= $task->getStatus() === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                <option value="done" <?= $task->getStatus() === 'done' ? 'selected' : '' ?>>Done</option>
            </select>
        </form>
    </div>
</div> 