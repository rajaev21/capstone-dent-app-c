<?php

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $response = file_get_contents('http://localhost:5000/login?' .
        '&username=' . urlencode($username));
    $response = json_decode($response, true);

    foreach ($response as $result) {
        if ($result && isset($result['id'])) {
            if (password_verify($password, $result['password'])) {
                // Test session
                // session_start();
                // $_SESSION['confirm'] = true;
                // $_SESSION['id'] = $result['id'];
                // $_SESSION['username'] = $result['username'];
                // $_SESSION['firstname'] = $result['first_name'];
                // $_SESSION['lastname'] = $result['last_name'];
                // $_SESSION['phoneNumber'] = $result['phone'];
                // $_SESSION['role'] = $result['role'];
                // header('Location:../index.php?selectedDate=' . date("d-m-Y"));
                // exit();

                $_SESSION['email'] = $response['email'];
                $_SESSION['confirm'] = false;

                header('location:./sendotp.php?');
                exit();
            }
        }
    }
    header('Location:../login.php?response=Password do not match or account not found!');
}
