<?php

require_once("db.php");

class Appointment {

    public $id;
    private $name;
    private $email;
    private $department;
    private $time; 
    private $user_id; 

    private $db;

    function __construct(){
        $this->db = new Database();
    }

    function getId(){ return $this->id; }
    function getName(){ return $this->name; }
    function getEmail(){ return $this->email; }
    function getDepartment(){ return $this->department; }
    function getTime(){ return $this->time; }
    function getUserId(){ return $this->user_id; }

    function setId($id){ $this->id = $id; }
    function setName($name){ $this->name = $name; }
    function setEmail($email){ $this->email = $email; }
    function setDepartment($department){ $this->department = $department; }
    function setTime($time){ $this->time = $time; }
    function setUserId($user_id){ $this->user_id = $user_id; }

    // Read: Fetches all appointments for the doctor view
    function getAppointments(){
        $this->db->query("SELECT * FROM appointments ORDER BY id DESC");
        return $this->db->set();
    }

    // Read: Fetches a single specific appointment record by ID
    function getAppointment(){
        $this->db->query("SELECT * FROM appointments WHERE id = :id");
        $this->db->bind(":id", $this->id);
        return $this->db->single();
    }

    // Create: Links and inserts a new appointment entry for a specific user
    function addAppointment(){
        $this->db->query("INSERT INTO appointments (user_id, name, email, department, time) VALUES (:user_id, :name, :email, :department, :time)");
        $this->db->bind(":user_id", $this->user_id);
        $this->db->bind(":name", $this->name);
        $this->db->bind(":email", $this->email);
        $this->db->bind(":department", $this->department);
        $this->db->bind(":time", $this->time);
        return $this->db->execute();
    }  
    
    // Delete: Permanently removes an appointment record from the database
    function deleteAppointment(){
        $this->db->query("DELETE FROM appointments WHERE id = :id");
        $this->db->bind(":id", $this->id);
        return $this->db->execute();
    }

    // Update: Modifies appointment information based on record ID
    function updateAppointment(){
        $this->db->query("UPDATE appointments SET name = :name, email = :email, department = :department, time = :time WHERE id = :id");
        $this->db->bind(":name", $this->name);
        $this->db->bind(":email", $this->email);
        $this->db->bind(":department", $this->department);
        $this->db->bind(":time", $this->time);
        $this->db->bind(":id", $this->id);
        return $this->db->execute();
    }

    // Read: Filters and pulls appointments specific to a clinic department
    function getAppointmentsByDepartment($department) {
        $this->db->query("SELECT * FROM appointments WHERE department = :department ORDER BY id DESC");
        $this->db->bind(":department", $department);
        return $this->db->set();
    }

    // Create: Secures user passwords using native crypt hashing algorithms
    public function registerUser($name, $email, $password, $role) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $this->db->query("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)");
        $this->db->bind(':name', $name);
        $this->db->bind(':email', $email);
        $this->db->bind(':password', $hashedPassword);
        $this->db->bind(':role', $role);
        return $this->db->execute();
    }

    // Read: Finds unique user metadata profile via a target email address
    public function getUserByEmail($email) {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    // Read: Isolates and pulls only the appointments assigned to a specific patient
    public function getAppointmentsByPatient($patientUserId) {
        $this->db->query("SELECT * FROM appointments WHERE user_id = :user_id ORDER BY id DESC");
        $this->db->bind(':user_id', $patientUserId);
        return $this->db->set();
    }
}
?>