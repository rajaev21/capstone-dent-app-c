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
  <style>
    .back-tooth,
    .front-tooth {
      position: relative;
      width: 60px;
      height: 30px;
      background-color: blue;
    }

    .back-tooth #quad1 {
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      position: absolute;
      clip-path: polygon(0% 5%, 20% 29%, 20% 75%, 0% 95%);
      background-color: white;
    }

    .back-tooth #quad2 {
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      position: absolute;
      clip-path: polygon(0 0, 100% 0, 75% 25%, 25% 25%);
      background-color: white;
    }

    .back-tooth #quad3 {
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      position: absolute;
      clip-path: polygon(80% 30%, 100% 5%, 100% 95%, 80% 75%);
      background-color: white;
    }

    .back-tooth #quad4 {
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      position: absolute;
      clip-path: polygon(25% 80%, 75% 80%, 100% 100%, 0% 100%);
      background-color: white;
    }

    .back-tooth #quad5 {
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      position: absolute;
      clip-path: polygon(25% 30%, 75% 30%, 75% 75%, 25% 75%);
      background-color: white;
    }

    .front-tooth #quad1 {
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      position: absolute;
      clip-path: polygon(0 0, 25% 50%, 25% 50%, 0 100%);
      background-color: white;
    }

    .front-tooth #quad2 {
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      position: absolute;
      clip-path: polygon(5% 0%, 95% 0%, 70% 45%, 30% 45%);
      background-color: white;
    }

    .front-tooth #quad3 {
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      position: absolute;
      clip-path: polygon(100% 0%, 100% 100%, 75% 50%, 75% 50%);
      background-color: white;
    }

    .front-tooth #quad4 {
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      position: absolute;
      clip-path: polygon(5% 100%, 30% 50%, 70% 50%, 95% 100%);
      background-color: white;
    }

    #legends {
      width: 11rem;
      height: 12rem;
      position: absolute;
      top: 5px;
      right: 1rem;
    }
  </style>
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
        <!-- <?php include('dental-chart.php') ?> -->

        <div class="container mt-5">
          <?php include('services.php') ?>

          <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#finish">
            Finish Appointment
          </button>
          <div class="modal fade" id="finish" tabindex="-1" aria-labelledby="finishLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="finishLabel">Finish Appointment</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-footer">
                  <form action="./api/finishAppointment.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $_GET['aid'] ?>">
                    <input type="hidden" name="admin_id" value="<?php echo $_SESSION['id'] ?>">
                    <input type="hidden" name="user_id" value="<?php echo $response[0]['user_id'] ?>">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" name="finish" value="Finish Appointment" class="btn btn-success">
                  </form>
                </div>
              </div>
            </div>
          </div>


          <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancel">
            Cancel Appointment
          </button>
          <div class="modal fade" id="cancel" tabindex="-1" aria-labelledby="cancelLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="cancelLabel">Cancel Appointment</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="./api/finishAppointment.php" method="POST">
                  <input type="hidden" name="id" value="<?php echo $_GET['aid'] ?>">
                  <input type="hidden" name="admin_id" value="<?php echo $_SESSION['id'] ?>">
                  <input type="hidden" name="user_id" value="<?php echo $response[0]['user_id'] ?>">
                  <div class="modal-body">
                    <div class="p-1">
                      <label class="form-label d-flex justify-content-start" for="reason">Reason for cancellation:</label>
                      <textarea class="form-control" name="reason" id="reason" row="4" required></textarea>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" name="cancel" value="Cancel Appointment" class="btn btn-danger">
                  </div>
                </form>
              </div>
            </div>
          </div>
        <?php } ?>
      <?php } ?>

      <script>
      </script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>