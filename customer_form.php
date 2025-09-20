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
                    <input class="form-control" type="text" name="firstName" id="firstname" placeholder="Firstname" value="<?php echo $firstName ?>" required>
                  </div>
                  <div class="col">
                    <label for="middleName" class="form-label">Middle name:</label>
                    <input class="form-control" type="text" name="middleName" id="middleName" placeholder="Middle name" value="<?php echo $middleName ?>">
                  </div>
                  <div class="col">
                    <label for="lastName" class="form-label">* Last Name:</label>
                    <input class="form-control" type="text" name="lastName" id="lastName" placeholder="Last name" value="<?php echo $lastName ?>" required>
                  </div>
                  <div class="col">
                    <label for="nickName" class="form-label">Nickname:</label>
                    <input class="form-control" type="text" name="nickName" id="nickName" placeholder="Nickname" value="<?php echo $nickName ?>">
                  </div>
                </div>

                <div class="row m-3">
                  <div class="col">
                    <label for="address" class="form-label">* Address:</label>
                    <input class="form-control" type="text" name="address" id="address" placeholder="Address" value="<?php echo $address ?>" required>
                  </div>
                  <div class="col">
                    <label for="contactNumber" class="form-label">* Contact Number:</label>
                    <input class="form-control" type="number" name="contactNumber" id="contactNumber" placeholder="Contact Number" value="<?php echo $contactNumber ?>" required>
                  </div>
                </div>

                <div class="row m-3">
                  <div class="col">
                    <label for="facebook" class="form-label">Facebook / Messenger:</label>
                    <input class="form-control" type="facebook" name="facebook" id="facebook" placeholder="Facebook / Messenger" value="<?php echo $facebook ?>">
                  </div>
                </div>

                <div class="row m-3">
                  <div class="col">
                    <label for="birthDay" class="form-label">* Date of Birth:</label>

                    <input class="form-control" type="date" name="birthDay" id="birthDay" placeholder="Date of Birth" value="<?php echo date('Y-m-d', strtotime($birthDay)) ?>" required>
                  </div>
                  <div class="col">
                    <label for="nationality" class="form-label">Nationality:</label>
                    <input class="form-control" type="text" name="nationality" id="nationality" placeholder="Nationality" value="<?php echo $nationality ?>">
                  </div>
                  <div class="col">
                    <label for="age" class="form-label">* Age:</label>
                    <input class="form-control" type="number" name="age" id="age" placeholder="Age" value="<?php echo $age ?>" required>
                  </div>
                </div>

                <div class="row m-3">
                  <div class="col">
                    <label for="gender" class="form-label">* Gender:</label>
                    <select class="form-control" type="text" name="gender" id="gender" placeholder="Gender" value="<?php echo $gender ?>" required>
                      <option value="male">Male</option>
                      <option value="female">Female</option>
                    </select>
                  </div>
                  <div class="col">
                    <label for="civilStatus" class="form-label">Civil Status:</label>
                    <input class="form-control" type="text" name="civilStatus" id="civilStatus" placeholder="Civil Status" value="<?php echo $civilStatus ?>">
                  </div>
                  <div class="col">
                    <label for="occupation" class="form-label">Occupation:</label>
                    <input class="form-control" type="text" name="occupation" id="occupation" placeholder="Occupation" value="<?php echo $occupation ?>">
                  </div>
                </div>

                <div class="row m-3">
                  <div class="col">
                    <label for="employer" class="form-label">Employer / School:</label>
                    <input class="form-control" type="text" name="employer" id="employer" placeholder="Employer / School" value="<?php echo $employer ?>">
                  </div>
                </div>

                <div class="row m-3">
                  <div class="col">
                    <label for="clinic" class="form-label">Medical Doctor's Name / Clinic:</label>
                    <input class="form-control" type="text" name="clinic" id="clinic" placeholder="Medical Doctor's Name / Clinic" value="<?php echo $clinic ?>">
                  </div>
                  <div class="col">
                    <label for="prevClinic" class="form-label">Previous Medical Doctor's Name / Clinic:</label>
                    <input class="form-control" type="text" name="prevClinic" id="prevClinic" placeholder="Previous Medical Doctor's Name / Clinic" value="<?php echo $prevClinic ?>">
                  </div>
                </div>

                <span class="fw-bold h5">Incase of Emergency:</span>
                <div class="row m-3">
                  <div class="col">
                    <label for="emergencyFirstname" class="form-label">* Firstname:</label>
                    <input class="form-control" type="text" name="emergencyFirstname" id="emergencyFirstname" placeholder="Firstname" required value="<?php echo $emergencyFirstname ?>">
                  </div>
                  <div class="col">
                    <label for="emergencyLastname" class="form-label">* Lastname:</label>
                    <input class="form-control" type="text" name="emergencyLastname" id="emergencyLastname" placeholder="Lastname" value="<?php echo $emergencyLastname ?>" required>
                  </div>
                </div>

                <div class="row m-3">
                  <div class="col">
                    <label for="relationship" class="form-label">* Relationship:</label>
                    <input class="form-control" type="text" name="relationship" id="relationship" placeholder="Relationship" value="<?php echo $relationship ?>" required>
                  </div>
                  <div class="col">
                    <label for="emergencyContactNumber" class="form-label">* Contact number:</label>
                    <input class="form-control" type="text" name="emergencyContactNumber" id="emergencyContactNumber" placeholder="Contact number" value="<?php echo $emergencyContactNumber ?>" required>
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
                      <input class="form-check-input" type="checkbox" value="aspirin" id="aspirin" name="taken[]" <?php if (in_array("aspirin", $taken_list)) echo "checked"; ?>>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="recreationalDrugs" id="recreationalDrugs" name="taken[]" <?php if (in_array("recreationalDrugs", $taken_list)) echo "checked"; ?>>
                      <label class="form-check-label" for="recreationalDrugs">
                        Recreational Drugs (Cocaine, Marijuana, etc.)
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="birthControl" id="birthControl" name="taken[]" <?php if (in_array("birthControl", $taken_list)) echo "checked"; ?>>
                      <label class="form-check-label" for="birthControl">
                        Birth control / Hormonal therapy (Thrust Daphne, Minipil, etc.)
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="narcotics" id="narcotics" name="taken[]" <?php if (in_array("narcotics", $taken_list)) echo "checked"; ?>>
                      <label class="form-check-label" for="narcotics">
                        Narcotics (Codeine, Narcotan, Methadone, etc.)
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="diabetes" id="diabetes" name="taken[]" <?php if (in_array("diabetes", $taken_list)) echo "checked"; ?>>
                      <label class="form-check-label" for="diabetes">
                        Diabetes Medication (Metformine, Janumete, etc.)
                      </label>
                    </div>
                  </div>


                  <span class="fw-bold h5">Have you had any of the following?</span>
                  <div class="row">
                    <!-- column 1 -->
                    <div class="col">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="asthma" id="asthma" name="conditions[]" <?php if (in_array("asthma", $conditions)) echo "checked"; ?>>
                        <label class="form-check-label" for="asthma">
                          Asthma
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="anemia" id="anemia" name="conditions[]" <?php if (in_array("anemia", $conditions)) echo "checked"; ?>>
                        <label class="form-check-label" for="anemia">
                          Anemia
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="arthritis" id="arthritis" name="conditions[]" <?php if (in_array("arthritis", $conditions)) echo "checked"; ?>>
                        <label class="form-check-label" for="arthritis">
                          Arthritis
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="heartAilments" id="heartAilments" name="conditions[]" <?php if (in_array("heartAilments", $conditions)) echo "checked"; ?>>
                        <label class="form-check-label" for="heartAilments">
                          Hearth Ailments
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="bloodDisorder" id="bloodDisorder" name="conditions[]" <?php if (in_array("bloodDisorder", $conditions)) echo "checked"; ?>>
                        <label class="form-check-label" for="bloodDisorder">
                          Blood disorder
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="diabetes" id="diabetes" name="conditions[]" <?php if (in_array("diabetes", $conditions)) echo "checked"; ?>>
                        <label class="form-check-label" for="diabetes">
                          Diabetes
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="epilepsy" id="epilepsy" name="conditions[]" <?php if (in_array("epilepsy", $conditions)) echo "checked"; ?>>
                        <label class="form-check-label" for="epilepsy">
                          Epilepsy
                        </label>
                      </div>
                    </div>

                    <!-- column 2 -->
                    <div class="col">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="highblood" id="highblood" name="conditions[]" <?php if (in_array("highblood", $conditions)) echo "checked"; ?>>
                        <label class="form-check-label" for="highblood">
                          HighBlood
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="heartmurmurs" id="heartmurmurs" name="conditions[]" <?php if (in_array("heartmurmurs", $conditions)) echo "checked"; ?>>
                        <label class="form-check-label" for="heartmurmurs">
                          Heart Murmurs
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="cancer" id="cancer" name="conditions[]" <?php if (in_array("cancer", $conditions)) echo "checked"; ?>>
                        <label class="form-check-label" for="cancer">
                          Malignancies or Cancer
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="rheumatic" id="rheumatic" name="conditions[]" <?php if (in_array("rheumatic", $conditions)) echo "checked"; ?>>
                        <label class="form-check-label" for="rheumatic">
                          Rheumatic Hearth Disease
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="radiation" id="radiation" name="conditions[]" <?php if (in_array("radiation", $conditions)) echo "checked"; ?>>
                        <label class="form-check-label" for="radiation">
                          Radiation Treatment
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="stroke" id="stroke" name="conditions[]" <?php if (in_array("stroke", $conditions)) echo "checked"; ?>>
                        <label class="form-check-label" for="stroke">
                          Stroke
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="sinus" id="sinus" name="conditions[]" <?php if (in_array("sinus", $conditions)) echo "checked"; ?>>
                        <label class="form-check-label" for="sinus">
                          Sinus
                        </label>
                      </div>
                    </div>

                    <!-- column 3 -->
                    <div class="col">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="hormonalDisorder" id="hormonalDisorder" name="conditions[]" <?php if (in_array("hormonalDisorder", $conditions)) echo "checked"; ?>>
                        <label class="form-check-label" for="hormonalDisorder">
                          Hormonal Disorder
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="stomachUlcer" id="stomachUlcer" name="conditions[]" <?php if (in_array("stomachUlcer", $conditions)) echo "checked"; ?>>
                        <label class="form-check-label" for="stomachUlcer">
                          Stomach Ulcer
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="kidney" id="kidney" name="conditions[]" <?php if (in_array("kidney", $conditions)) echo "checked"; ?>>
                        <label class="form-check-label" for="kidney">
                          Kidney Disease
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="neurological" id="neurological" name="conditions[]" <?php if (in_array("neurological", $conditions)) echo "checked"; ?>>
                        <label class="form-check-label" for="neurological">
                          Neurogical Problem
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="prostate" id="prostate" name="conditions[]" <?php if (in_array("prostate", $conditions)) echo "checked"; ?>>
                        <label class="form-check-label" for="prostate">
                          Prostate Problem
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="eyeDisorder" id="eyeDisorder" name="conditions[]" <?php if (in_array("eyeDisorder", $conditions)) echo "checked"; ?>>
                        <label class="form-check-label" for="eyeDisorder">
                          Eye Disorder
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="eatingDisorder" id="eatingDisorder" name="conditions[]" <?php if (in_array("eatingDisorder", $conditions)) echo "checked"; ?>>
                        <label class="form-check-label" for="eatingDisorder">
                          Eating Disorder
                        </label>
                      </div>
                    </div>

                    <!-- column 4 -->
                    <div class="col">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="thyroid" id="thyroid" name="conditions[]" <?php if (in_array("thyroid", $conditions)) echo "checked"; ?>>
                        <label class="form-check-label" for="thyroid">
                          Thyroid Fever
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="tuberculosis" id="tuberculosis" name="conditions[]" <?php if (in_array("tuberculosis", $conditions)) echo "checked"; ?>>
                        <label class="form-check-label" for="tuberculosis">
                          Tuberculosis
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="veneral" id="veneral" name="conditions[]" <?php if (in_array("veneral", $conditions)) echo "checked"; ?>>
                        <label class="form-check-label" for="veneral">
                          Veneral Disease
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="hepatitis" id="hepatitis" name="conditions[]" <?php if (in_array("hepatitis", $conditions)) echo "checked"; ?>>
                        <label class="form-check-label" for="hepatitis">
                          Hepatits
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="herpes" id="herpes" name="conditions[]" <?php if (in_array("herpes", $conditions)) echo "checked"; ?>>
                        <label class="form-check-label" for="herpes">
                          Herpes
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="colitis" id="colitis" name="conditions[]" <?php if (in_array("colitis", $conditions)) echo "checked"; ?>>
                        <label class="form-check-label" for="colitis">
                          Colitis
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="aids" id="aids" name="conditions[]" <?php if (in_array("aids", $conditions)) echo "checked"; ?>>
                        <label class="form-check-label" for="aids">
                          Aids
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <input type="hidden" name="id" value="<?php echo $user ?>">

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
                  <li class="list-group-item">
                    <span class="fw-bold">Uploaded Content:</span> such as files or photos you upload to our system.
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
      <div class="row p-5"><input class="btn btn-primary" type="submit" value="Submit" name="appoint" id="submitButton" disabled></div>
    </form>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script>
    function checkAgreement() {
      const checkbox = document.getElementById("policyID");

      if (checkbox.checked) {
        document.getElementById("submitButton").disabled = false;
      } else {
        document.getElementById("submitButton").disabled = true;
      }
    }
  </script>
</body>

</html>