<?php
class AppointmentManager {
    // Encapsulate configuration properties
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "rodencia"; // Matches your DB name
    private $conn;

    // Constructor: Automatically establishes connection when the object is created
    public function __construct() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die("Database connection failed: " . $this->conn->connect_error);
        }
    }

    // Method to safely sanitize inputs
    private function sanitize($data) {
        return $this->conn->real_escape_string(trim($data));
    }

    // Method to create a new appointment using OOP Prepared Statements (Secure)
    public function createAppointment($name, $email, $department, $time) {
        // Sanitize incoming data
        $sName = $this->sanitize($name);
        $sEmail = $this->sanitize($email);
        $sDept = $this->sanitize($department);
        $sTime = $this->sanitize($time);

        // SQL using your exact column names
        $sql = "INSERT INTO appointments (name, email, department, time) VALUES (?, ?, ?, ?)";
        
        // Prepare statement
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            // "ssss" means 4 strings
            $stmt->bind_param("ssss", $sName, $sEmail, $sDept, $sTime);
            
            $result = $stmt->execute();
            $stmt->close();
            return $result; // Returns true on success, false on failure
        }
        
        return false;
    }

    // Method to fetch appointments filtered by a specific department
    public function getAppointmentsByDepartment($department) {
        $sDept = $this->conn->real_escape_string(trim($department));
        
        // Query to select columns matching your exact database schema
        $sql = "SELECT name, email, time FROM appointments WHERE department = ? ORDER BY id DESC";
        
        $stmt = $this->conn->prepare($sql);
        $appointments = [];

        if ($stmt) {
            $stmt->bind_param("s", $sDept);
            $stmt->execute();
            $result = $stmt->get_result();
            
            // Loop through database rows and store them in an array
            while ($row = $result->fetch_assoc()) {
                $appointments[] = $row;
            }
            $stmt->close();
        }
        
        return $appointments; // Returns an array of appointments
    }

    // Destructor: Automatically closes connection when object is destroyed or script ends
    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>