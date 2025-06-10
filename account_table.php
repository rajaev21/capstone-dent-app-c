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
            </div>
            <!-- user table -->
            <div class="container">
                <table class="table table-striped">
                    <thead>
                        <th scope="col">Account ID</th>
                        <th scope="col">Username</th>
                        <th scope="col">Action</th>
                    </thead>
                    <tbody>
                        <?php
                        $result = file_get_contents("http://localhost:5000/selectUser?&role=user");
                        $result = json_decode($result, true);
                        foreach ($result as $row) {
                            $modalId = "userModal" . $row['user']['id'];
                        ?>
                            <tr>
                                <th><?php echo $row['user']['id'] ?></th>
                                <td><?php echo $row['user']['username'] ?></td>
                                <td>
                                    <button class="btn btn-primary">Edit account</button>
                                    <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#<?php echo $modalId ?>">
                                        Show Details
                                    </button>
                                </td>
                            </tr>

                            <div class="modal fade" id="<?php echo $modalId ?>" tabindex="-1" aria-labelledby="<?php echo $modalId ?>Label" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="<?php echo $modalId ?>Label">User Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <?php if ($row['customer_detail']) { ?>
                                                <p><strong>Name: </strong><?php echo $row['customer_detail'][0]['firstName'] . " " . $row['customer_detail'][0]['middleName'] . " " . $row['customer_detail'][0]['lastName'] ?></p>
                                                <p><strong>Nickname: </strong><?php echo $row['customer_detail'][0]['nickName'] ?></p>
                                                <p><strong>Address: </strong><?php echo $row['customer_detail'][0]['address'] ?></p>
                                                <p><strong>Contact Number: </strong><?php echo $row['customer_detail'][0]['contactNumber'] ?></p>
                                                <p><strong>Facebook: </strong><?php echo $row['customer_detail'][0]['facebook'] ?></p>
                                                <p><strong>Email: </strong><?php echo $row['customer_detail'][0]['email'] ?></p>
                                                <p><strong>Birthday: </strong><?php echo $row['customer_detail'][0]['birthDay'] ?></p>
                                                <p><strong>Nationality: </strong><?php echo $row['customer_detail'][0]['nationality'] ?></p>
                                                <p><strong>Age: </strong><?php echo $row['customer_detail'][0]['age'] ?></p>
                                                <p><strong>Gender: </strong><?php echo $row['customer_detail'][0]['gender'] ?></p>
                                                <p><strong>Civil Status: </strong><?php echo $row['customer_detail'][0]['civilStatus'] ?></p>
                                                <p><strong>Occupation: </strong><?php echo $row['customer_detail'][0]['occupation'] ?></p>
                                                <p><strong>Employer: </strong><?php echo $row['customer_detail'][0]['employer'] ?></p>
                                                <p><strong>Clinic: </strong><?php echo $row['customer_detail'][0]['clinic'] ?></p>
                                                <p><strong>Previous Clinic: </strong><?php echo $row['customer_detail'][0]['prevClinic'] ?></p>
                                                <p><strong>Emergency Person: </strong><?php echo $row['customer_detail'][0]['emergencyFirstname'] . " " . $row['customer_detail'][0]['emergencyLastname'] ?></p>
                                                <p><strong>Relationship: </strong><?php echo $row['customer_detail'][0]['relationship'] ?></p>
                                                <p><strong>Contact Number: </strong><?php echo $row['customer_detail'][0]['emergencyContactNumber'] ?></p>
                                                <p><strong>Conditions: </strong></p>
                                            <?php } ?>
                                            <?php
                                            if ($row['medication']) {
                                                foreach ($row['medication'] as $condition) {
                                            ?>

                                                    <p><?php echo $condition['meds'] ?></p>

                                                <?php }
                                            } else { ?> <p>No condition listed</p> <?php } ?>

                                            <p><strong>Medication:</strong></p>
                                            <?php
                                            if ($row['taken']) {
                                                foreach ($row['taken'] as $medication) {
                                            ?>

                                                    <p><?php echo $medication['taken'] ?></p>

                                                <?php }
                                            } else { ?> <p>No medication listed</p> <?php } ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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