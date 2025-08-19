<?php
session_start();
date_default_timezone_set('Asia/Manila');
if (empty($_SESSION['id']) && $_SESSION['confirm'] == false) {
    header('location:login.php');
}
$reschedule = false;
$dateToday = date('F d,Y');
$timeToday = date('H:i:s');
$month = date('m');
$year = date('Y');
$daysInMonth = date('t');

if (isset($_GET['selectedDate'])) {
    $_SESSION['selectedDate'] = $_GET['selectedDate'];
}
if (isset($_GET['user_id']) && isset($_GET['aid'])) {
    $reschedule = true;
}

$getDate = date("Y-m-d", strtotime($_SESSION['selectedDate']))
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dent App</title>
    <!-- BS css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- BS icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <?php
    include('nav.php');
    include('welcome.php');

    $user_id = $_SESSION['id'];
    $searchDate = date("Y-m-d", strtotime($dateToday));
    $response = file_get_contents('http://localhost:5000/isAppointed?user_id=' . $user_id);
    $response = json_decode($response, true);
    $Finished = false;
    $Pending = false;
    $Booked = false;
    $isRescheduled = false;
    if (isset($_GET['aid']) && isset($_GET['user_id'])) {
        $isRescheduled = true;
    }

    if (count($response) > 0) {
        foreach ($response as $result) {
            $date = strtotime($result['date']);
            $status = $result['status'];
            $aid = $result['aid'];

            if ($date < strtotime($dateToday) && ($status == 1 || $status == 2)) {
                $status = 5;
                $response = file_get_contents('http://localhost:5000/changeStatus?user_id=' . $user_id . "&status=" . $status);
                $response = json_decode($response, true);
                header("Refresh:1");
            } elseif ($status == 3) {
                $Finished = true;
                $Booked = false;
            } elseif ($status == 1) {
                $Pending = true;
                $Finished = false;
            } elseif ($status == 2) {
                $Finished = false;
                $Booked = true;
            } elseif ($status == 4) {
                $Finished = false;
                $Booked = false;
            }
            break;
        }
    ?>
        <div class="container">
            <?php
            if ($Finished) {
            ?>
                <h4> Appointment for the day is done. Wait for the next appointment. </h4>
            <?php
            } elseif ($Pending) {
            ?>
                <h4> Waiting for approval on <?= date("F j, Y", strtotime($response[0]["date"])) ?> at <?= date("g:i a", strtotime($response[0]["appointment_start"])) ?> </h4>
                <form action="./api/finishAppointment.php" method="POST" class="row">
                    <input type="hidden" name="id" value="<?= $aid ?>">
                    <input class="btn btn-danger col" type="submit" name="cancel" value="Cancel Appointment" >

                    <button type="button" class="btn btn-success col" data-bs-toggle="modal" data-bs-target="#reschedule" <?= isset($_SESSION['ID']) ? "disabled" : "" ?>>
                        Reschedule Appointment
                    </button>
                </form>
                <?php
                include("reschedule.php");
                ?>
            <?php
            } elseif ($isRescheduled) {

                include("calendar.php");
            } elseif ($Booked) {
            ?>
                <h4> You already have an appointment on <?= date("F j, Y", strtotime($response[0]["date"])) ?> at <?= date("g:i a", strtotime($response[0]["appointment_start"])) ?> </h4>
                <form action="./api/finishAppointment.php" method="POST" class="row">
                    <input type="hidden" name="id" value="<?= $aid ?>">
                    <input class="btn btn-danger col" type="submit" name="cancel" value="Cancel Appointment" >

                    <button type="button" class="btn btn-success col" data-bs-toggle="modal" data-bs-target="#reschedule" <?= isset($_SESSION['ID']) ? "disabled" : "" ?>>
                        Reschedule Appointment
                    </button>
                </form>
                <?php
                include("reschedule.php");
                ?>

            <?php
            } else {
                include("calendar.php");
            }
            ?>
        </div>
    <?php
    } else {
        include("calendar.php");
    }
    ?>
    <!-- BS script w/ popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
<script>
    function changeDate() {
        const date = document.getElementById('calendar').value;
        const params = new URLSearchParams(window.location.search);
        params.set("selectedDate", date);
        if (date) {
            window.location.href = `${window.location.pathname}?${params.toString()}`;
        }
    }
</script>

</html>