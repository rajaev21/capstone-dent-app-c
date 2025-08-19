<?php
$date = $_GET['selectedDate'];
$start = urldecode($_GET['starting']);

$response = file_get_contents('http://localhost:5000/getWaiting?date=' . $date . "&start=" . $start);
$response = json_decode($response, true);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dent App</title>
    <!-- BS css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- BS icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <?php
    include('nav.php');
    include('welcome.php');
    ?>

    <div class="container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Number</th>
                    <th>Address</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($response as $customer) {
                ?>
                    <tr>
                        <td class="text-capitalize"><?= ($customer['firstName'] ?? "") . " " . ($customer['lastName'] ?? ""); ?></td>
                        <td class="text-capitalize"><?= $customer['age'] ?? ""; ?></td>
                        <td class="text-capitalize"><?= $customer['gender'] ?? ""; ?></td>
                        <td class="text-capitalize"><?= $customer['contactNumber'] ?? ""; ?></td>
                        <td class="text-capitalize"><?= $customer['address'] ?? ""; ?></td>
                        <td>
                            <button type="button" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#modal<?= $customer['aid'] ?>">
                                Approval
                            </button>
                            <div class="modal fade" id="modal<?= $customer['aid'] ?>" tabindex="-1" aria-labelledby="approvalModalLabel<?= $customer['aid'] ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="approvalModalLabel<?= $customer['aid'] ?>">Approval</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <h6>Do you want to approve <span class="fw-bold text-capitalize"><?= ($customer['firstName'] ?? "") . " " . ($customer['lastName'] ?? ""); ?></span> appointment?</h6>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <form action="./api/finishAppointment.php" method="POST">
                                                <button class="btn btn-primary" type="submit" name="approve">Approve</button>
                                                <input type="hidden" name="id" value="<?= $customer['aid'] ?>">
                                                <input type="hidden" name="date" value="<?= $date ?>">
                                                <input type="hidden" name="start" value="<?= $start ?>">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>