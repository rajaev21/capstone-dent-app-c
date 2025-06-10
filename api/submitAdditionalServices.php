<?php

if(isset($_POST['submit'])) {
    $additionalServices = $_POST['additionalServices'];
    $user_id = $_POST['user_id'];
    $appointment_id = $_POST['appointment_id'];
    
                            
                            
    
    $result = file_get_contents(
        'http://localhost:5000/additionalServices?'.
        'additionalServices='. urlencode($additionalServices) .
        '&user_id='. urlencode($user_id) .
        '&appointment_id='. urlencode($appointment_id) 
    );
    
    if($result) {
        $response = json_decode($result, true);
        header('location: ../customer_details.php?response='.urldecode($response) . '&aid='.$appointment_id);
    } else {
        echo "Error: Unable to connect to the API.";
    }
    exit();
}