<?php

if (isset($_POST['confirmOtp'])) {
    session_start();
    $userOtp = $_POST['otp'];
    $emailOtp = $_SESSION['otp'];

    if ($emailOtp == $userOtp) {
        $response = file_get_contents('http://localhost:5000/login?' .
            '&username=' . urlencode($username));
        $response = json_decode($response, true);

        foreach ($response as $result) {
            $_SESSION['confirm'] = true;
            $_SESSION['id'] = $result['id'];
            $_SESSION['username'] = $result['username'];
            $_SESSION['firstname'] = $result['first_name'];
            $_SESSION['lastname'] = $result['last_name'];
            $_SESSION['phoneNumber'] = $result['phone'];
            $_SESSION['role'] = $result['role'];
            header('Location:../index.php?selectedDate=' . date("d-m-Y"));
            exit();
        }
    } else {
        header("Location: ../otp.php?response=Incorrect OTP");
        exit();
    }
}
