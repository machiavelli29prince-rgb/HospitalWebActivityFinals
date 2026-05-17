<?php
require_once("appointLib.php");

if(isset($_POST['save'])){
    $p=new Appointment();
    $p->setName($_POST['name']);
    $p->setEmail($_POST['email']);
    $p->setDepartment($_POST['department']);
    $p->setAppointment_time($_POST['appointment_time']);


    // $imagePath=null;
    // if(isset($_FILES['image']) && $_FILES['image']['error']==0){
    //     $filename=time() . "_" . basename($_FILES['image']['name']);
    //     $img_loc="uploads/posts/";

    //     $targetFile=$img_loc.$filename;
    //     $fileType=strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    //     $allowed=['jpg','jpeg','png','gif'];


    //     if(in_array($fileType,$allowed)){
    //         if(move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)){
    //             $imagePath=$targetFile;
    //         }
    //     }
    // }

    $p->addAppointment();

    // echo"<script>alert('data inserted successfully'); document.location='PostList.php'</script>";
    header("Location: PostList.php?added=1");
    

    }
?>