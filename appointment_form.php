<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Form</title>
    <!-- BS css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <?php include('nav.php') ?>
    <?php include('welcome.php') ?>
    <form action="./api/setAppointment.php" method="POST"></form>
    <div class="container d-flex align-items-center">
        <div class="container">
            <div class="p-1">
                <label for="startAppointment">Start Date:</label>
                <input type="date" name="startAppointmentDate" value="<?php echo $date ?>" readonly>
            </div>
            <div class="p-1">
                <label for="startAppointment">Start Time:</label>
                <input type="time" name="startAppointmentTime" value="<?php echo $date ?>">
            </div>
            <div class="p-1">
                <label for="endAppointment">End:</label>
                <input type="date" name="endAppointment" value="2025-02-09T01:00" id="endAppointment">
            </div>
            <div class="p-1">
                <label for="serviceType">Type</label>
                <select name="serviceType" id="serviceType">
                    <option value="teethCleaning">Teeth cleaning</option>
                    <option value="filling">Fillings (Pasta)</option>
                    <option value="brace">Orthodontic treatment</option>
                    <option value="examination">Oral examinations</option>
                </select>
            </div>
            <div class="p-1">
                <label class="form-label d-flex justify-content-start" for="note">Note:</label>
                <textarea class="form-control" name="note" id="note" row="4"></textarea>
            </div>
            <div class="p-1">
                <label for="form-label" for="customerSelect">Select customer</label>
                <select name="customerSelect" id="customerSelect">
                    <?php
                    $result = file_get_contents("http://localhost:5000/selectCustomer");
                    $result = json_decode($result, false);
                    foreach ($result as $row) {
                    ?>
                        <option value="<?php echo $row[0] ?>">
                            <?php echo $row[1] . $row[2] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <input type="hidden" name="user_id" value="<?php echo $_SESSION['id'] ?>">
            <div class="p-3">
                <input class="btn btn-primary" type="submit" value="Submit" name="submit">
            </div>
        </div>
    </div>
    </form>
</body>

</html>