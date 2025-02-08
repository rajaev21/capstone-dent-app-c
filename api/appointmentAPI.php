<?php
if (isset($_GET['appoint'])) {
    $firstName = $_GET['firstName'];
    $lastName = $_GET['lastName'];
    $age = $_GET['age'];
    $gender = $_GET['gender'];
    $email = $_GET['email'];
    $phoneNumber = $_GET['phoneNumber'];

    $street = $_GET['street'];
    $barangay = $_GET['barangay'];
    $municipality = $_GET['municipality'];
    $district = $_GET['district'];
    $post_code = $_GET['post_code'];
    $city = $_GET['city'];
    $country = $_GET['country'];

    $appointmentDate = $_GET['appointmentDate'];
    $serviceType = $_GET['serviceType'];
    $note = $_GET['note'];
    $status = $_GET['status'];
    $user_id = $_GET['user_id'];

    $response = file_get_contents(
        'http://localhost:5000/appoint?' .
            '&firstName=' . urlencode($firstName) .
            '&lastName=' . urlencode($lastName) .
            '&age=' . urlencode($age) .
            '&gender=' . urlencode($gender) .
            '&email=' . urlencode($email) .
            '&phoneNumber=' . urlencode($phoneNumber) .
            '&street=' . urlencode($street) .
            '&barangay=' . urlencode($barangay) .
            '&municipality=' . urlencode($municipality) .
            '&district=' . urlencode($district) .
            '&post_code=' . urlencode($post_code) .
            '&city=' . urlencode($city) .
            '&country=' . urlencode($country) .
            '&appointmentDate=' . urlencode($appointmentDate) .
            '&serviceType=' . urlencode($serviceType) .
            '&note=' . urlencode($note) .
            '&status=' . urlencode($status) .
            '&user_id=' . urlencode($user_id)
    );

    header('location: ../appointment_form.php?response='.urldecode($response));
}
