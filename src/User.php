<?php

class User {
    private ?int $id;
    private string $name;
    private DateTime $createdAt;

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getName(): string {
        return $this->name;
    }

    public static function login(PDO $pdo, string $name): ?User {
        $user = new self($name);
        
        $stmt = $pdo->prepare("INSERT INTO users (name, created_at) 
                             VALUES (:name, NOW()) 
                             ON CONFLICT (name) DO NOTHING 
                             RETURNING id, created_at");
        $stmt->execute([':name' => $name]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $user->setId($result['id']);
        }
        
        return $user;
    }
} 