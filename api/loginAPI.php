<?php

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $response = file_get_contents('http://localhost:5000/login?' .
        '&username=' . urlencode($username) .
        '&password=' . urlencode($password));



    $responseDecode = json_decode($response, true);

    if ($responseDecode && isset($responseDecode['id'])) {
        session_start();

        $_SESSION['id'] = $responseDecode['id'];
        $_SESSION['username'] = $responseDecode['username'];
        $_SESSION['firstname'] = $responseDecode['firstname'];
        $_SESSION['lastname'] = $responseDecode['lastname'];
        $_SESSION['email'] = $responseDecode['email'];
        $_SESSION['phoneNumber'] = $responseDecode['phoneNumber'];
        $_SESSION['role'] = $responseDecode['role'];

        header('Location:../index.php');
        echo "<script>alert('Login successful!');</script>";
        exit();
    } else {
        header('Location:../login.php');
        echo "<script>alert('".$responseDecode."');</script>";
        exit();
    }
}
