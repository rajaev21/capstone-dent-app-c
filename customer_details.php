<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('Location:./login.php?response=Please log in again');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Form</title>
    <!-- BS css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- BS icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <?php
    include('nav.php');
    ?>
    <div class="container py-5">
        <?php if (isset($_GET['aid'])) {
            $response = file_get_contents('http://localhost:5000/getAppointedCustomer?aid=' . $_GET['aid']);
            $response = json_decode($response, true);

            if (count($response) > 0) {
        ?>
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white d-flex align-items-center">
                        <i class="bi bi-person-circle fs-2 me-3"></i>
                        <h4 class="mb-0">Customer Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Basic Info -->
                            <div class="col-md-4">
                                <div class="border rounded p-3 bg-white h-100">
                                    <div class="fw-bold text-secondary mb-2">First Name</div>
                                    <div><?= ucfirst($response[0]['firstname']) ?></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3 bg-white h-100">
                                    <div class="fw-bold text-secondary mb-2">Last Name</div>
                                    <div><?= ucfirst($response[0]['lastname']) ?></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3 bg-white h-100">
                                    <div class="fw-bold text-secondary mb-2">Nickname</div>
                                    <div><?= ucfirst($response[0]['nickname']) ?></div>
                                </div>
                            </div>

                            <!-- Contact Info -->
                            <div class="col-md-6">
                                <div class="border rounded p-3 bg-white h-100">
                                    <div class="fw-bold text-secondary mb-2">Address</div>
                                    <div><?= $response[0]['address'] ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3 bg-white h-100">
                                    <div class="fw-bold text-secondary mb-2">Contact Number</div>
                                    <div><?= $response[0]['contactNumber'] ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3 bg-white h-100">
                                    <div class="fw-bold text-secondary mb-2">Facebook</div>
                                    <div><?= $response[0]['facebook'] ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3 bg-white h-100">
                                    <div class="fw-bold text-secondary mb-2">Email</div>
                                    <div><?= $response[0]['email'] ?></div>
                                </div>
                            </div>

                            <!-- Personal Info -->
                            <div class="col-md-4">
                                <div class="border rounded p-3 bg-white h-100">
                                    <div class="fw-bold text-secondary mb-2">Date of Birth</div>
                                    <div><?= date('d, M Y', strtotime($response[0]['birthDay'])) ?></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3 bg-white h-100">
                                    <div class="fw-bold text-secondary mb-2">Nationality</div>
                                    <div><?= $response[0]['nationality'] ?></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3 bg-white h-100">
                                    <div class="fw-bold text-secondary mb-2">Age</div>
                                    <div><?= $response[0]['age'] ?></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3 bg-white h-100">
                                    <div class="fw-bold text-secondary mb-2">Gender</div>
                                    <div><?= $response[0]['gender'] ?></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3 bg-white h-100">
                                    <div class="fw-bold text-secondary mb-2">Civil Status</div>
                                    <div><?= $response[0]['civilStatus'] ?></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3 bg-white h-100">
                                    <div class="fw-bold text-secondary mb-2">Occupation</div>
                                    <div><?= $response[0]['occupation'] ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3 bg-white h-100">
                                    <div class="fw-bold text-secondary mb-2">Employer / School</div>
                                    <div><?= $response[0]['employer'] ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3 bg-white h-100">
                                    <div class="fw-bold text-secondary mb-2">Medical Doctor</div>
                                    <div><?= $response[0]['clinic'] ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3 bg-white h-100">
                                    <div class="fw-bold text-secondary mb-2">Previous Doctor</div>
                                    <div><?= $response[0]['prevClinic'] ?></div>
                                </div>
                            </div>

                            <!-- Emergency Contact -->
                            <div class="col-12">
                                <hr class="my-4">
                                <h5 class="text-primary"><i class="bi bi-exclamation-triangle me-2"></i>In Case of Emergency</h5>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3 bg-white h-100">
                                    <div class="fw-bold text-secondary mb-2">First Name</div>
                                    <div><?= $response[0]['emergencyFirstname'] ?></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3 bg-white h-100">
                                    <div class="fw-bold text-secondary mb-2">Last Name</div>
                                    <div><?= $response[0]['emergencyLastname'] ?></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3 bg-white h-100">
                                    <div class="fw-bold text-secondary mb-2">Relationship</div>
                                    <div><?= $response[0]['relationship'] ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3 bg-white h-100">
                                    <div class="fw-bold text-secondary mb-2">Contact Number</div>
                                    <div><?= $response[0]['emergencyContactNumber'] ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="my-4">
                    <h2 class="mb-4">Findings</h2>
                    <div class="text-center mb-4">
                        <img src="img/tooth_chart.jpg" alt="Tooth Chart" class="img-fluid">
                    </div>
                    <form action="./api/setFindings.php" method="post">
                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <label for="tooth1" class="form-label">Tooth 1</label>
                                <input type="text" class="form-control" id="tooth1" name="tooth1" value="<?php echo $response[0]['tooth1'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth2" class="form-label">Tooth 2</label>
                                <input type="text" class="form-control" id="tooth2" name="tooth2" value="<?php echo $response[0]['tooth2'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth3" class="form-label">Tooth 3</label>
                                <input type="text" class="form-control" id="tooth3" name="tooth3" value="<?php echo $response[0]['tooth3'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth4" class="form-label">Tooth 4</label>
                                <input type="text" class="form-control" id="tooth4" name="tooth4" value="<?php echo $response[0]['tooth4'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth5" class="form-label">Tooth 5</label>
                                <input type="text" class="form-control" id="tooth5" name="tooth5" value="<?php echo $response[0]['tooth5'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth6" class="form-label">Tooth 6</label>
                                <input type="text" class="form-control" id="tooth6" name="tooth6" value="<?php echo $response[0]['tooth6'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth7" class="form-label">Tooth 7</label>
                                <input type="text" class="form-control" id="tooth7" name="tooth7" value="<?php echo $response[0]['tooth7'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth8" class="form-label">Tooth 8</label>
                                <input type="text" class="form-control" id="tooth8" name="tooth8" value="<?php echo $response[0]['tooth8'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth9" class="form-label">Tooth 9</label>
                                <input type="text" class="form-control" id="tooth9" name="tooth9" value="<?php echo $response[0]['tooth9'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth10" class="form-label">Tooth 10</label>
                                <input type="text" class="form-control" id="tooth10" name="tooth10" value="<?php echo $response[0]['tooth10'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth11" class="form-label">Tooth 11</label>
                                <input type="text" class="form-control" id="tooth11" name="tooth11" value="<?php echo $response[0]['tooth11'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth12" class="form-label">Tooth 12</label>
                                <input type="text" class="form-control" id="tooth12" name="tooth12" value="<?php echo $response[0]['tooth12'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth13" class="form-label">Tooth 13</label>
                                <input type="text" class="form-control" id="tooth13" name="tooth13" value="<?php echo $response[0]['tooth13'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth14" class="form-label">Tooth 14</label>
                                <input type="text" class="form-control" id="tooth14" name="tooth14" value="<?php echo $response[0]['tooth14'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth15" class="form-label">Tooth 15</label>
                                <input type="text" class="form-control" id="tooth15" name="tooth15" value="<?php echo $response[0]['tooth15'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth16" class="form-label">Tooth 16</label>
                                <input type="text" class="form-control" id="tooth16" name="tooth16" value="<?php echo $response[0]['tooth16'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth17" class="form-label">Tooth 17</label>
                                <input type="text" class="form-control" id="tooth17" name="tooth17" value="<?php echo $response[0]['tooth17'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth18" class="form-label">Tooth 18</label>
                                <input type="text" class="form-control" id="tooth18" name="tooth18" value="<?php echo $response[0]['tooth18'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth19" class="form-label">Tooth 19</label>
                                <input type="text" class="form-control" id="tooth19" name="tooth19" value="<?php echo $response[0]['tooth19'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth20" class="form-label">Tooth 20</label>
                                <input type="text" class="form-control" id="tooth20" name="tooth20" value="<?php echo $response[0]['tooth20'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth21" class="form-label">Tooth 21</label>
                                <input type="text" class="form-control" id="tooth21" name="tooth21" value="<?php echo $response[0]['tooth21'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth22" class="form-label">Tooth 22</label>
                                <input type="text" class="form-control" id="tooth22" name="tooth22" value="<?php echo $response[0]['tooth22'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth23" class="form-label">Tooth 23</label>
                                <input type="text" class="form-control" id="tooth23" name="tooth23" value="<?php echo $response[0]['tooth23'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth24" class="form-label">Tooth 24</label>
                                <input type="text" class="form-control" id="tooth24" name="tooth24" value="<?php echo $response[0]['tooth24'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth25" class="form-label">Tooth 25</label>
                                <input type="text" class="form-control" id="tooth25" name="tooth25" value="<?php echo $response[0]['tooth25'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth26" class="form-label">Tooth 26</label>
                                <input type="text" class="form-control" id="tooth26" name="tooth26" value="<?php echo $response[0]['tooth26'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth27" class="form-label">Tooth 27</label>
                                <input type="text" class="form-control" id="tooth27" name="tooth27" value="<?php echo $response[0]['tooth27'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth28" class="form-label">Tooth 28</label>
                                <input type="text" class="form-control" id="tooth28" name="tooth28" value="<?php echo $response[0]['tooth28'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth29" class="form-label">Tooth 29</label>
                                <input type="text" class="form-control" id="tooth29" name="tooth29" value="<?php echo $response[0]['tooth29'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth30" class="form-label">Tooth 30</label>
                                <input type="text" class="form-control" id="tooth30" name="tooth30" value="<?php echo $response[0]['tooth30'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth31" class="form-label">Tooth 31</label>
                                <input type="text" class="form-control" id="tooth31" name="tooth31" value="<?php echo $response[0]['tooth31'] ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="tooth32" class="form-label">Tooth 32</label>
                                <input type="text" class="form-control" id="tooth32" name="tooth32" value="<?php echo $response[0]['tooth32'] ?>">
                            </div>
                        </div>
                        <input type="hidden" name="user_id" value="<?php echo $response[0]['user_id'] ?>">
                        <input type="hidden" name="aid" value="<?php echo $_GET['aid'] ?>">
                        <button type="submit" class="btn btn-primary mt-4">Submit Findings</button>
                    </form>
                </div>

                <div class="container mt-5">
                    <div class="row fw-bold border-bottom pb-2 mb-2">
                        <h4 class="col-4 mb-4"> Dental Services & Prices</h4>

                        <div class="">
                            <form action="./api/submitAdditionalServices.php" method="post">
                                <label for="services" class="form-label">Additional Services</label>
                                <select name="additionalServices" id="services">
                                    <?php
                                    $services = file_get_contents('http://localhost:5000/getServices?');
                                    $services = json_decode($services, true);
                                    foreach ($services as $result) {
                                        echo '<option value="' . $result['id'] . '">' . $result['service_type'] . '</option>';
                                    }
                                    ?>
                                </select>
                                <input type="hidden" name="user_id" value="<?php echo $response[0]['user_id'] ?>">
                                <input type="hidden" name="appointment_id" value="<?php echo $_GET['aid'] ?>">
                                <input type="submit" name="submit" class="btn btn-primary" value="Add Service">

                            </form>
                        </div>
                    </div>

                    <div class="row fw-bold border-bottom pb-2 mb-2">
                        <div class="col-8">Service Type</div>
                        <div class="col-4 text-end">Price (PHP)</div>
                    </div>
                    <?php
                    $services = file_get_contents('http://localhost:5000/getCustomerServices?appointment_id=' . $_GET['aid']);
                    $services = json_decode($services, true);
                    foreach ($services as $service) {
                    ?>
                        <div class="row mb-2">
                            <div class="col-8"><?php echo $service['serviceType'] ?></div>
                            <div class="col-4 text-end"><?php echo $service['servicePrice'] ?></div>
                        </div>
                    <?php } ?>
                    <div class="row fw-bold border-top pt-2 mt-2">
                        <div class="col-8">Total</div>
                        <div class="col-4 text-end">
                            <?php
                            $total = 0;
                            foreach ($services as $service) {
                                $total += $service['servicePrice'];
                            }
                            echo $total;
                            ?>

                        </div>
                    </div>
                    <div class="row">
                        <form action="./api/finishAppointment.php" method="POST" class="d-flex justify-content-between">
                            <input type="hidden" name="id" value="<?php echo $_GET['aid'] ?>">
                            <input type="hidden" name="admin_id" value="<?php echo $_SESSION['id'] ?>">
                            <input type="hidden" name="user_id" value="<?php echo $response[0]['user_id'] ?>">
                            <div>
                                <input type="submit" name="finish" value="Finish Appointment" class="btn btn-success">
                            </div>

                            <div>
                                <input type="submit" name="cancel" value="Cancel Appointment" class="btn btn-danger">
                            </div>
                        </form>
                    </div>

                <?php } ?>
            <?php } ?>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
            <!-- <script>
                document.addEventListener("DOMContentLoaded", () => {
                    function requestUpdate(aid, action) {
                        fetch('http://localhost:5000/requestFeedback?aid=' + aid + '&action=' + action)
                            .then(response => response.json())
                            .catch((error) => {
                                console.error('Error:', error);
                            });
                    }
                });
            </script> -->
</body>

</html>