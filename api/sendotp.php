<?php
date_default_timezone_set('Asia/Manila');
session_start();

$receiver = $_SESSION['email'];
$body = '';
$numbers = '0123456789';
$sender = 'From: rajaevberame21@gmail.com';


for ($i = 1; $i <= 6; $i++) {
    $body .= $numbers[random_int(0, strlen($numbers) - 1)];
}

if (mail($receiver, $subject, $body, $sender)) {
    $_SESSION['otp'] = $body;
    header('location:../otp.php?response=Otp sent');
    exit();
} else {
    header('location:../otp.php?respnose=Please log in again');
    exit();
}
