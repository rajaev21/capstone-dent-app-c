<?php
date_default_timezone_set('Asia/Manila');
$aid = $_POST['aid'];
$admin_id = $_POST['admin_id'];
$user_id = $_POST['user_id'];
$reason = $_POST['reason'];

if (isset($_POST['cancel'])) {
  $result = file_get_contents("http://localhost:5000/cancelSingleAppointment?aid=" . urlencode($aid) . "&admin_id=" . urlencode($admin_id) . "&user_id=" .  urlencode($user_id) . "&reason=" . urlencode($reason));
  $result = json_decode($result);
  if ($result) {
    header("location: ../index.php?selectedDate=" . date("Y-m-d"));
  }
}
