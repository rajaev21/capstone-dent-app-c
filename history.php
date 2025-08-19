<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
    <!-- BS css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- BS icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <?php
    include('nav.php');
    $result = file_get_contents("http://localhost:5000/getHistory");
    $result = json_decode($result, true);
    if (empty($result)) {
        echo "<div class='container mt-5'><h1 class='text-center'>No history found</h1></div>";
    } else {
    ?>
        <div class="container mt-5">
            <h1 class="text-center mb-4">Appointment History</h1>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Customer ID</th>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Note</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result as $appointment) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($appointment['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['firstname']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['lastname']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['appointment_start']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['appointment_end']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['note']); ?></td>
                            <td> 
                                <?php 
                                    if ($appointment['status'] == 1) { 
                                    echo "Pending";
                                    } elseif ($appointment['status'] == 2) {
                                        echo "Approved";
                                    } elseif ($appointment['status'] == 3) {
                                        echo "Done";
                                    } elseif ($appointment['status'] == 4) {
                                        echo "Cancelled";
                                    } elseif ($appointment['status'] == 5) {
                                        echo "Expired";
                                    } elseif ($appointment['status'] == 6) {
                                        echo "Rescheduled";
                                    } 
                                    
                                ?> 
                            </td>
                            <td><?php echo htmlspecialchars($appointment['date']); ?></td>
                            <td>
                                <?php if ($appointment['status'] == 1 || $appointment['status'] == 2) { ?>
                                    <button class="btn btn-primary" onclick="goTo(<?php echo htmlspecialchars($appointment['aid']); ?>)">View</button>
                                <?php } elseif ($appointment['status'] == 3) { ?>
                                    <button class="btn btn-success" onclick="goTo(<?php echo htmlspecialchars($appointment['aid']); ?>)" disabled>Finished</button>
                                <?php } elseif ($appointment['status'] == 4) { ?>
                                    <button class="btn btn-danger" onclick="goTo(<?php echo htmlspecialchars($appointment['aid']); ?>)" disabled>Cancelled</button>
                                <?php } elseif ($appointment['status'] == 6) { ?>
                                    <button class="btn btn-success" onclick="goTo(<?php echo htmlspecialchars($appointment['aid']); ?>)" disabled>Rescheduled</button>
                                <?php } else { ?>
                                    <button class="btn btn-danger" onclick="goTo(<?php echo htmlspecialchars($appointment['aid']); ?>)" disabled>Expired</button>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        function goTo(appointmentId) {
            window.location.href = "customer_details.php?aid=" + appointmentId;
        }
    </script>
</body>

</html>

</script>
</body>

</html>