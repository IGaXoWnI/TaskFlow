<?php

class TaskManager {
    private array $tasks = [];
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->loadTasksFromDatabase();
    }

    private function loadTasksFromDatabase(): void {
        $stmt = $this->pdo->query("
            SELECT t.*, u.name as assignee_name 
            FROM tasks t 
            LEFT JOIN users u ON t.assignee_id = u.id 
            ORDER BY t.created_at DESC
        ");
        
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($tasks as $taskData) {
            $task = match($taskData['type']) {
                'bug' => new Bug(
                    $taskData['title'], 
                    $taskData['description'], 
                    $taskData['severity'] ?? 'medium'
                ),
                'feature' => new Feature(
                    $taskData['title'], 
                    $taskData['description'], 
                    $taskData['priority'] ?? 'medium'
                ),
                default => new Task($taskData['title'], $taskData['description'])
            };
            
            $task->setId($taskData['task_id']);
            $task->setStatus($taskData['status']);
            if (isset($taskData['assignee_name'])) {
                $task->setAssignee($taskData['assignee_name']);
            }
            
            $this->tasks[] = $task;
        }
    }

    public function createTask(string $title, string $description, string $type = 'basic'): Task {
        $task = match($type) {
            'bug' => new Bug($title, $description, $_POST['severity'] ?? 'medium'),
            'feature' => new Feature($title, $description, $_POST['priority'] ?? 'medium'),
            default => new Task($title, $description)
        };
        
        $status = $_POST['status'] ?? 'todo';
        $task->setStatus($status);
        
        $assigneeId = $_POST['assignee_id'] ?? null;
        
        $stmt = $this->pdo->prepare("
            INSERT INTO tasks (
                title, description, type, status, created_at, assignee_id, 
                severity, priority
            ) 
            VALUES (
                :title, :description, :type, :status, NOW(), :assignee_id,
                :severity, :priority
            )
        ");
        
        $stmt->execute([
            ':title' => $task->getTitle(),
            ':description' => $task->getDescription(),
            ':type' => $task->getType(),
            ':status' => $task->getStatus(),
            ':assignee_id' => $assigneeId,
            ':severity' => ($task instanceof Bug) ? $task->getSeverity() : null,
            ':priority' => ($task instanceof Feature) ? $task->getPriority() : null
        ]);
        
        return $task;
    }

    public function getAllTasks(): array {
        return $this->tasks;
    }

    public function updateTaskStatus(int $taskId, string $newStatus): void {
        $stmt = $this->pdo->prepare("UPDATE tasks SET status = :status WHERE task_id = :id");
        $stmt->execute([
            ':status' => $newStatus,
            ':id' => $taskId
        ]);
    }
} 