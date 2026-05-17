<?php

require_once("db.php");

// $db=new Database();

// echo $db->isConnected() ? "DB connected" : "DB not connected";

// echo "<br>DELA TORRE, Karl Armand C.";

class Appointment{

    private $id;
    private $name;
    private $email;
    private $department;
    private $appointment_time;

    private $db;

    function __construct(){
        $this->db=new Database();
    }
//getters
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
    function getAppointment_time(){
        return $this->appointment_time;
    }
//setters
    function setId($id){
        $this->id=$id;
    }
    function setName($name){
        $this->name=$name;
    }
    function setEmail($email){
        $this->email=$email;
    }
    function setDepartment($department){
        $this->department=$department;
    }
    function setAppointment_time($appointment_time){
        $this->appointment_time=$appointment_time;
    }

    function getAppointments(){
        $this->db->query("SELECT * FROM appointments");
        return $this->db->set();
    }
    function getAppointment(){
        $this->db->query("SELECT * FROM appointments WHERE id=:id");
        $this->db->bind(":id",$this->id);
        return $this->db->single();
    }

    function addAppointment(){
        $this->db->query("INSERT INTO appointments (name, email, department, appointment_time) VALUES (:name, :email, :department, :appointment_time)");
        $this->db->bind(":name",$this->name);
        $this->db->bind(":email",$this->email);
        $this->db->bind(":department",$this->department);
        $this->db->bind(":appointment_time",$this->appointment_time);
        $this->db->execute();
    }  
    
    function deleteAppointment(){
        $this->db->query("DELETE FROM posts WHERE id=:id");
        $this->db->bind(":id", $this->id);
        $this->db->execute();
    }

    function updateAppointment(){
        $this->db->query("UPDATE appointments SET name=:name, email=:email, department=:department, appointment_time=:appointment_time  WHERE id=:id");
        $this->db->bind(":name", $this->name);
        $this->db->bind(":email", $this->email);
        $this->db->bind(":department", $this->department);
        $this->db->bind(":appointment_time", $this->appointment_time);
        $this->db->bind(":id", $this->id);
        $this->db->execute();
    }

}

?>