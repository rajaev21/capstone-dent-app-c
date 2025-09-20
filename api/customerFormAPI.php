<?php
if (isset($_GET['appoint'])) {
    $id = $_GET['id'];
    $firstName = $_GET['firstName'];
    $middleName = $_GET['middleName'];
    $lastName = $_GET['lastName'];
    $nickName = $_GET['nickName'];

    $address = $_GET['address'];
    $contactNumber = $_GET['contactNumber'];
    $facebook = $_GET['facebook'];
    $email = $_GET['email'];

    $birthDay = $_GET['birthDay'];
    $nationality = $_GET['nationality'];
    $age = $_GET['age'];

    $gender = $_GET['gender'];
    $civilStatus = $_GET['civilStatus'];
    $occupation = $_GET['occupation'];

    $employer = $_GET['employer'];
    $clinic = $_GET['clinic'];
    $prevClinic = $_GET['prevClinic'];

    $emergencyFirstname = $_GET['emergencyFirstname'];
    $emergencyLastname = $_GET['emergencyLastname'];
    $relationship = $_GET['relationship'];
    $emergencyContactNumber = $_GET['emergencyContactNumber'];

    $taken = $_GET['taken'];
    $conditions = $_GET['conditions'];

    $response = file_get_contents(
        'http://localhost:5000/addCustomer?' .
            '&id=' . urlencode($id) .
            '&firstName=' . urlencode($firstName) .
            '&middleName=' . urlencode($middleName) .
            '&lastName=' . urlencode($lastName) .
            '&nickName=' . urlencode($nickName) .
            '&address=' . urlencode($address) .
            '&contactNumber=' . urlencode($contactNumber) .
            '&facebook=' . urlencode($facebook) .
            '&email=' . urlencode($email) .
            '&birthDay=' . urlencode($birthDay) .
            '&nationality=' . urlencode($nationality) .
            '&age=' . urlencode($age) .
            '&gender=' . urlencode($gender) .
            '&civilStatus=' . urlencode($civilStatus) .
            '&occupation=' . urlencode($occupation) .
            '&employer=' . urlencode($employer) .
            '&clinic=' . urlencode($clinic) .
            '&prevClinic=' . urlencode($prevClinic) .
            '&emergencyFirstname=' . urlencode($emergencyFirstname) .
            '&emergencyLastname=' . urlencode($emergencyLastname) .
            '&relationship=' . urlencode($relationship) .
            '&emergencyContactNumber=' . urlencode($emergencyContactNumber) .
            '&taken=' . urlencode(json_encode($taken)) .
            '&conditions=' . urlencode(json_encode($conditions))
    );
    header('location: ../index.php?response='.urldecode($response));
}
