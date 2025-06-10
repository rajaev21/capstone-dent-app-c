<?php
session_start();
date_default_timezone_set('Asia/Manila');
if (empty($_SESSION['id']) && $_SESSION['confirm'] == false) {
    header('location:login.php');
}
$date = $_GET['date'];
$timeStart = $_GET['time'];
$timeEnd = date("H:i", strtotime("+30 minutes", strtotime($timeStart)));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Form</title>
    <!-- BS css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <?php include('nav.php') ?>
    <?php include('welcome.php') ?>
    <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'super') {
        include('appointment_form_admin.php');
    } else {
        include('appointment_form_user.php');
    } ?>
</body>

</html>