<?php
session_start();

$email = $_SESSION['email'];
$otp = '';
$numbers = '0123456789';
$headers = 'From: rajaevberame21@gmail.com';


for ($i = 1; $i <= 6; $i++) {
    $otp .= $numbers[random_int(0, strlen($numbers) - 1)];
}

if (mail($email, "OTP", "Dent app OTP is: " . $otp, $headers)) {
    $_SESSION['otp'] = $otp;
    header('location:../otp.php?response=Otp sent');
    exit();
} else {
    header('location:../otp.php?respnose=Please log in again');
    exit();
}
