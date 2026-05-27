<?php

require_once __DIR__ . '/../utils/db.php';
require_once __DIR__ . '/../utils/Mailer.php';

class User
{
    private $db;
    public $id;
    public $name;
    public $email;
    public $role;
    private $passwordHash;

    public function __construct()
    {
        $this->db = new Database();
    }

    public static function emailExists(string $email): bool
    {
        $db = new Database();
        $db->query('SELECT id FROM users WHERE email = :email');
        $db->bind(':email', $email);
        return (bool) $db->single();
    }

    public function loadByEmail(string $email): bool
    {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();

        if (!$row) {
            return false;
        }

        $this->id = (int) $row->id;
        $this->name = $row->name;
        $this->email = $row->email;
        $this->role = $row->role;
        $this->passwordHash = $row->password;
        return true;
    }

    public function loadById(int $id): bool
    {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        $row = $this->db->single();

        if (!$row) {
            return false;
        }

        $this->id = (int) $row->id;
        $this->name = $row->name;
        $this->email = $row->email;
        $this->role = $row->role;
        $this->passwordHash = $row->password;
        return true;
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->passwordHash);
    }

    public function register(string $name, string $email, string $password, string $role): bool
    {
        if (self::emailExists($email)) {
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $this->db->query('INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)');
        $this->db->bind(':name', $name);
        $this->db->bind(':email', $email);
        $this->db->bind(':password', $hashedPassword);
        $this->db->bind(':role', $role);

        return $this->db->execute();
    }

    public function updatePassword(string $password): bool
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $this->db->query('UPDATE users SET password = :password WHERE id = :id');
        $this->db->bind(':password', $hashedPassword);
        $this->db->bind(':id', $this->id);

        return $this->db->execute();
    }
}

class Appointment
{
    private $db;
    public $id;
    public $user_id;
    public $name;
    public $email;
    public $department;
    public $time;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function fetchAll(): array
    {
        $this->db->query('SELECT * FROM appointments ORDER BY id DESC');
        return $this->db->set();
    }

    public function fetchById(int $id)
    {
        $this->db->query('SELECT * FROM appointments WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function fetchByUser(int $userId): array
    {
        $this->db->query('SELECT * FROM appointments WHERE user_id = :user_id ORDER BY id DESC');
        $this->db->bind(':user_id', $userId);
        return $this->db->set();
    }

    public function fetchByDepartment(string $department): array
    {
        $this->db->query('SELECT * FROM appointments WHERE department = :department ORDER BY id DESC');
        $this->db->bind(':department', $department);
        return $this->db->set();
    }

    public function create(): bool
    {
        $this->db->query('INSERT INTO appointments (user_id, name, email, department, time) VALUES (:user_id, :name, :email, :department, :time)');
        $this->db->bind(':user_id', $this->user_id);
        $this->db->bind(':name', $this->name);
        $this->db->bind(':email', $this->email);
        $this->db->bind(':department', $this->department);
        $this->db->bind(':time', $this->time);

        return $this->db->execute();
    }

    public function update(): bool
    {
        $this->db->query('UPDATE appointments SET name = :name, email = :email, department = :department, time = :time WHERE id = :id');
        $this->db->bind(':name', $this->name);
        $this->db->bind(':email', $this->email);
        $this->db->bind(':department', $this->department);
        $this->db->bind(':time', $this->time);
        $this->db->bind(':id', $this->id);

        return $this->db->execute();
    }

    public function delete(): bool
    {
        $this->db->query('DELETE FROM appointments WHERE id = :id');
        $this->db->bind(':id', $this->id);
        return $this->db->execute();
    }
}

class PasswordReset
{
    private $db;
    public $id;
    public $user_id;
    public $token;
    public $expires_at;

    public function __construct()
    {
        $this->db = new Database();
    }

    private function ensureTableExists(): void
    {
        $this->db->query(
            'CREATE TABLE IF NOT EXISTS password_resets (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                token VARCHAR(128) NOT NULL UNIQUE,
                expires_at DATETIME NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );
        $this->db->execute();
    }

    public function create(int $userId, string $token, string $expiresAt): bool
    {
        $this->ensureTableExists();
        $this->db->query('INSERT INTO password_resets (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':token', $token);
        $this->db->bind(':expires_at', $expiresAt);

        return $this->db->execute();
    }

    public function loadByToken(string $token): bool
    {
        $this->ensureTableExists();
        $this->db->query('SELECT * FROM password_resets WHERE token = :token LIMIT 1');
        $this->db->bind(':token', $token);
        $row = $this->db->single();

        if (!$row) {
            return false;
        }

        $this->id = (int) $row->id;
        $this->user_id = (int) $row->user_id;
        $this->token = $row->token;
        $this->expires_at = $row->expires_at;
        return true;
    }

    public function deleteByUserId(int $userId): bool
    {
        $this->ensureTableExists();
        $this->db->query('DELETE FROM password_resets WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    public function deleteByToken(string $token): bool
    {
        $this->ensureTableExists();
        $this->db->query('DELETE FROM password_resets WHERE token = :token');
        $this->db->bind(':token', $token);
        return $this->db->execute();
    }
}

class AuthController
{
    private $mailer;
    private $lastError = '';

    public function __construct()
    {
        $this->mailer = new MailerHelper();
    }

    public function getLastError(): string
    {
        return $this->lastError;
    }

    private function validatePassword(string $password): bool
    {
        $errors = [];

        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long.';
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Password must contain at least one uppercase character.';
        }
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'Password must contain at least one lowercase character.';
        }
        if (!preg_match('/\d/', $password)) {
            $errors[] = 'Password must contain at least one number.';
        }
        if (!preg_match('/[\W_]/', $password)) {
            $errors[] = 'Password must contain at least one special character.';
        }
        if (preg_match('/\s/', $password)) {
            $errors[] = 'Password may not contain spaces.';
        }
        if (count(array_unique(str_split($password))) < 5) {
            $errors[] = 'Password must include at least a few unique characters.';
        }

        if (!empty($errors)) {
            $this->lastError = implode(' ', $errors);
            return false;
        }

        return true;
    }

    private function buildBaseUrl(): string
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '/');

        if (in_array(basename($scriptDir), ['controllers', 'views'], true)) {
            $scriptDir = dirname($scriptDir);
        }

        return rtrim($scheme . '://' . $host . $scriptDir, '/');
    }

    public function register(string $name, string $email, string $password, string $role): bool
    {
        if (empty($name) || empty($email) || empty($password) || empty($role)) {
            $this->lastError = 'All registration fields are required.';
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->lastError = 'Please provide a valid email address.';
            return false;
        }

        if (!$this->validatePassword($password)) {
            return false;
        }

        $allowedRoles = ['patient', 'doctor'];
        if (!in_array($role, $allowedRoles, true)) {
            $role = 'patient';
        }

        $user = new User();
        if (User::emailExists($email)) {
            $this->lastError = 'This email is already registered in our system.';
            return false;
        }

        $registerSuccess = $user->register($name, $email, $password, $role);

        if ($registerSuccess) {
            $this->mailer->sendRegistrationConfirmation($name, $email);
        } else {
            $this->lastError = 'An error occurred while creating your account.';
        }

        return $registerSuccess;
    }

    public function login(string $email, string $password)
    {
        $user = new User();
        if (!$user->loadByEmail($email)) {
            $this->lastError = 'Invalid email or password combination.';
            return false;
        }

        if (!$user->verifyPassword($password)) {
            $this->lastError = 'Invalid email or password combination.';
            return false;
        }

        return $user;
    }

    public function requestPasswordReset(string $email): bool
    {
        $user = new User();
        if (!$user->loadByEmail($email)) {
            // Do not reveal whether the email exists.
            return true;
        }

        $token = bin2hex(random_bytes(16));
        $expiresAt = date('Y-m-d H:i:s', time() + 3600);

        $reset = new PasswordReset();
        $reset->deleteByUserId($user->id);
        $reset->create($user->id, $token, $expiresAt);

        $resetLink = $this->buildBaseUrl() . '/views/reset-password.php?token=' . urlencode($token);
        $subject = 'Rodencia Password Reset Request';
        $body = "<p>Hi " . htmlspecialchars($user->name, ENT_QUOTES, 'UTF-8') . ",</p>" .
            "<p>We received a request to reset your password. Click the link below to choose a new password. This link will expire in one hour.</p>" .
            "<p><a href=\"{$resetLink}\">Reset your password</a></p>" .
            "<p>If you did not request this change, please ignore this email.</p>" .
            "<p>Best regards,<br>Rodencia Hospital Team</p>";

        $this->mailer->sendEmail($user->email, $subject, $body);
        return true;
    }

    public function resetPassword(string $token, string $password): bool
    {
        if (!$this->validatePassword($password)) {
            return false;
        }

        $reset = new PasswordReset();
        if (!$reset->loadByToken($token)) {
            $this->lastError = 'Invalid or expired password reset token.';
            return false;
        }

        if (strtotime($reset->expires_at) < time()) {
            $reset->deleteByToken($token);
            $this->lastError = 'This password reset link has expired.';
            return false;
        }

        $user = new User();
        if (!$user->loadById($reset->user_id)) {
            $this->lastError = 'Unable to find the account for this reset request.';
            return false;
        }

        if (!$user->updatePassword($password)) {
            $this->lastError = 'Unable to update your password. Please try again.';
            return false;
        }

        $reset->deleteByToken($token);

        return true;
    }
}

?>