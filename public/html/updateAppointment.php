<?php

require_once("appointLib.php");

$post=new Appointment();
$currentPost=null;

if(isset($_GET['id']) && !empty($_GET['id'])){
    $post->setId($_GET['id']);
    $currentPost=$post->getAppointment();

}else{
    echo"No Post ID";
}

if(isset($_POST['update'])){
    $post->setName($_POST['name']);
    $post->setEmail($_POST['email']);
    $post->setDepartment($_POST['department']);
    $post->setAppointment_time($_POST['appointment_time']);

    $post->updateAppointment();

    // echo "<script>alert('Post updated successfully!'); window.location.href='postlist.php';</script>";
    header("Location: PostList.php?updated=1");
}

?>

