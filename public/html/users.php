<?php
require_once("appointLib.php");

$post=new Appointment();
$posts=$post->getAppointments();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Document</title>
</head>
<body>
    <h1>Appointment List</h1>
    <a href="AddPost.php" class="btn btn-primary">Add Appointment</a>

    <table class="table table-striped-columns">
        <tr>
            <td>Id</td>
            <td>Name</td>
            <td>Email</td>
            <td>Department</td>
            <td>Time</td>
            <td>action</td>
        </tr>
        <?php foreach($posts as $post){ ?>
        <tr>
            <td><?php echo $post->id; ?></td>
            <td><?php echo $post->name; ?></td>
            <td><?php echo $post->email; ?></td>
            <td><?php echo $post->department; ?></td>
            <td><?php echo $post->time; ?></td>
            <td><a href=<?php echo "updateAppointment.php?id=".$post->id?> class="btn btn-primary">Edit</a>
            <br>
            <a href="javascript:void(0);" class="btn btn-danger" onclick="deleteAppointment(<?php echo $post->id; ?>)">Delete</a></td>
        </tr>
        <?php } ?>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <?php if(isset($_GET['added'])) { ?>
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: 'Appointment added successfully'
    });
    </script>
    <?php } ?>

    <?php if(isset($_GET['deleted'])) { ?>
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Deleted!',
        text: 'Appointment deleted successfully'
    });
    </script>
    <?php } ?>

    <script>
    function deleteAppointment(id){
        Swal.fire({
            title: "Are you sure?",
            text: "This action cannot be undone!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if(result.isConfirmed){
                window.location.href = "deleteAppointment.php?id=" + id;
            }
        });
    }
    </script>


    <?php if(isset($_GET['updated'])) { ?>
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Updated!',
        text: 'Appointment updated successfully'
    });
    </script>
    <?php } ?>
</body>
</html>