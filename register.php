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

<body>
    <div class="d-flex align-items-center justify-content-center login-container bg-light m-5">
        <div class="card shadow-lg p-4" style="width: 30rem;">
            <div class="h1 fw-bold">Dent App</div>
            <div class="h6 mb-0 mt-3">Hello! Let's get started.</div>
            <div class="p">Sign in to continue.</div>
            <div class="m-3">
                <form action="./api/registerAPI.php" method="POST">
                    <input class="form-control m-1" type="text" name="username" placeholder="Username">
                    <input class="form-control m-1" type="password" name="password" placeholder="Password">
                    <input class="form-control m-1" type="password" name="confirmPassword" placeholder="Password">

                    <input class="form-control m-1" type="text" name="firstName" placeholder="First name">
                    <input class="form-control m-1" type="text" name="lastName" placeholder="Last name">
                    <input class="form-control m-1" type="email" name="email" placeholder="Email">
                    <select class="form-select m-1" name="gender">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                    <input class="form-control m-1" type="number" name="phoneNumber" placeholder="Phone number">
                    <select class="form-select m-1" name="role">
                        <option value="admin">Admin</option>
                        <option value="employee">Employee</option>
                    </select>
                    <div class="d-grid">
                        <button class="rounded-pill btn btn-primary m-1" type="submit" name="register">Register</button>
                    </div>
                </form>
            </div>
            <div class="p text-center my-4">Already have an account?<a href="login.php">Login now!</a></div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>