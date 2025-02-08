<?php
session_start();
if (empty($_SESSION['id'])) {
    header('location: login.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Form</title>
    <!-- BS css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- BS icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow">
        <a class="navbar-brand h3 fw-bold mx-5" href="index.php">Dent App</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home</a>
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
    <!-- Form Starts here -->
    <form action="./api/appointmentAPI.php" method="GET">
        <div class="row">
            <div class="col-6">


                <div class="d-flex align-items-center justify-content-center bg-light m-4">
                    <div class="card" style="width: 100rem;">
                        <div class="card-body">
                            <div class="card-title h3 fw-bold mb-0">Customer Details:</div>
                            <div class="card-text mb-4">Insert appointment details.</div>
                            <div class="row m-3">
                                <div class="col">
                                    <label for="firstName" class="form-label">First Name:</label>
                                    <input class="form-control" type="text" name="firstName" id="firstname" placeholder="Firstname">
                                </div>
                                <div class="col">
                                    <label for="lastname" class="form-label">Last Name:</label>
                                    <input class="form-control" type="text" name="lastName" id="lastname" placeholder="Lastname">
                                </div>
                                <div class="col">
                                    <label for="age" class="form-label">Age:</label>
                                    <input class="form-control" type="number" name="age" id="age" placeholder="Age">
                                </div>
                            </div>

                            <div class="row m-4">
                                <label for="gender" class="form-label">Gender:</label>
                                <select class="form-select" type="text" name="gender" id="gender">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>

                            <div class="row m-3">
                                <div class="col">
                                    <label for="email" class="form-label">Email:</label>
                                    <input class="form-control" type="email" name="email" id="email" placeholder="Email">
                                </div>
                                <div class="col">
                                    <label for="phoneNumber" class="form-label">Phone number:</label>
                                    <input class="form-control" type="number" name="phoneNumber" id="phoneNumber" placeholder="Phone number">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-6">
                <div class="d-flex align-items-center justify-content-center bg-light m-4">
                    <div class="card col">
                        <div class="card-body">
                            <div class="card-title h3 fw-bold mb-0">Address:</div>
                            <div class="card-text mb-4">Insert appointment details.</div>
                            <div class="row m-3">
                                <div class="col"><label for="street" class="form-label">Street:</label>
                                    <select class="form-select" name="street" id="street">
                                        <option value="street">Street</option>
                                        <option value="street">street</option>
                                    </select>
                                </div>
                                <div class="col"><label for="barangay" class="form-label">Barangay:</label>
                                    <select class="form-select" name="barangay" id="barangay">
                                        <option value="barangay">barangay</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row m-3">
                                <div class="col">
                                    <label for="municipality" class="form-label">Municipality:</label>
                                    <select class="form-select" name="municipality" id="municipality">
                                        <option value="municipality">minicipality</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="district" class="form-label">District:</label>
                                    <select class="form-select" name="district" id="district">
                                        <option value="district">district</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row m-3">
                                <div class="col">
                                    <label for="post_code" class="form-label">Post code:</label>
                                    <select class="form-select" name="post_code" id="post_code">
                                        <option value="post_code">post_code</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="city" class="form-label">City:</label>
                                    <select class="form-select" name="city" id="city">
                                        <option value="city">city</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="country" class="form-label">Country:</label>
                                    <select class="form-select" name="country" id="country">
                                        <option value="country">country</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="d-flex align-items-center justify-content-center bg-light m-4">
                    <div class="card" style="width: 100rem;">
                        <div class="card-body">
                            <div class="card-title h3 fw-bold mb-0">Appointment Details:</div>
                            <div class="card-text mb-4">Insert appointment details.</div>
                            <div class="row m-4">
                                <div class="col">
                                    <label for="appointmentDate" class="form-label">Pick a date and time:</label>
                                    <input class="form-control" type="datetime-local" name="appointmentDate" id="appointmentDate">
                                </div>
                                <div class="col">
                                    <label for="serviceType" class="form-label">Pick type of service:</label>
                                    <select class="form-control" name="serviceType" id="serviceType" placeholder="Service Type">
                                        <option value="serviceType">Service Type</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row m-4">
                                <label for="note" class="form-label">Notes:</label>
                                <input class="form-control" type="text" name="note" placeholder="Note" id="note">
                            </div>

                            <div class="row m-4">
                                <input type="hidden" name="status" value="1">
                                <input type="hidden" name="user_id" value="<?= $_SESSION['id'] ?>">
                                <button class="btn btn-primary" type="submit" name="appoint">Create Appointment</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>