<?php
session_start();

if (!empty($_SESSION['id'])) {
    header('location:index.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP</title>
    <!-- BS css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- BS icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <div class="vh-100 d-flex align-items-center justify-content-center login-container bg-light">
        <div class="card shadow-lg p-4" style="width: 30rem;">
            <div class="h1 fw-bold">Dent App</div>
            <div class="h6 mb-0 mt-3">An otp was sent to your email</div>
            <div class="p">Enter OTP.</div>
            <form action="./api/confirmOtp.php" method="POST">
                <div class="input-group my-3">
                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-lock-fill"></i></span>
                    <input class="form-control" type="number" name="otp" placeholder="OTP" required>
                </div>
                <div class="d-grid">
                    <button class="btn btn-primary rounded-pill" type="submit" name="confirmOtp">Confirm OTP</button>
                </div>
            </form>
            <form action="./api/login.php"></form>
            <div class="p">OTP is only valid for 5 mins</div>

            <div class="text-center">
                <?php if (!empty($_GET['response'])) {
                    echo '<p>' . $_GET['response'] . '</p>';
                } ?>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>