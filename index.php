<?php
session_start();
if (empty($_SESSION['id'])) {
    header('location:login.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- BS css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- BS icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <!-- navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand h3 fw-bold mx-5" href="index.php">Dent App</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="appointment_form.php">Appointment Form</a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="logout.php">
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <div class="card d-flex align-items-center justify-content-center m-4 shadow">
        <div class="card-body">
            <div class="card-title h3 fw-bold">List of appointments:</div>
            <table class="table table-striped">
                <thead>
                    <th scope="col">ID</th>
                    <th scope="col">Assigned By</th>
                    <th scope="col">Customer</th>
                    <th scope="col">Appointed Date</th>
                    <th scope="col">Appointment</th>
                    <th scope="col">Note</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </thead>
                <tbody>
                    <?php
                    $result = file_get_contents("http://localhost:5000/index");
                    $result = json_decode($result, false);
                    foreach ($result as $row) {
                    ?>
                        <tr>
                            <td><?= $row[0] ?></td>
                            <td class="text-capitalize"><?= $row[1] ?> <span class="text-capitalize"><?= $row[2] ?></span></td>
                            <td class="text-capitalize"><?= $row[3] ?> <span class="text-capitalize"><?= $row[4] ?></span></td>
                            <td><?= $row[5] ?></td>
                            <td><?= $row[6] ?></td>
                            <td><?= $row[7] ?></td>
                            <td><?= $row[8] ?></td>
                            <td>
                                <button class="btn btn-primary">Action here</button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- BS script w/ popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>