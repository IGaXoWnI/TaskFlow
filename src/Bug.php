<?php

class Bug extends Task {
    private string $severity;

    public function __construct(string $title, string $description, string $severity = 'medium') {
        parent::__construct($title, $description, 'bug');
        $this->severity = $severity;
    }

    public function getSeverity(): string {
        return $this->severity;
    }
} 