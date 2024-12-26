<?php

class Feature extends Task {
    private string $priority;

    public function __construct(string $title, string $description, string $priority = 'medium') {
        parent::__construct($title, $description, 'feature');
        $this->priority = $priority;
    }

    public function getPriority(): string {
        return $this->priority;
    }
    
} 