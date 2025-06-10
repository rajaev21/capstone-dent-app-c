<?php
echo 'start';
if(isset($_POST['submit'])){
    echo 'start';
    $startAppointment = $_POST['startAppointment'];
    $endAppointment = $_POST['endAppointment'];
    $note = $_POST['note'];
    $user_id = $_POST['user_id'];
    $serviceType = $_POST['serviceType'];
    $date= $_POST['date'];

    $response = file_get_contents(
        'http://localhost:5000/setAppointment?'.
        'date='. urlencode($date) . 
        '&startAppointment='. urlencode($startAppointment) . 
        '&endAppointment='. urlencode($endAppointment).
        '&note='. urlencode($note).
        '&user_id='. urlencode($user_id).
        '&serviceType='. urlencode($serviceType)
    );

    $response = json_decode($response, true);
    
    header('location: ../index.php?response='.urldecode($response));
}