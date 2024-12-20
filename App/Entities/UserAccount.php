<?php

declare(strict_types=1);

namespace App\Entities;

class UserAccount
{
    private int $userId;
    private string $email;
    private string $password;
    private bool $disabled;

    public function __construct(int $userId, string $email, string $password, bool $disabled)
    {
        $this->userId = $userId;
        $this->email = $email;
        $this->password = $password;
        $this->disabled = $disabled;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function isDisabled(): bool
    {
        return $this->disabled;
    }
}
