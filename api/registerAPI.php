<?php

if (isset($_POST['register'])) {

    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $gender = $_POST['gender'];
    $phoneNumber = $_POST['phoneNumber'];
    $role = $_POST['role'];

    if ($password != $confirmPassword) {
        echo "Password does not match!";
        header('location: register.php');
        exit;
    }

    if ($password == $confirmPassword) {

        $response = file_get_contents('http://localhost:5000/register?' .
            '&firstName=' . urlencode($firstName) .
            '&lastName=' . urlencode($lastName) .
            '&email=' . urlencode($email) .
            '&password=' . urlencode($password) .
            '&gender=' . urlencode($gender) .
            '&phoneNumber=' . urlencode($phoneNumber) .
            '&role=' . urlencode($role));
            
        json_decode($response, true);
        
        echo '<script>alert($response)</script>';
        header('location: ../login.php');
    }
}
