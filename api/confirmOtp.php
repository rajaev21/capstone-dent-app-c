<?php
session_start();
date_default_timezone_set('Asia/Manila');

if (isset($_POST['confirmOtp'])) {
    $userOtp = $_POST['otp'];
    $emailOtp = $_SESSION['otp'];
    $username = $_SESSION['username'];

    if ($emailOtp == $userOtp) {

        $response = file_get_contents('http://localhost:5000/login?' .
            '&username=' . urlencode($username));
        $response = json_decode($response, true);
        
        foreach ($response as $result) {

            $_SESSION['confirm'] = true;
            $_SESSION['id'] = $result['id'];

            header('Location:../index.php?selectedDate=' . date("Y-m-d"));
            exit();
        }
    } else {
        header("Location: ../otp.php?response=Incorrect OTP");
        exit();
    }
}
