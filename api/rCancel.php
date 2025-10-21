<?php
date_default_timezone_set('Asia/Manila');
$aid = $_POST['aid'];
$admin_id = $_POST['admin_id'];
$user_id = $_POST['user_id'];
$answer = $_POST['answer'];

if (isset($_POST['answer'])) {
  $result = file_get_contents("http://localhost:5000/rCancelApproval?aid=" . urlencode($aid) . "&admin_id=" . urlencode($admin_id) . "&user_id=" .  urlencode($user_id) . "&answer=" . urlencode($answer));
  $result = json_decode($result);
  if ($answer == "No") {
    header("location: ../customer_details.php?aid=" . urlencode($aid));
  } else {
    header("location: ../index.php?selectedDate=" . date("Y-m-d"));
  }
}
