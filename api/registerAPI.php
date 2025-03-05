<?php


if (isset($_POST['register'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $gender = $_POST['gender'];
    $phoneNumber = $_POST['phoneNumber'];
    $username = $_POST['username'];
    $adminCode = $_POST['adminCode'];


    if ($password != $confirmPassword) {
        header('location: ../login.php?response=Password does not match!');
        exit;
    }
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $response = file_get_contents('http://localhost:5000/login?' .
        '&username=' . urlencode($username));
    $response = json_decode($response, true);
    echo var_dump($response['username']);
    if (!empty($response[0]['username'])) {
        header('location: ../login.php?response=Account Already Exist');
    } else {

        if ($adminCode == "@dentapp") {
            $response = file_get_contents('http://localhost:5000/register?' .
                '&firstName=' . urlencode($firstName) .
                '&lastName=' . urlencode($lastName) .
                '&username=' . urlencode($username) .
                '&email=' . urlencode($email) .
                '&password=' . urlencode($hashedPassword) .
                '&gender=' . urlencode($gender) .
                '&phoneNumber=' . urlencode($phoneNumber) .
                '&role=admin');
            $response = json_decode($response, true);

            header('location: ../login.php?response=Admin registration complete');
        } else {
            $response = file_get_contents('http://localhost:5000/register?' .
            '&firstName=' . urlencode($firstName) .
            '&lastName=' . urlencode($lastName) .
            '&username=' . urlencode($username) .
            '&email=' . urlencode($email) .
            '&password=' . urlencode($hashedPassword) .
            '&gender=' . urlencode($gender) .
            '&phoneNumber=' . urlencode($phoneNumber) .
            '&role=user');
        $response = json_decode($response, true);

        header('location: ../login.php?response=User registration complete');
        }
    }
}
