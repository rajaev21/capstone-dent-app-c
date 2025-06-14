<?php

if(isset($_POST['submit'])){
    $id = $_POST['id'];
    $result = file_get_contents("http://localhost:5000/finishAppointment?id=" .urlencode($id));
    $result = json_decode($result, true);

    header("location: ../customer_details.php?response=&aid=".$id);
}