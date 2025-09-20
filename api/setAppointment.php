<?php
date_default_timezone_set('Asia/Manila');
if (isset($_POST['submit'])) {
    $startAppointment = $_POST['startAppointment'];
    $endAppointment = $_POST['endAppointment'];
    $note = $_POST['note'];
    $user_id = $_POST['user_id'];
    $serviceType = $_POST['serviceType'];
    $date = $_POST['date'];
    $aid = $_POST['aid'];
    $status = $_POST['status'];
    $reason = $_POST['reason'];

    $response = file_get_contents(
        'http://localhost:5000/setAppointment?' .
            'date=' . urlencode($date) .
            '&startAppointment=' . urlencode($startAppointment) .
            '&endAppointment=' . urlencode($endAppointment) .
            '&note=' . urlencode($note) .
            '&user_id=' . urlencode($user_id) .
            '&status=' . urlencode($status) .
            '&reason=' . urlencode($reason) .
            '&aid=' . urlencode($aid) .
            '&serviceType=' . urlencode($serviceType)
    );
    $response = json_decode($response, true);

    // if (count($response) > 0 && $aid) {
    //     $response = file_get_contents(
    //         'http://localhost:5000/changeStatusaid?' .
    //             'aid=' . urlencode($aid) 
    //     );
    //     $response = json_decode($response, true);
    // }




    header("location: ../index.php?selectedDate=" . date("d-m-Y"));
}
