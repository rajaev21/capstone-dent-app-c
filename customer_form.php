<?php
if (!$_GET['id']) {
  header('location:login.php');
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
  <style>
    #waiver {
      position: relative;
    }

    #canvasimg {
      position: relative;
      bottom: 50px;
      right: 10%;
      width: 10rem;
    }
  </style>
</head>

<body>
  <?php if (isset($_GET['id'])) {
    include("nav.php");

    $response = file_get_contents('http://localhost:5000/getCustomerProfile?id=' . $_GET['id']);
    $response = json_decode($response, true);
    if (count($response) > 0) {
      foreach ($response as $row) {
        $user = $row['user'];
        $firstName = $row['firstName'];
        $middleName = $row['middleName'];
        $lastName = $row['lastName'];
        $nickName = $row['nickName'];
        $address = $row['address'];
        $contactNumber = $row['contactNumber'];
        $facebook = $row['facebook'];
        $birthDay = $row['birthDay'];
        $nationality = $row['nationality'];
        $age = $row['age'];
        $gender = $row['gender'];
        $civilStatus = $row['civilStatus'];
        $occupation = $row['occupation'];
        $employer = $row['employer'];
        $clinic = $row['clinic'];
        $prevClinic = $row['prevClinic'];
        $emergencyFirstname = $row['emergencyFirstname'];
        $emergencyLastname = $row['emergencyLastname'];
        $relationship = $row['relationship'];
        $emergencyContactNumber = $row['emergencyContactNumber'];
        $username = $row['username'];
        $isBeingTreated = $row['isBeingTreated'];
        $isHospitalized = $row['isHospitalized'];
        $isAllergy = $row['isAllergy'];
        $menstrual = date("Y-m-d", strtotime($row['menstrual']));;
        $isPregnant = $row['isPregnant'];
        $isBreastfeeding = $row['isBreastfeeding'];
        $additionalInformation = $row['additionalInformation'];
        $last_updated = $row['last_updated'];
        $consentSignature = $row['signature'];

        if (!empty($row['medications'])) {
          $conditions = explode(',', $row['medications']);
        } else {
          $conditions = [];
        }

        if (!empty($row['taken_list'])) {
          $taken_list = explode(',', $row['taken_list']);
        } else {
          $taken_list = [];
        }
      }
    }
  } ?>

  <!-- Form Starts here -->


  <div class="container">
    <h3>Please Fill the form before proceeding </h3>
    <p> Last Updated:
      <?php
      $date = new DateTime($last_updated);
      echo $date->format("F d, Y h:i A");
      ?>
    </p>

    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">Check Form Submitted</button>

    <div class="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasScrollingLabel">Form Submitted</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <div class="container">
          <?php
          $signatures = file_get_contents('http://localhost:5000/getSignatures?id=' . $_GET['id']);
          $signatures = json_decode($signatures, true);
          ?>
          <?php if (count($signatures) > 0): ?>
            <div style="max-height: 300px; overflow-y: auto; padding-right: 5px;">
              <?php foreach ($signatures as $signature):
                $date = new DateTime($signature['created_at']);
                $formattedDate = $date->format("F d, Y h:i A");
              ?>
                <button class="btn btn-primary mb-2 w-100"
                  onclick="openWaiverModal('<?= urlencode($signature['signature']) ?>', '<?= $formattedDate ?>')">
                  <?= $formattedDate ?>
                </button>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <p>No signatures found.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>


    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel"></h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="container card p-5 my-3" id="waiver">
              <p class="lh-lg" id="waiverNote">
              <div style="font-family: 'Times New Roman', serif; line-height: 1.6;">

                <h3 style="text-align:center;">SALAPANTAN DENTAL CLINIC</h3>
                <p style="text-align:center;">Brgy. 6, Santiago St., San Miguel, Iloilo</p>

                <h2 style="text-align:center; text-decoration: underline;">WAIVER AND INFORMED CONSENT</h2>

                <p>
                  I, <?= $firstName . ' ' . $middleName . ' ' . $lastName ?>, here by acknowledge that Dr. <span class="doctor"></span>
                  and the dental team of <strong>Salapantan Dental Clinic</strong> have fully explained to me my current dental condition,
                  as well as the recommended treatment plan deemed appropriate for my case.
                </p>

                <p>
                  I further acknowledge that I have been adequately informed of the nature, purpose, benefits,
                  and potential risks associated with the recommended treatment. In addition, I have been made aware
                  of the possible consequences of refusing or delaying such treatment, which may include, but are not limited to:
                </p>

                <ul>
                  <li>Progression or worsening of my dental condition</li>
                  <li>Increased pain or discomfort</li>
                  <li>Infection or spread of disease</li>
                  <li>Tooth mobility or potential tooth loss</li>
                  <li>Increased future costs for corrective or more extensive treatment</li>
                </ul>

                <p>
                  Not withstanding the foregoing explanations, I hereby voluntarily and knowingly choose to decline or postpone
                  the recommended treatment at this time. I fully understand and accept that Salapantan Dental Clinic,
                  Dr. <span class="doctor"></span>, and all affiliated staff shall not be held liable or responsible
                  for any complications, progression of disease, or adverse outcomes that may arise as a result of my decision.
                </p>

                <p>By signing this waiver, I hereby affirm that:</p>

                <ul>
                  <li>I have been given sufficient opportunity to ask questions, and all my concerns have been addressed to my satisfaction.</li>
                  <li>I am making this decision freely, voluntarily, and without any form of pressure, coercion, or undue influence.</li>
                  <li>I accept full responsibility for any consequences resulting from my decision to decline or postpone the recommended treatment.</li>
                </ul>

                <br><br>

                <div style="display: flex; justify-content: space-between; width: 100%;">
                  <div>
                    <img id="waiverImg" style="width: 270px; height: 140px; position: absolute; left: 0px;">
                    <p>Signature over Printed Name:</p>
                    <p class="text-center"><?= $firstName . ' ' . $middleName . ' ' . $lastName ?></p>
                    <p>Date: <span class="date"></span> </p>
                  </div>
                  <div>
                    <p><strong>LEA GRACE S. SALAPANTAN, DMD</strong></p>
                    <p>Owner/Dentist</p>
                    <p>Date: <span class="date"></span> </p>
                  </div>
                </div>
              </div>
              </p>
            </div>
          </div>
          <div class="modal-footer">
            <div class="btn btn-secondary" id="closeButtonModal" data-bs-dismiss="modal">Close</div>
          </div>
        </div>
      </div>
    </div>

    <form action="./api/customerFormAPI.php?" method="GET">
      <div class="row">
        <div class="col-7">
          <div class="d-flex align-items-center justify-content-center bg-light m-4">
            <div class="card" style="width: 100rem;">
              <div class="card-body">
                <div class="card-title h3 fw-bold mb-0">Customer Details:</div>
                <div class="card-text mb-4">Insert appointment details.</div>
                <div class="card-text mb-4">Fields marked with <span class="fw-bold">(*)</span> are required.</div>
                <!-- personal information -->
                <div class="row m-3">
                  <div class="col">
                    <label for="firstName" class="form-label">* First Name:</label>
                    <input class="form-control" type="text" name="firstName" id="firstname" placeholder="Firstname" value="<?php echo $firstName ?>" <?= $_SESSION['role'] == "admin" ? "readonly" : "required" ?>>
                  </div>
                  <div class="col">
                    <label for="middleName" class="form-label">Middle name:</label>
                    <input class="form-control" type="text" name="middleName" id="middleName" placeholder="Middle name" value="<?php echo $middleName ?>" <?= $_SESSION['role'] == "admin" ? "readonly" : "" ?>>
                  </div>
                  <div class="col">
                    <label for="lastName" class="form-label">* Last Name:</label>
                    <input class="form-control" type="text" name="lastName" id="lastName" placeholder="Last name" value="<?php echo $lastName ?>" <?= $_SESSION['role'] == "admin" ? "readonly" : "required" ?>>
                  </div>
                  <div class="col">
                    <label for="nickName" class="form-label">Nickname:</label>
                    <input class="form-control" type="text" name="nickName" id="nickName" placeholder="Nickname" value="<?php echo $nickName ?>" <?= $_SESSION['role'] == "admin" ? "readonly" : "" ?>>
                  </div>
                </div>

                <div class="row m-3">
                  <div class="col">
                    <label for="address" class="form-label">* Address:</label>
                    <input class="form-control" type="text" name="address" id="address" placeholder="Address" value="<?php echo $address ?>" <?= $_SESSION['role'] == "admin" ? "readonly" : "required" ?>>
                  </div>
                  <div class="col">
                    <label for="contactNumber" class="form-label">* Contact Number:</label>
                    <input class="form-control" type="number" name="contactNumber" id="contactNumber" placeholder="Contact Number" value="<?php echo $contactNumber ?>" <?= $_SESSION['role'] == "admin" ? "readonly" : "required" ?>>
                  </div>
                </div>

                <div class="row m-3">
                  <div class="col">
                    <label for="facebook" class="form-label">Facebook / Messenger:</label>
                    <input class="form-control" type="facebook" name="facebook" id="facebook" placeholder="Facebook / Messenger" value="<?php echo $facebook ?>" <?= $_SESSION['role'] == "admin" ? "readonly" : "" ?>>
                  </div>
                </div>

                <div class="row m-3">
                  <div class="col">
                    <label for="birthDay" class="form-label">* Date of Birth:</label>

                    <input class="form-control" type="date" name="birthDay" id="birthDay" placeholder="Date of Birth" value="<?php echo date('Y-m-d', strtotime($birthDay)) ?>" <?= $_SESSION['role'] == "admin" ? "readonly" : "required" ?>>
                  </div>
                  <div class="col">
                    <label for="nationality" class="form-label">Nationality:</label>
                    <input class="form-control" type="text" name="nationality" id="nationality" placeholder="Nationality" value="<?php echo $nationality ?>" <?= $_SESSION['role'] == "admin" ? "readonly" : "" ?>>
                  </div>
                  <div class="col">
                    <label for="age" class="form-label">* Age:</label>
                    <input class="form-control" type="number" name="age" id="age" placeholder="Age" min="0" value="<?php echo $age ?>" <?= $_SESSION['role'] == "admin" ? "readonly" : "required" ?>>
                  </div>
                </div>

                <div class="row m-3">
                  <div class="col">
                    <label for="gender" class="form-label">* Gender:</label>
                    <select class="form-control" type="text" name="gender" id="gender" placeholder="Gender" onchange="genderChange(this)" <?= $_SESSION['role'] == "admin" ? "readonly" : "required" ?>>
                      <option value="male" <?= $gender == "male" ? "selected" : "" ?>>Male</option>
                      <option value="female" <?= $gender == "female" ? "selected" : "" ?>>Female</option>
                    </select>
                  </div>
                  <div class="col">
                    <label for="civilStatus" class="form-label">Civil Status:</label>
                    <input class="form-control" type="text" name="civilStatus" id="civilStatus" placeholder="Civil Status" value="<?php echo $civilStatus ?>" <?= $_SESSION['role'] == "admin" ? "readonly" : "" ?>>
                  </div>
                  <div class="col">
                    <label for="occupation" class="form-label">Occupation:</label>
                    <input class="form-control" type="text" name="occupation" id="occupation" placeholder="Occupation" value="<?php echo $occupation ?>" <?= $_SESSION['role'] == "admin" ? "readonly" : "" ?>>
                  </div>
                </div>

                <div class="row m-3">
                  <div class="col">
                    <label for="employer" class="form-label">Employer / School:</label>
                    <input class="form-control" type="text" name="employer" id="employer" placeholder="Employer / School" value="<?php echo $employer ?>" <?= $_SESSION['role'] == "admin" ? "readonly" : "" ?>>
                  </div>
                </div>

                <div class="row m-3">
                  <div class="col">
                    <label for="clinic" class="form-label">Medical Doctor's Name / Clinic:</label>
                    <input class="form-control" type="text" name="clinic" id="clinic" placeholder="Medical Doctor's Name / Clinic" value="<?php echo $clinic ?>" <?= $_SESSION['role'] == "admin" ? "readonly" : "" ?>>
                  </div>
                  <div class="col">
                    <label for="prevClinic" class="form-label">Previous Medical Doctor's Name / Clinic:</label>
                    <input class="form-control" type="text" name="prevClinic" id="prevClinic" placeholder="Previous Medical Doctor's Name / Clinic" value="<?php echo $prevClinic ?>" <?= $_SESSION['role'] == "admin" ? "readonly" : "" ?>>
                  </div>
                </div>

                <span class="fw-bold h5">Incase of Emergency:</span>
                <div class="row m-3">
                  <div class="col">
                    <label for="emergencyFirstname" class="form-label">* Firstname:</label>
                    <input class="form-control" type="text" name="emergencyFirstname" id="emergencyFirstname" placeholder="Firstname" required value="<?php echo $emergencyFirstname ?>" <?= $_SESSION['role'] == "admin" ? "readonly" : "" ?>>
                  </div>
                  <div class="col">
                    <label for="emergencyLastname" class="form-label">* Lastname:</label>
                    <input class="form-control" type="text" name="emergencyLastname" id="emergencyLastname" placeholder="Lastname" value="<?php echo $emergencyLastname ?>" <?= $_SESSION['role'] == "admin" ? "readonly" : "required" ?>>
                  </div>
                </div>

                <div class="row m-3">
                  <div class="col">
                    <label for="relationship" class="form-label">* Relationship:</label>
                    <input class="form-control" type="text" name="relationship" id="relationship" placeholder="Relationship" value="<?php echo $relationship ?>" <?= $_SESSION['role'] == "admin" ? "readonly" : "required" ?>>
                  </div>
                  <div class="col">
                    <label for="emergencyContactNumber" class="form-label">* Contact number:</label>
                    <input class="form-control" type="text" name="emergencyContactNumber" id="emergencyContactNumber" placeholder="Contact number" value="<?php echo $emergencyContactNumber ?>" <?= $_SESSION['role'] == "admin" ? "readonly" : "required" ?>>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- address -->
        <div class="col-5">
          <div class="d-flex align-items-center justify-content-center bg-light m-4">
            <div class="col">
              <div class="card-body p-2">
                <div class="card-title h3 fw-bold mb-0">conditions: (Optional)</div>
                <div class="card-text mb-4">Please check the only that apply.</div>
                <div class="row m-3">
                  <div class="row p-3">
                    <span class="fw-bold h5">Have you taken this conditions?</span>
                    <div class="form-check">
                      <label class="form-check-label" for="aspirin">
                        Aspirin (Aspilet, Cortal, Coplavix, etc.)
                      </label>
                      <input class="form-check-input" type="checkbox" value="aspirin" id="aspirin" name="taken[]" <?php if (in_array("aspirin", $taken_list)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="recreationalDrugs">
                        Recreational Drugs (Cocaine, Marijuana, etc.)
                      </label>
                      <input class="form-check-input" type="checkbox" value="recreationalDrugs" id="recreationalDrugs" name="taken[]" <?php if (in_array("recreationalDrugs", $taken_list)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="birthControl">
                        Birth control / Hormonal therapy (Thrust Daphne, Minipil, etc.)
                      </label>
                      <input class="form-check-input" type="checkbox" value="birthControl" id="birthControl" name="taken[]" <?php if (in_array("birthControl", $taken_list)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="narcotics">
                        Narcotics (Codeine, Narcotan, Methadone, etc.)
                      </label>
                      <input class="form-check-input" type="checkbox" value="narcotics" id="narcotics" name="taken[]" <?php if (in_array("narcotics", $taken_list)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="diabetes">
                        Diabetes Medication (Metformine, Janumete, etc.)
                      </label>
                      <input class="form-check-input" type="checkbox" value="diabetes" id="diabetes" name="taken[]" <?php if (in_array("diabetes", $taken_list)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="tranquilizers">
                        Tranquilizers / sleeping pills (vallium etc.)
                      </label>
                      <input class="form-check-input" type="checkbox" value="tranquilizers" id="tranquilizers" name="taken[]" <?php if (in_array("tranquilizers", $taken_list)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="steriods">
                        Sterioids (cortisone, prednosone, etc.)
                      </label>
                      <input class="form-check-input" type="checkbox" value="steriods" id="steriods" name="taken[]" <?php if (in_array("steriods", $taken_list)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="anticoagulants">
                        Anticoagulants(Noklot, Norplay, Coumandine, etc.)
                      </label>
                      <input class="form-check-input" type="checkbox" value="anticoagulants" id="anticoagulants" name="taken[]" <?php if (in_array("anticoagulants", $taken_list)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="antibiotics">
                        Antibiotics(Augmantin, Flagyi, Zithromax, etc.)
                      </label>
                      <input class="form-check-input" type="checkbox" value="antibiotics" id="antibiotics" name="taken[]" <?php if (in_array("antibiotics", $taken_list)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                  </div>


                  <span class="fw-bold h5">Have you had any of the following?</span>
                  <div class="row row-cols-2">
                    <div class="form-check">
                      <label class="form-check-label" for="asthma">
                        Asthma
                      </label>
                      <input class="form-check-input" type="checkbox" value="asthma" id="asthma" name="conditions[]" <?php if (in_array("asthma", $conditions)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="anemia">
                        Anemia
                      </label>
                      <input class="form-check-input" type="checkbox" value="anemia" id="anemia" name="conditions[]" <?php if (in_array("anemia", $conditions)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="arthritis">
                        Arthritis
                      </label>
                      <input class="form-check-input" type="checkbox" value="arthritis" id="arthritis" name="conditions[]" <?php if (in_array("arthritis", $conditions)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="heartAilments">
                        Hearth Ailments
                      </label>
                      <input class="form-check-input" type="checkbox" value="heartAilments" id="heartAilments" name="conditions[]" <?php if (in_array("heartAilments", $conditions)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="bloodDisorder">
                        Blood disorder
                      </label>
                      <input class="form-check-input" type="checkbox" value="bloodDisorder" id="bloodDisorder" name="conditions[]" <?php if (in_array("bloodDisorder", $conditions)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="diabetes">
                        Diabetes
                      </label>
                      <input class="form-check-input" type="checkbox" value="diabetes" id="diabetes" name="conditions[]" <?php if (in_array("diabetes", $conditions)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="epilepsy">
                        Epilepsy
                      </label>
                      <input class="form-check-input" type="checkbox" value="epilepsy" id="epilepsy" name="conditions[]" <?php if (in_array("epilepsy", $conditions)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="highblood">
                        HighBlood
                      </label>
                      <input class="form-check-input" type="checkbox" value="highblood" id="highblood" name="conditions[]" <?php if (in_array("highblood", $conditions)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="heartmurmurs">
                        Heart Murmurs
                      </label>
                      <input class="form-check-input" type="checkbox" value="heartmurmurs" id="heartmurmurs" name="conditions[]" <?php if (in_array("heartmurmurs", $conditions)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="cancer">
                        Malignancies or Cancer
                      </label>
                      <input class="form-check-input" type="checkbox" value="cancer" id="cancer" name="conditions[]" <?php if (in_array("cancer", $conditions)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="rheumatic">
                        Rheumatic Hearth Disease
                      </label>
                      <input class="form-check-input" type="checkbox" value="rheumatic" id="rheumatic" name="conditions[]" <?php if (in_array("rheumatic", $conditions)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="radiation">
                        Radiation Treatment
                      </label>
                      <input class="form-check-input" type="checkbox" value="radiation" id="radiation" name="conditions[]" <?php if (in_array("radiation", $conditions)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="stroke">
                        Stroke
                      </label>
                      <input class="form-check-input" type="checkbox" value="stroke" id="stroke" name="conditions[]" <?php if (in_array("stroke", $conditions)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="sinus">
                        Sinus
                      </label>
                      <input class="form-check-input" type="checkbox" value="sinus" id="sinus" name="conditions[]" <?php if (in_array("sinus", $conditions)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>

                    <div class="form-check">
                      <label class="form-check-label" for="hormonalDisorder">
                        Hormonal Disorder
                      </label>
                      <input class="form-check-input" type="checkbox" value="hormonalDisorder" id="hormonalDisorder" name="conditions[]" <?php if (in_array("hormonalDisorder", $conditions)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="stomachUlcer">
                        Stomach Ulcer
                      </label>
                      <input class="form-check-input" type="checkbox" value="stomachUlcer" id="stomachUlcer" name="conditions[]" <?php if (in_array("stomachUlcer", $conditions)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="kidney">
                        Kidney Disease
                      </label>
                      <input class="form-check-input" type="checkbox" value="kidney" id="kidney" name="conditions[]" <?php if (in_array("kidney", $conditions)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="neurological">
                        Neurogical Problem
                      </label>
                      <input class="form-check-input" type="checkbox" value="neurological" id="neurological" name="conditions[]" <?php if (in_array("neurological", $conditions)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="prostate">
                        Prostate Problem
                      </label>
                      <input class="form-check-input" type="checkbox" value="prostate" id="prostate" name="conditions[]" <?php if (in_array("prostate", $conditions)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="eyeDisorder">
                        Eye Disorder
                      </label>
                      <input class="form-check-input" type="checkbox" value="eyeDisorder" id="eyeDisorder" name="conditions[]" <?php if (in_array("eyeDisorder", $conditions)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="eatingDisorder">
                        Eating Disorder
                      </label>
                      <input class="form-check-input" type="checkbox" value="eatingDisorder" id="eatingDisorder" name="conditions[]" <?php if (in_array("eatingDisorder", $conditions)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="thyroid">
                        Thyroid Fever
                      </label>
                      <input class="form-check-input" type="checkbox" value="thyroid" id="thyroid" name="conditions[]" <?php if (in_array("thyroid", $conditions)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="tuberculosis">
                        Tuberculosis
                      </label>
                      <input class="form-check-input" type="checkbox" value="tuberculosis" id="tuberculosis" name="conditions[]" <?php if (in_array("tuberculosis", $conditions)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="veneral">
                        Veneral Disease
                      </label>
                      <input class="form-check-input" type="checkbox" value="veneral" id="veneral" name="conditions[]" <?php if (in_array("veneral", $conditions)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="hepatitis">
                        Hepatits
                      </label>
                      <input class="form-check-input" type="checkbox" value="hepatitis" id="hepatitis" name="conditions[]" <?php if (in_array("hepatitis", $conditions)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="herpes">
                        Herpes
                      </label>
                      <input class="form-check-input" type="checkbox" value="herpes" id="herpes" name="conditions[]" <?php if (in_array("herpes", $conditions)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="colitis">
                        Colitis
                      </label>
                      <input class="form-check-input" type="checkbox" value="colitis" id="colitis" name="conditions[]" <?php if (in_array("colitis", $conditions)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="aids">
                        Aids
                      </label>
                      <input class="form-check-input" type="checkbox" value="aids" id="aids" name="conditions[]" <?php if (in_array("aids", $conditions)) echo "checked"; ?> <?= $_SESSION['role'] == "admin" ? "disabled" : ""; ?>>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <input type="hidden" name="id" value="<?php echo $user ?>">

      <div class="row">
        <div class="col-6">
          <div class="card p-3 mb-3">
            <h5>Answer the following question to the best of your knowledge. All information will be considered strictly confidential.</h5>
            <div class="">Are you being treated by a doctor at the present time?</div>
            <label for="isBeingTreated" class="form-label">If yes, specify illness:</label>
            <input class="form-control" type="text" name="isBeingTreated" id="isBeingTreated" placeholder="Put 'no' if not" value="<?= $isBeingTreated ?>" <?= $_SESSION['role'] == "admin" ? "readonly" : "required" ?>>
            <div class="">Have you been seriously ill or hopitalized?</div>
            <label for="isHospitalized" class="form-label">If yes, specify illness:</label>
            <input class="form-control" type="text" name="isHospitalized" id="isHospitalized" placeholder="Put 'no' if not" value="<?= $isHospitalized ?>" <?= $_SESSION['role'] == "admin" ? "readonly" : "required" ?>>
            <div class="">Do you have any allergies or sensitivity to any medication, injections, food or materials?</div>
            <label for="isAllergy" class="form-label">If yes, specify illness:</label>
            <input class="form-control" type="text" name="isAllergy" id="isAllergy" placeholder="Put 'no' if not" value="<?= $isAllergy ?>" <?= $_SESSION['role'] == "admin" ? "readonly" : "required" ?>>
          </div>
        </div>

        <div class="col-6 <?= $gender == "female" ? "" : "d-none" ?>" id="forFemale">
          <div class="card p-3 mb-3">
            <div class="h5 text-center">For female patient</div>
            Date of last menstrual period:<input style="width: 40%" class="form-control" type="date" value="<?= $menstrual ? $menstrual : "" ?>" name="menstrual" id="menstrual" <?= $_SESSION['role'] == "admin" ? "readonly" : "required" ?>>
            Are you pregnant?
            <div class="form-check">
              <input class="form-check-input" type="radio" name="isPregnant" id="radioDefault1" value="1" <?= $_SESSION['role'] == "admin" ? "disabled" : "required" ?> <?= $isPregnant ? "checked" : "" ?>>
              <label class="form-check-label" for="radioDefault1">
                Yes
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="isPregnant" id="radioDefault2" value="0" <?= $_SESSION['role'] == "admin" ? "disabled" : "required" ?> <?= !$isPregnant ? "checked" : "" ?>>
              <label class="form-check-label" for="radioDefault2">
                No
              </label>
            </div>
            Are you breastfeading?
            <div class="form-check">
              <input class="form-check-input" type="radio" name="isBreastfeeding" id="radioDefault1" value="1" <?= $_SESSION['role'] == "admin" ? "disabled" : "required" ?> <?= $isBreastfeeding ? "checked" : "" ?>>
              <label class="form-check-label" for="radioDefault1">
                Yes
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="isBreastfeeding" id="radioDefault2" value="0" <?= $_SESSION['role'] == "admin" ? "disabled" : "required" ?> <?= !$isBreastfeeding ? "checked" : "" ?>>
              <label class="form-check-label" for="radioDefault2">
                No
              </label>
            </div>
            Addtional Information: <textarea class="form-control" name="additionalInformation" <?= $_SESSION['role'] == "admin" ? "readonly" : "" ?>><?= $additionalInformation ?></textarea>
          </div>
        </div>
      </div>

      <div class="container">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#consentModal">
          Click here to sign
        </button>

        <div class="modal fade" id="consentModal" tabindex="-1" aria-labelledby="consentModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="consentModalLabel">Signature here</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <canvas id="can" width="460" height="400" style="border:2px solid;"></canvas>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="closeButtonModal" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="save()">Save changes</button>
              </div>
            </div>
          </div>
        </div>
      </div>


      <div class="container" id="waiver">
        <p class="lh-lg">
          I understand that the above information was completed correctly and to the best
          of my knowledge; and thus i assume all risks arising from or connected with any
          ommission or interpretation of the same. I also understand that it is my responsibility
          to inform Salapantan Dental Clinic of any charges in the information that i have provided.
          I voluntarily entrust all my dental treatment to Salapantan Dental Clinic and confirm that
          I am consenting to all their dental procedures and clinical recommendations, being as I am
          at all times provided by Salapantan Dental Clinic with sufficient information to give
          my intelligent consent to the same. Having given my voluntary and intelligent consent
          to the same, I hold Salapantan Dental Clinic without responsible for any untoward claim,
          damage or liability in connection with such procedures and recommendations.
        </p>
        <div class="p m-5">Name here:<img id="canvasimg" src="<?= $consentSignature ?>"></div>

      </div>

      <h3>Privacy & Policy Agreement</h3>
      <p>
        Check the policy and agreement <a href="#" data-bs-toggle="modal" data-bs-target="#myModal">here</a> first to submit the form.
      </p>

      <div class="modal" id="myModal" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Privacy Policy and Terms of Use</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <h5 class="fw-bold">Introduction</h5>
                <p>
                  Welcome to <span class="fw-bold">DentApp</span>. We value your privacy and are committed to protecting your personal information. This Privacy Policy explains how we collect, use, store, and disclose your information when you use our services.
                </p>
              </div>
              <div class="mb-3">
                <h5 class="fw-bold">1. Information We Collect</h5>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item">
                    <span class="fw-bold">Personal Information:</span> such as your name, email address, contact details, and login credentials.
                  </li>
                </ul>
              </div>
              <div class="mb-3">
                <h5 class="fw-bold">2. How We Use Your Information</h5>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item">Provide and maintain our services.</li>
                  <li class="list-group-item">Communicate with you (e.g., send OTPs).</li>
                  <li class="list-group-item">Improve our platform and user experience.</li>
                  <li class="list-group-item">Ensure legal compliance and security.</li>
                </ul>
              </div>
              <div class="mb-3">
                <h5 class="fw-bold">3. Sharing Your Information</h5>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item">We do not sell your data.</li>
                  <li class="list-group-item">We may share information with service providers (e.g., email or hosting services).</li>
                  <li class="list-group-item">Legal authorities when required by law.</li>
                  <li class="list-group-item">Third parties with your consent.</li>
                </ul>
              </div>
              <div class="mb-3">
                <h5 class="fw-bold">4. Cookies and Tracking</h5>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item">Maintain session state.</li>
                  <li class="list-group-item">Analyze usage and improve performance.</li>
                  <li class="list-group-item">Personalize content.</li>
                </ul>
              </div>
              <div class="mb-3">
                <h5 class="fw-bold">5. Data Security</h5>
                <p>
                  We implement reasonable technical and organizational measures to protect your data. However, no method of transmission over the internet is 100% secure.
                </p>
              </div>
              <div class="mb-3">
                <h5 class="fw-bold">6. User Rights</h5>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item">Access, update, or delete your personal data.</li>
                  <li class="list-group-item">Withdraw consent at any time.</li>
                  <li class="list-group-item">Lodge a complaint with a data protection authority.</li>
                </ul>
              </div>
              <div class="mb-2">
                <h5 class="fw-bold">Contact Us</h5>
                <p>
                  If you have any questions about this policy, please contact us at:
                  <br>
                  <a href="mailto:salologankenneth23@gmail.com" class="text-decoration-none">salologankenneth23@gmail.com</a>
                </p>
              </div>
              <input type="checkbox" id="policyID" onchange="checkAgreement()">
              By checking the box, you agree to our terms and conditions,
              including the collection and processing of your personal data.
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
  </div>

  <div class="row p-5"><input class="btn btn-primary <?= $_SESSION['role'] == "admin" ? "d-none" : ""; ?>" type="submit" value="Submit" name="appoint" id="submitButton" disabled></div>
  </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

  <script>
    function openWaiverModal(signature, date) {
      const modal = new bootstrap.Modal(document.getElementById("exampleModal"));
      const canvasImg = document.getElementById("waiverImg");
      const modalLabel = document.getElementById("exampleModalLabel");
      canvasImg.src = decodeURIComponent(signature);

      modalLabel.textContent = date;

      modal.show();
    }

    function genderChange(e) {
      const forFemale = document.getElementById("forFemale");
      if (e.value === "female") {
        forFemale.classList.toggle("d-none", false)
      } else {
        forFemale.classList.toggle("d-none", true)
      }
    }

    var customerID = `<?= $_GET['id']; ?>`
    var canvas, ctx, flag = false,
      prevX = 0,
      currX = 0,
      prevY = 0,
      currY = 0,
      dot_flag = false;

    var x = "black",
      y = 2;

    function init() {
      canvas = document.getElementById('can');
      ctx = canvas.getContext("2d");
      w = canvas.width;
      h = canvas.height;

      canvas.addEventListener("mousemove", e => findxy('move', e), false);
      canvas.addEventListener("mousedown", e => findxy('down', e), false);
      canvas.addEventListener("mouseup", e => findxy('up', e), false);
      canvas.addEventListener("mouseout", e => findxy('out', e), false);
    }

    document.addEventListener('shown.bs.modal', function(event) {
      if (event.target.id === 'consentModal') {
        init();
      }
    });
    document.addEventListener('hidden.bs.modal', function(event) {
      if (event.target.id === 'consentModal') {
        erase();
      }
    });

    function draw() {
      ctx.beginPath();
      ctx.moveTo(prevX, prevY);
      ctx.lineTo(currX, currY);
      ctx.strokeStyle = x;
      ctx.lineWidth = y;
      ctx.stroke();
      ctx.closePath();
    }

    function save() {
      const dataURL = canvas.toDataURL("image/png");

      fetch("http://localhost:5000/setConsentSignature", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify({
            signature: dataURL,
            user_id: customerID
          })
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            document.getElementById("canvasimg").src = dataURL;
            checkAgreement()
            document.getElementById("closeButtonModal").click();
          } else {
            console.error("Save failed:", data.message);
          }
        })
        .catch(err => console.error(err));
    }

    function erase() {
      ctx.clearRect(0, 0, w, h);
    }

    function findxy(res, e) {
      const rect = canvas.getBoundingClientRect();
      if (res == 'down') {
        prevX = currX;
        prevY = currY;
        currX = e.clientX - rect.left;
        currY = e.clientY - rect.top;

        flag = true;
        dot_flag = true;
        if (dot_flag) {
          ctx.beginPath();
          ctx.fillStyle = x;
          ctx.fillRect(currX, currY, 2, 2);
          ctx.closePath();
          dot_flag = false;
        }
      }
      if (res == 'up' || res == "out") flag = false;
      if (res == 'move' && flag) {
        prevX = currX;
        prevY = currY;
        currX = e.clientX - rect.left;
        currY = e.clientY - rect.top;
        draw();
      }
    }

    function checkAgreement() {
      const canvasimg = document.getElementById("canvasimg")
      const policyID = document.getElementById("policyID");
      const imgsrc = canvasimg.getAttribute("src")
      if (policyID.checked && imgsrc) {
        document.getElementById("submitButton").disabled = false;
      } else {
        document.getElementById("submitButton").disabled = true;
      }
    }
  </script>
</body>

</html>