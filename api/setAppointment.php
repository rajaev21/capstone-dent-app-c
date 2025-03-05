<?php
echo 'start';
if(isset($_POST['submit'])){
    echo 'start';
    $startAppointment = $_POST['startAppointment'];
    $endAppointment = $_POST['endAppointment'];
    $note = $_POST['note'];
    $user_id = $_POST['user_id'];
    $customerSelect = $_POST['customerSelect'];
    $serviceType = $_POST['serviceType'];

    $response = file_get_contents(
        'http://localhost:5000/setAppointment?'.
        'startAppointment='. urlencode($startAppointment) . 
        '&endAppointment='. urlencode($endAppointment).
        '&note='. urlencode($note).
        '&user_id='. urlencode($user_id).
        '&customerSelect='. urlencode($customerSelect).
        '&serviceType='. urlencode($serviceType)
    );

    $response = json_decode($response, true);
    
    header('location: ../index.php?response='.urldecode($response));
}