<?php
date_default_timezone_set('Asia/Manila');
$bypassUser = ["admin","a", "xx", "xxx", "xxxx", "xxxxx"];
if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $response = file_get_contents('https://dent-flask-production.up.railway.app/login?' .
        '&username=' . urlencode($username));
    $response = json_decode($response, true);

    foreach ($response as $result) {
        if ($result && isset($result['id'])) {
            if (password_verify($password, $result['password'])) {

                session_start();
                // Test session
                $_SESSION['confirm'] = false;
                $_SESSION['email'] = $result['email'];
                $_SESSION['username'] = $result['username'];
                $_SESSION['role'] = $result['role'];
                $_SESSION['otp'] = $result['otp'];


                if (in_array($username, $bypassUser)) {
                    $_SESSION['confirm'] = true;
                    $_SESSION['id'] = $result['id'];
                    header('Location:../index.php?selectedDate=' . date("Y-m-d"));
                    exit();
                }
                echo $_SESSION['otp'];
                header('location:./confirmOtp.php?');
                exit();
            }
        }
    }
    header('Location:../login.php?response=Password do not match or account not found!');
}
