<?php if (empty($_SESSION)) {
    session_start();
} ?>

<!-- navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light px-5 pb-3">
    <div class="col">
        <a class="navbar-brand h3 fw-bold mx-5" href="index.php">Dent App</a>
    </div>

    <div class="col">
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <?php if ($_SESSION['role'] == "admin") { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="account_table.php">User table</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="history.php">History</a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="col d-flex justify-content-end"><a class="btn btn-danger" href="logout.php">Logout</a></div>
</nav>