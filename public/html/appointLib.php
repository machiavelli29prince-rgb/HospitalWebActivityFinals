<?php

require_once('db.php');
require_once(__DIR__ . '/helpers/Mailer.php');

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

class AuthController
{
    private $mailer;

    public function __construct()
    {
        $this->mailer = new MailerHelper();
    }

    public function register(string $name, string $email, string $password, string $role): bool
    {
        $user = new User();
        $registerSuccess = $user->register($name, $email, $password, $role);

        if ($registerSuccess) {
            $this->mailer->sendRegistrationConfirmation($name, $email);
        }

        return $registerSuccess;
    }

    public function login(string $email, string $password)
    {
        $user = new User();
        if (!$user->loadByEmail($email)) {
            return false;
        }

        if (!$user->verifyPassword($password)) {
            return false;
        }

        return $user;
    }
}

?>