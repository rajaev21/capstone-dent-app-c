<?php
date_default_timezone_set('Asia/Manila');
$id = $_POST['id'];
if (isset($_POST['finish'])) {
    $result = file_get_contents("http://localhost:5000/finishAppointment?id=" . urlencode($id) . "&status=3");
    $result = json_decode($result, true);
}
if (isset($_POST['cancel'])) {
    $result = file_get_contents("http://localhost:5000/finishAppointment?id=" . urlencode($id) . "&status=4");
    $result = json_decode($result, true);
}
if (isset($_POST['approve'])) {
    $result = file_get_contents("http://localhost:5000/finishAppointment?id=" . urlencode($id) . "&status=2");
    $result = json_decode($result);
    if ($result) {
        $start = $_POST['start'];
        $date = $_POST['date'];
        $cancel = file_get_contents("http://localhost:5000/cancelAppointments?start=" . urlencode($start) . "&date=" . urlencode($date) . "&id=" . urlencode($id));
        $cancel = json_decode($cancel, true);
    }
}
header("location: ../index.php?selectedDate=" . date("d-m-Y"));
