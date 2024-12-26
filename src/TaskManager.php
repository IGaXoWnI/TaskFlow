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
                'bug' => new Bug($taskData['title'], $taskData['description']),
                'feature' => new Feature($taskData['title'], $taskData['description']),
                default => new Task($taskData['title'], $taskData['description'])
            };
            
            $task->setStatus($taskData['status']);
            if (isset($taskData['assignee_name'])) {
                $task->setAssignee($taskData['assignee_name']);
            }
            
            $this->tasks[] = $task;
        }
    }

    public function createTask(string $title, string $description, string $type = 'basic'): Task {
        $task = match($type) {
            'bug' => new Bug($title, $description),
            'feature' => new Feature($title, $description),
            default => new Task($title, $description)
        };
        
        $status = $_POST['status'] ?? 'todo';
        $task->setStatus($status);
        
        $assigneeId = $_POST['assignee_id'] ?? null;
        
        $stmt = $this->pdo->prepare("INSERT INTO tasks (title, description, type, status, created_at, assignee_id) 
                                   VALUES (:title, :description, :type, :status, NOW(), :assignee_id)");
        
        $stmt->execute([
            ':title' => $task->getTitle(),
            ':description' => $task->getDescription(),
            ':type' => $task->getType(),
            ':status' => $task->getStatus(),
            ':assignee_id' => $assigneeId
        ]);
        
        if ($assigneeId) {
            $stmt = $this->pdo->prepare("SELECT name FROM users WHERE id = ?");
            $stmt->execute([$assigneeId]);
            $assigneeName = $stmt->fetchColumn();
            if ($assigneeName) {
                $task->setAssignee($assigneeName);
            }
        }
        
        $this->tasks[] = $task;
        return $task;
    }

    public function getAllTasks(): array {
        return $this->tasks;
    }

    public function getTasksByAssignee(string $assignee): array {
        $assignedTasks = [];
        foreach ($this->tasks as $task) {
            if ($task->getAssignee() === $assignee) {
                $assignedTasks[] = $task;
            }
        }
        return $assignedTasks;
    }
} 