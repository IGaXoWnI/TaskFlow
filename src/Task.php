<?php

class Task {
    protected int $id;
    protected string $title;
    protected string $description;
    protected string $type;
    protected string $status;
    protected ?string $assignee = null;

    public function __construct(string $title, string $description, string $type = 'basic') {
        $this->title = $title;
        $this->description = $description;
        $this->type = $type;
        $this->status = 'todo';
    }


    public function getId(): int 
    { return $this->id; 
    }
    public function getTitle(): string
     { return $this->title; 
    }
    public function getDescription(): string
     { return $this->description;
     }
    public function getType(): string
     { return $this->type;
     }
    public function getStatus(): string
     { return $this->status;
     }
    public function getAssignee(): ?string
     { return $this->assignee; 
    }


    

    // Setters
    public function setStatus(string $status): void {
        $allowedStatuses = ['todo', 'in_progress', 'done'];
        if (in_array($status, $allowedStatuses)) {
            $this->status = $status;
        }
    }

    public function setAssignee(string $assignee): void {
        $this->assignee = $assignee;
    }
}