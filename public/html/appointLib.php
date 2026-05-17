<?php

require_once("db.php");

class Appointment {

    private $id;
    private $name;
    private $email;
    private $department;
    private $time; // Aligned with your database column name

    private $db;

    function __construct(){
        $this->db = new Database();
    }

    // Getters
    function getId(){
        return $this->id;
    }
    function getName(){
        return $this->name;
    }
    function getEmail(){
        return $this->email;
    }
    function getDepartment(){
        return $this->department;
    }
    function getTime(){
        return $this->time;
    }

    // Setters
    function setId($id){
        $this->id = $id;
    }
    function setName($name){
        $this->name = $name;
    }
    function setEmail($email){
        $this->email = $email;
    }
    function setDepartment($department){
        $this->department = $department;
    }
    function setTime($time){
        $this->time = $time;
    }

    // Fetch all appointments
    function getAppointments(){
        $this->db->query("SELECT * FROM appointments ORDER BY id DESC");
        return $this->db->set();
    }

    // Fetch appointments for a specific department (Crucial for doctor.php)
    function getAppointmentsByDepartment($department){
        $this->db->query("SELECT * FROM appointments WHERE department = :department ORDER BY id DESC");
        $this->db->bind(":department", $department);
        return $this->db->set();
    }

    function getAppointment(){
        $this->db->query("SELECT * FROM appointments WHERE id = :id");
        $this->db->bind(":id", $this->id);
        return $this->db->single();
    }

    // FIXED: Corrected parameter binding tokens to match the SQL query
    function addAppointment(){
        $this->db->query("INSERT INTO appointments (name, email, department, time) VALUES (:name, :email, :department, :time)");
        $this->db->bind(":name", $this->name);
        $this->db->bind(":email", $this->email);
        $this->db->bind(":department", $this->department);
        $this->db->bind(":time", $this->time);
        return $this->db->execute();
    }  
    
    // FIXED: Table target changed from 'posts' to 'appointments'
    function deleteAppointment(){
        $this->db->query("DELETE FROM appointments WHERE id = :id");
        $this->db->bind(":id", $this->id);
        return $this->db->execute();
    }

    // FIXED: Adjusted to use 'time' column to match your phpMyAdmin setup
    function updateAppointment(){
        $this->db->query("UPDATE appointments SET name = :name, email = :email, department = :department, time = :time WHERE id = :id");
        $this->db->bind(":name", $this->name);
        $this->db->bind(":email", $this->email);
        $this->db->bind(":department", $this->department);
        $this->db->bind(":time", $this->time);
        $this->db->bind(":id", $this->id);
        return $this->db->execute();
    }
}
?>