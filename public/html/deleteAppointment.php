<?php 
require_once("appointLib.php");

if(isset($_GET['id'])){
    $p=new Appointment();
    $p->setId($_GET['id']);
    $p->deleteAppointment();

        // echo"<script>alert('data deleted successfully'); document.location='PostList.php'</script>";
            header("Location: PostList.php?deleted=1");

}



?>