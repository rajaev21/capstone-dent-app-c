<?php
date_default_timezone_set('Asia/Manila');

if (isset($_POST['register'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirmPassword'];
  $email = $_POST['email'];
  $adminCode = $_POST['adminCode'];


  $result_email = file_get_contents('http://localhost:5000/checkEmail?' .
    '&email=' . urlencode($email));
  $result_email = json_decode($result_email, true);

  if (count($result_email) > 5) {
    header('location: ../login.php?response=Spam account detected!');
    echo count($result_email);
    exit;
  }

  if ($password != $confirmPassword) {
    header('location: ../login.php?response=Password does not match!');
    exit;
  }
  $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
  $response = file_get_contents('http://localhost:5000/login?' .
    '&username=' . urlencode($username));
  $response = json_decode($response, true);
  if (!empty($response[0]['username'])) {
    header('location: ../login.php?response=Account Already Exist');
  } else {


    if ($adminCode) {
      if (@$adminCode == "@dentapp") {
        $response = file_get_contents('http://localhost:5000/register?' .
          '&username=' . urlencode($username) .
          '&password=' . urlencode($hashedPassword) .
          '&email=' . urlencode($email) .
          '&role=admin');
        $response = json_decode($response, true);
        header('location: ../login.php?response=Admin registration complete');
      } else {
        header('location: ../login.php?response=Invalid admin code');
      }
    } else {
      $response = file_get_contents('http://localhost:5000/register?' .
        '&username=' . urlencode($username) .
        '&password=' . urlencode($hashedPassword) .
        '&email=' . urlencode($email) .
        '&role=user');
      $response = json_decode($response, true);
      $id = $response['inserted_id'];

      header('location: ../login.php?response=Registration complete');
    }
  }
}
