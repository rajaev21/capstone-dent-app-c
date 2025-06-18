<?php
session_start();
date_default_timezone_set('Asia/Manila');
if (empty($_SESSION['id']) && $_SESSION['confirm'] == false) {
    header('location:login.php');
}
$dateToday = date('F d,Y');
$timeToday = date('H:i:s');
$month = date('m');
$year = date('Y');
$daysInMonth = date('t');

if (isset($_GET['selectedDate'])) {
    $_SESSION['selectedDate'] = $_GET['selectedDate'];
}

$getDate = date("Y-m-d", strtotime($_SESSION['selectedDate']))
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>
    <!-- BS css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- BS icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <?php include('nav.php') ?>
    <?php include('welcome.php') ?>

    <?php
    $response = file_get_contents('http://localhost:5000/isAppointed?user_id=' . $_SESSION['id']);
    $response = json_decode($response, true);

    if ($response) { ?>

        <div class="container">
            <h4>You already have an appointment</h4>
        </div>
    <?php } else { ?>


        <div class="calendar container border">
            <div class="table">
                <div class="row">
                    <div class="col">
                        <h1>Today's date: <?php echo $dateToday ?></h1>
                    </div>
                    <div class="col">
                        <form action="index.php" method="get">
                            <input type="date" name="selectedDate" value="<?php echo date("Y-m-d", strtotime($_SESSION['selectedDate'])) ?>">
                            <input type="submit" name="submit" value="Select Date">
                        </form>
                    </div>
                </div>
                <table class="container-fluid table">
                    <thead>
                        <tr class="text-center">
                            <td class="col-2"></td>
                            <td>
                                <div class=""><?php echo date("l", strtotime($_SESSION['selectedDate'])); ?></div>
                                <div class=""><?php echo date("d", strtotime($_SESSION['selectedDate'])); ?></div>
                            </td>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <?php $response = file_get_contents('http://localhost:5000/getAppointment?date=' . $getDate);
                        $response = json_decode($response, true);

                        for ($i = strtotime("09:00"); $i <= strtotime("18:00"); $i += 1800) {
                            $slotTime = date("H:i", $i);

                            $isBooked = false;
                            $isDone = false;

                            foreach ($response as $result) {
                                if ($result['appointment_start'] === $slotTime) {
                                    $isBooked = true;
                                    $isDone = ($result['status'] == 2);
                                    break;
                                }
                            }
                        ?>
                            <tr>
                                <td class="col-2"><?php echo date("h:i A", $i); ?></td>
                                <td>
                                    <?php if ($isBooked && $_SESSION['role'] == 'user') { ?>
                                        <button class="btn btn-danger w-100" disabled>Booked</button>

                                    <?php } elseif ($isBooked && $_SESSION['role'] == 'admin') { ?>
                                        <a class=" <?php if ($isDone) {
                                                        echo "btn btn-success w-100 disabled";
                                                    } else {
                                                        echo "btn btn-primary w-100";
                                                    } ?> " href="<?php echo './customer_details.php?aid=' . urlencode($result['aid']); ?>"> <?php if ($isDone) {
                                                                                                                                            echo "Finish";
                                                                                                                                        } else {
                                                                                                                                            echo "Booked";
                                                                                                                                        } ?> </a>
                                    <?php } else { ?>
                                        <form action="appointment_form.php" method="get">
                                            <input type="hidden" name="date" value="<?php echo $getDate; ?>">
                                            <input type="hidden" name="time" value="<?php echo $slotTime; ?>">
                                            <input type="submit" class="btn btn-light w-100 <?php if ($_SESSION['role'] == 'admin') {
                                                                                                echo "disabled";
                                                                                            } ?> " value="Available" />
                                        </form>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
    }
    ?>
    <!-- BS script w/ popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>