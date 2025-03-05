<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Table</title>
    <!-- BS css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- BS icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <?php include('nav.php') ?>

    <h1>Welcome to Appointment Table</h1>
    <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Suscipit unde laborum nesciunt repellendus, assumenda omnis exercitationem quas aliquid labore quis. Ea rem eius quam assumenda quas esse minus enim quis.</p>
    <div class="card d-flex align-items-center justify-content-center m-4 shadow">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <div class="card-title h3 fw-bold">Today</div>
                </div>
                <div class="col d-flex justify-content-end"><a class="btn btn-primary" href="appointment_form.php">New Customer</a></div>
            </div>
            <!-- user table -->
            <div class="container">
                <table class="table table-striped">
                    <thead>
                        <th scope="col">Account ID</th>
                        <th scope="col">Username</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Gender</th>
                        <th scope="col">Phone</th>
                        <th scope="col"></th>
                    </thead>
                    <tbody>
                        <?php
                        $result = file_get_contents("http://localhost:5000/selectUser?&role=user");
                        $result = json_decode($result, true);
                        foreach ($result as $row) {
                        ?>
                            <tr>
                                <th><?php echo $row['id'] ?></th>
                                <td class="text-capitalize"><?= $row['first_name'] ?> <span class="text-capitalize"><?php echo $row['last_name'] ?></span></td>
                                <td><?php echo $row['username'] ?></td>
                                <td><?php echo $row['email'] ?></td>
                                <td><?php echo $row['gender'] ?></td>
                                <td><?php echo $row['phone'] ?></td>
                                <td>
                                    <button class="btn btn-primary">Edit account</button>
                                    <button class="btn btn-danger">Delete account</button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <!-- BS script w/ popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>