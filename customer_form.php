<?php
if (!$_GET['id']) {
header('location:login.php');
}
$_SESSION['id'] = $_GET['id'];
session_start();
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
    <!-- Form Starts here -->
    <div class="container">
        <h3>Please Fill the form before proceeding </h3>
        <form action="./api/customerFormAPI.php" method="GET">
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
                                        <input class="form-control" type="text" name="firstName" id="firstname" placeholder="Firstname" required>
                                    </div>
                                    <div class="col">
                                        <label for="middleName" class="form-label">Middle name:</label>
                                        <input class="form-control" type="text" name="middleName" id="middleName" placeholder="Middle name">
                                    </div>
                                    <div class="col">
                                        <label for="lastName" class="form-label">* Last Name:</label>
                                        <input class="form-control" type="text" name="lastName" id="lastName" placeholder="Last name" required>
                                    </div>
                                    <div class="col">
                                        <label for="nickName" class="form-label">Nickname:</label>
                                        <input class="form-control" type="text" name="nickName" id="nickName" placeholder="Nickname">
                                    </div>
                                </div>

                                <div class="row m-3">
                                    <div class="col">
                                        <label for="address" class="form-label">* Address:</label>
                                        <input class="form-control" type="text" name="address" id="address" placeholder="Address" required>
                                    </div>
                                    <div class="col">
                                        <label for="contactNumber" class="form-label">* Contact Number:</label>
                                        <input class="form-control" type="number" name="contactNumber" id="contactNumber" placeholder="Contact Number" required>
                                    </div>
                                </div>

                                <div class="row m-3">
                                    <div class="col">
                                        <label for="facebook" class="form-label">Facebook / Messenger:</label>
                                        <input class="form-control" type="facebook" name="facebook" id="facebook" placeholder="Facebook / Messenger">
                                    </div>
                                </div>

                                <div class="row m-3">
                                    <div class="col">
                                        <label for="birthDay" class="form-label">* Date of Birth:</label>

                                        <input class="form-control" type="date" name="birthDay" id="birthDay" placeholder="Date of Birth" required>
                                    </div>
                                    <div class="col">
                                        <label for="nationality" class="form-label">Nationality:</label>
                                        <input class="form-control" type="text" name="nationality" id="nationality" placeholder="Nationality">
                                    </div>
                                    <div class="col">
                                        <label for="age" class="form-label">* Age:</label>
                                        <input class="form-control" type="number" name="age" id="age" placeholder="Age" required>
                                    </div>
                                </div>

                                <div class="row m-3">
                                    <div class="col">
                                        <label for="gender" class="form-label">* Gender:</label>
                                        <select class="form-control" type="text" name="gender" id="gender" placeholder="Gender" required>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="civilStatus" class="form-label">Civil Status:</label>
                                        <input class="form-control" type="text" name="civilStatus" id="civilStatus" placeholder="Civil Status">
                                    </div>
                                    <div class="col">
                                        <label for="occupation" class="form-label">Occupation:</label>
                                        <input class="form-control" type="text" name="occupation" id="occupation" placeholder="Occupation">
                                    </div>
                                </div>

                                <div class="row m-3">
                                    <div class="col">
                                        <label for="employer" class="form-label">Employer / School:</label>
                                        <input class="form-control" type="text" name="employer" id="employer" placeholder="Employer / School">
                                    </div>
                                </div>

                                <div class="row m-3">
                                    <div class="col">
                                        <label for="clinic" class="form-label">Medical Doctor's Name / Clinic:</label>
                                        <input class="form-control" type="text" name="clinic" id="clinic" placeholder="Medical Doctor's Name / Clinic">
                                    </div>
                                    <div class="col">
                                        <label for="prevClinic" class="form-label">Previous Medical Doctor's Name / Clinic:</label>
                                        <input class="form-control" type="text" name="prevClinic" id="prevClinic" placeholder="Previous Medical Doctor's Name / Clinic">
                                    </div>
                                </div>

                                <span class="fw-bold h5">Incase of Emergency:</span>
                                <div class="row m-3">
                                    <div class="col">
                                        <label for="emergencyFirstname" class="form-label">* Firstname:</label>
                                        <input class="form-control" type="text" name="emergencyFirstname" id="emergencyFirstname" placeholder="Firstname" required>
                                    </div>
                                    <div class="col">
                                        <label for="emergencyLastname" class="form-label">* Lastname:</label>
                                        <input class="form-control" type="text" name="emergencyLastname" id="emergencyLastname" placeholder="Lastname" required>
                                    </div>
                                </div>

                                <div class="row m-3">
                                    <div class="col">
                                        <label for="relationship" class="form-label">* Relationship:</label>
                                        <input class="form-control" type="text" name="relationship" id="relationship" placeholder="Relationship" required>
                                    </div>
                                    <div class="col">
                                        <label for="emergencyContactNumber" class="form-label">* Contact number:</label>
                                        <input class="form-control" type="text" name="emergencyContactNumber" id="emergencyContactNumber" placeholder="Contact number" required>
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
                                <div class="card-title h3 fw-bold mb-0">Medications: (Optional)</div>
                                <div class="card-text mb-4">Please check the only that apply.</div>
                                <div class="row m-3">
                                    <div class="row p-3">
                                        <span class="fw-bold h5">Have you taken this medications?</span>
                                        <div class="form-check">
                                            <label class="form-check-label" for="aspirin">
                                                Aspirin (Aspilet, Cortal, Coplavix, etc.)
                                            </label>
                                            <input class="form-check-input" type="checkbox" value="aspirin" id="aspirin" name="taken[]">
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="recreationalDrugs" id="recreationalDrugs" name="taken[]">
                                            <label class="form-check-label" for="recreationalDrugs">
                                                Recreational Drugs (Cocaine, Marijuana, etc.)
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="birthControl" id="birthControl" name="taken[]">
                                            <label class="form-check-label" for="birthControl">
                                                Birth control / Hormonal therapy (Thrust Daphne, Minipil, etc.)
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="narcotics" id="narcotics" name="taken[]">
                                            <label class="form-check-label" for="narcotics">
                                                Narcotics (Codeine, Narcotan, Methadone, etc.)
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="diabetes" id="diabetes"name="taken[]">
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
                                                <input class="form-check-input" type="checkbox" value="asthma" id="asthma"name="conditions[]">
                                                <label class="form-check-label" for="asthma">
                                                    Asthma
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="anemia" id="anemia"name="conditions[]">
                                                <label class="form-check-label" for="anemia">
                                                    Anemia
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="arthritis" id="arthritis"name="conditions[]">
                                                <label class="form-check-label" for="arthritis">
                                                    Arthritis
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="heartAilments" id="heartAilments"name="conditions[]">
                                                <label class="form-check-label" for="heartAilments">
                                                    Hearth Ailments
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="bloodDisorder" id="bloodDisorder"name="conditions[]">
                                                <label class="form-check-label" for="bloodDisorder">
                                                    Blood disorder
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="diabetes" id="diabetes"name="conditions[]">
                                                <label class="form-check-label" for="diabetes">
                                                    Diabetes
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="epilepsy" id="epilepsy"name="conditions[]">
                                                <label class="form-check-label" for="epilepsy">
                                                    Epilepsy
                                                </label>
                                            </div>
                                        </div>

                                        <!-- column 2 -->
                                        <div class="col">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="highblood" id="highblood"name="conditions[]">
                                                <label class="form-check-label" for="highblood">
                                                    HighBlood
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="heartmurmurs" id="heartmurmurs"name="conditions[]">
                                                <label class="form-check-label" for="heartmurmurs">
                                                    Heart Murmurs
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="cancer" id="cancer"name="conditions[]">
                                                <label class="form-check-label" for="cancer">
                                                    Malignancies or Cancer
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="rheumatic" id="rheumatic"name="conditions[]">
                                                <label class="form-check-label" for="rheumatic">
                                                    Rheumatic Hearth Disease
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="radiation" id="radiation"name="conditions[]">
                                                <label class="form-check-label" for="radiation">
                                                    Radiation Treatment
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="stroke" id="stroke"name="conditions[]">
                                                <label class="form-check-label" for="stroke">
                                                    Stroke
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="sinus" id="sinus"name="conditions[]">
                                                <label class="form-check-label" for="sinus">
                                                    Sinus
                                                </label>
                                            </div>
                                        </div>

                                        <!-- column 3 -->
                                        <div class="col">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="hormonalDisorder" id="hormonalDisorder"name="conditions[]">
                                                <label class="form-check-label" for="hormonalDisorder">
                                                    Hormonal Disorder
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="stomachUlcer" id="stomachUlcer"name="conditions[]">
                                                <label class="form-check-label" for="stomachUlcer">
                                                    Stomach Ulcer
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="kidney" id="kidney"name="conditions[]">
                                                <label class="form-check-label" for="kidney">
                                                    Kidney Disease
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="neurological" id="neurological"name="conditions[]">
                                                <label class="form-check-label" for="neurological">
                                                    Neurogical Problem
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="prostate" id="prostate"name="conditions[]">
                                                <label class="form-check-label" for="prostate">
                                                    Prostate Problem
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="eyeDisorder" id="eyeDisorder"name="conditions[]">
                                                <label class="form-check-label" for="eyeDisorder">
                                                    Eye Disorder
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="eatingDisorder" id="eatingDisorder"name="conditions[]">
                                                <label class="form-check-label" for="eatingDisorder">
                                                    Eating Disorder
                                                </label>
                                            </div>
                                        </div>

                                        <!-- column 4 -->
                                        <div class="col">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="thyroid" id="thyroid" name="conditions[]">
                                                <label class="form-check-label" for="thyroid">
                                                    Thyroid Fever
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="tuberculosis" id="tuberculosis" name="conditions[]">
                                                <label class="form-check-label" for="tuberculosis">
                                                    Tuberculosis
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="veneral" id="veneral"name="conditions[]">
                                                <label class="form-check-label" for="veneral">
                                                    Veneral Disease
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="hepatitis" id="hepatitis"name="conditions[]">
                                                <label class="form-check-label" for="hepatitis">
                                                    Hepatits
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="herpes" id="herpes"name="conditions[]">
                                                <label class="form-check-label" for="herpes">
                                                    Herpes
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="colitis" id="colitis"name="conditions[]">
                                                <label class="form-check-label" for="colitis">
                                                    Colitis
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="aids" id="aids"name="conditions[]">
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
            <input type="hidden" value="<?php echo $_GET['id'] ?>" name="id">
            <div class="row p-5"><input class="btn btn-primary" type="submit" value="Submit" name="appoint"></div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>