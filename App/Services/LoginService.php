<?php

declare(strict_types=1);
namespace App\Services;

use App\Data\ClientDAO;
use Exception;

class LoginService {
    private ClientDAO $clientDAO;

    public function __construct() {
        $this->clientDAO = new ClientDAO();
    }

    public function authenticate(string $email, string $password): bool {
        $user = $this->clientDAO->getUserAccountByEmail($email);
        
        if (!$user) {
            return false;
        }
        
        if ($user['disabled']) {
            throw new Exception("Account is disabled. Contacteer klantendienst.");
        }
        
        // Gebruik password_verify voor BCrypt wachtwoorden
        return password_verify($password, $user['paswoord']);
    }
}
