<?php
session_start();

if (isset($_SESSION['id'])) {
    header('Location: index.php?selectedDate=' . date("d-m-Y"));
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- BS css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- BS icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<style>
    .login-container {
        height: 100vh;
    }
</style>

<body>
    <div class="d-flex align-items-center justify-content-center login-container bg-light">
        <div class="card shadow-lg p-4" style="width: 30rem;">
            <div class="h1 fw-bold">Dent App</div>
            <div class="h6 mb-0 mt-3">Hello! Let's get started.</div>
            <div class="p">Sign in to continue.</div>
            <form action="./api/loginAPI.php" method="POST">
                <div class="input-group my-3">
                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-person-fill"></i></span>
                    <input class="form-control" type="text" name="username" placeholder="Username" required>
                </div>

                <div class="input-group my-3">
                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-lock-fill"></i></span>
                    <input class="form-control" type="password" name="password" placeholder="Password" required>
                </div>

                <div class="d-grid">
                    <button class="btn btn-primary rounded-pill" type="submit" name="login">Login</button>
                </div>
            </form>
            <div class="p text-center my-4">Don't have an account?<a href="register.php">Create</a></div>

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