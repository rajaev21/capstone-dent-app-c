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

    #waiver {
      position: relative;

    }

    #canvasimg {
      position: relative;
      bottom: 18%;
      width: 10rem;
    }
  </style>
</head>

<body>
  <?php
  include('nav.php');
  ?>
  <div class="container">
    <?php if (isset($_GET['aid'])) {
      $response = file_get_contents('http://localhost:5000/getAppointedCustomer?aid=' . $_GET['aid']);
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
          $signature = $row['signature'];
          $isPregnant = $row['isPregnant'];
          $isBreastfeeding = $row['isBreastfeeding'];
          $additionalInformation = $row['additionalInformation'];
          $email = $row['email'];
          $isBeingTreated = $row['isBeingTreated'];
          $isHospitalized = $row['isHospitalized'];
          $isAllergy = $row['isAllergy'];
          $menstrual = $row['menstrual'];

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

    <div class="container mt-5">
      <div class="row justify-content-center">
        <div class="col-6">
          <div class="card shadow-sm mb-2">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0">Customer Details</h5>
            </div>
            <div class="card-body">
              <h5 class="card-title"><?= ucwords($firstName) . " " . ucwords($middleName) . " " . ucwords($lastName) ?></h5>
            </div>
            <div class="row p-3">
              <ul class="list-group list-group-flush col-6">
                <li class="list-group-item"><strong>Address:</strong> <?= $address ?> </li>
                <li class="list-group-item"><strong>Contact number:</strong> <?= $contactNumber ?> </li>
                <li class="list-group-item"><strong>Facebook:</strong> <?= $facebook ?> </li>
                <li class="list-group-item"><strong>Email:</strong> <?= $email ?> </li>
                <li class="list-group-item"><strong>Nationality:</strong> <?= $nationality ?> </li>
                <li class="list-group-item"><strong>Age:</strong> <?= $age ?> </li>
                <li class="list-group-item"><strong>Date of birth:</strong> <?= date("M d,Y") ?> </li>
              </ul>
              <ul class="list-group list-group-flush col-6">
                <li class="list-group-item"><strong>Gender:</strong> <?= $gender ?> </li>
                <li class="list-group-item"><strong>Civil Status:</strong> <?= $civilStatus ?> </li>
                <li class="list-group-item"><strong>Occupation:</strong> <?= $occupation ?> </li>
                <li class="list-group-item"><strong>Employer/School:</strong> <?= $employer ?> </li>
                <li class="list-group-item"><strong>Medical Doctor's Name/Clinic:</strong> <?= $prevClinic ?> </li>
                <li class="list-group-item"><strong>Incase of Emergency Contact Name:</strong> <?= $emergencyFirstname . $emergencyLastname ?> </li>
                <li class="list-group-item"><strong>Relationship:</strong> <?= $relationship ?> </li>
                <li class="list-group-item"><strong>Contact Number:</strong> <?= $emergencyContactNumber ?> </li>
              </ul>
            </div>
          </div>
          <div class="card shadow-sm mb-2">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0">Waiver</h5>
            </div>
            <div class="row p-3">
              <div class="container " id="waiver">
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
                <div class="mt-5 ms-5"><?= ucwords($firstName . " " . $middleName . " " . $lastName); ?></div>
                <img id="canvasimg" src="<?= $signature ?>">
              </div>
            </div>
          </div>
        </div>
        <div class="col-6">
          <div class="card shadow-sm mb-2">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0">Medication / Injections</h5>
            </div>
            <div class="row p-3">
              <ul class="list-group list-group-flush col-6">
                <div class="row p-3">
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
                </div>
              </ul>
              <ul class="list-group list-group-flush col-6">
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
              </ul>
            </div>
          </div>
          <div class="card shadow-sm mb-2">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0">Conditions</h5>
            </div>
            <div class="row p-3">
              <ul class="list-group list-group-flush">
                <div class="row row-cols-2 p-3">
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
              </ul>
            </div>
          </div>
          <div class="card shadow-sm mb-2">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0">Other Information</h5>
            </div>
            <div class="p-3">
              Are you being treated by a doctor at the present time? <div class=""><?= $isBeingTreated ?></div>
              Have you been seriously ill or hopitalized? <div class=""><?= $isHospitalized ?></div>
              Do you have any allergies or sensitivity to any medication, injections, food or materials? <div class=""><?= $isAllergy ?></div>
            </div>
          </div>
          <div class="card shadow-sm mb-2 <?= $gender == "female" ? "" : "d-none" ?>">
            <div class=" card-header bg-primary text-white">
              <h5 class="mb-0">For female patient</h5>
            </div>
            <div class="row px-5 py-3 ">
              Date of last menstrual period: <div class="card p-2"><?= date("M d,Y", strtotime($menstrual)) ?></div>
              Are you pregnant?
              <div class="form-check">
                <input class="form-check-input" type="radio" name="isPregnant" id="radioDefault1" value="1" disabled <?= $isPregnant ? "checked" : "" ?>>
                <label class="form-check-label" for="radioDefault1">
                  Yes
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="isPregnant" id="radioDefault2" value="0" disabled <?= !$isPregnant ? "checked" : "" ?>>
                <label class="form-check-label" for="radioDefault2">
                  No
                </label>
              </div>
              Are you breastfeading?
              <div class="form-check">
                <input class="form-check-input" type="radio" name="isBreastfeeding" id="radioDefault1" value="1" disabled <?= $isBreastfeeding ? "checked" : "" ?>>
                <label class="form-check-label" for="radioDefault1">
                  Yes
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="isBreastfeeding" id="radioDefault2" value="0" disabled <?= !$isBreastfeeding ? "checked" : "" ?>>
                <label class="form-check-label" for="radioDefault2">
                  No
                </label>
              </div>
              Addtional Information: <div class="card p-3"><?= $additionalInformation ?></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include('dental-chart.php') ?>
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
              <input type="hidden" name="user_id" value="<?php echo $user ?>">
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
            <input type="hidden" name="user_id" value="<?php echo $user ?>">
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
  </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>