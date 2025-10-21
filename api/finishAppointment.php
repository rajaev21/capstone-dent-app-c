<?php
date_default_timezone_set('Asia/Manila');

if (isset($_POST['finish'])) {
    $id = $_POST['id'];
    $admin_id = $_POST['admin_id'];
    $user_id = $_POST['user_id'];
    $result = file_get_contents("http://localhost:5000/finishAppointment?id=" . urlencode($id) . "&status=3" . "&admin_id=" . urlencode($admin_id) . "&user_id=" .  urlencode($user_id));
    $result = json_decode($result, true);
}
if (isset($_POST['cancel'])) {
    $id = $_POST['id'];
    $admin_id = $_POST['admin_id'];
    $user_id = $_POST['user_id'];
    $reason = $_POST['reason'];
    $result = file_get_contents("http://localhost:5000/finishAppointment?id=" . urlencode($id) . "&status=4" . "&admin_id=" . urlencode($admin_id) . "&user_id=" .  urlencode($user_id) . "&reason=" .  urlencode($reason));
    $result = json_decode($result, true);
}
if (isset($_POST['cancelBooked'])) {
    $aid = $_POST['aid'];
    $user_id = $_POST['user_id'];
    $reason = $_POST['reason'];
    $result = file_get_contents("http://localhost:5000/cancelBooked?aid=" . urlencode($aid) . "&reason=" . urlencode($reason) . "&user_id=" .  urlencode($user_id));
    $result = json_decode($result, true);
}
if (isset($_POST['approve'])) {
    $id = $_POST['id'];
    $admin_id = $_POST['admin_id'];
    $user_id = $_POST['user_id'];
    $result = file_get_contents("http://localhost:5000/finishAppointment?id=" . urlencode($id) . "&status=2" . "&admin_id=" . urlencode($admin_id) . "&user_id=" .  urlencode($user_id));
    $result = json_decode($result);
    if ($result) {
        $start = $_POST['start'];
        $date = $_POST['date'];
        $cancel = file_get_contents("http://localhost:5000/cancelAppointments?start=" . urlencode($start) . "&date=" . urlencode($date) . "&id=" . urlencode($id) . "&admin_id=" . urlencode($admin_id));
        $cancel = json_decode($cancel, true);
    }
}
header("location: ../index.php?selectedDate=" . date("Y-m-d"));
