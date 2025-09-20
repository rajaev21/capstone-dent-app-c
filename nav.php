<?php
if (empty($_SESSION)) {
  session_start();
}
date_default_timezone_set('Asia/Manila');

?>

<!-- navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light px-5 pb-3">
  <div class="col">
    <a class="navbar-brand h3 fw-bold mx-5" href="index.php?selectedDate=<?= isset($_GET['selectedDate']) ? $_GET['selectedDate'] : date('Y-m-d') ?>">Dent App</a>
  </div>
  <div class="col d-flex justify-content-end">
    <div class="row">
      <div class="col">
        <div class="btn-group">
          <button type="button" class="btn btn-transparent dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="bellButton">
            <i class="bi bi-bell"></i>
          </button>
          <?php
          $response = file_get_contents('http://localhost:5000/getNotification?user_id=' . $_SESSION['id']);
          $response = json_decode($response, true);
          if (count($response) > 0) {

            $unreadCount = 0;
            foreach ($response as $row) {
              if ($row['isRead'] === 0) {
                $unreadCount++;
              }
            }
          ?>
            <?php if ($unreadCount > 0) { ?>
              <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger"><?php echo $unreadCount ?></span>
            <?php } ?>
            <ul class="dropdown-menu dropdown-menu-end" style="width: 15em;">
              <?php
              foreach ($response as $row) {
                $createdAt = new DateTime($row['created_at'], new DateTimeZone('Asia/Manila')); ?>
                <li class="p text-center">
                  <div class="fw-bold"><?php echo $row['message'] ?> </div>
                  <div class="fw-light"><?php echo date('M d, Y', strtotime($row['date'])) ?> <?php echo date('h:i A', strtotime($row['appointment_start'])) ?></div>
                  <div class="p">Reason : <?php echo $row['reason'] ?> </div>
                  <div class="fw-light" style="font-size: 10px;">Date cancelled: <?php echo $createdAt->format('M d, Y h:i A'); ?></div>
                </li>
              <?php } ?>
            </ul>
          <?php } ?>
        </div>
      </div>
      <div class="col dropdown">
        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
          <?php echo $_SESSION['email'] ?>
        </button>
        <ul class="dropdown-menu">
          <div class="d-grid gap-2 mx-2">
            <?php if ($_SESSION['role'] == "admin") { ?>
              <a class="btn btn-light" href="account_table.php">User table</a>
              <a class="btn btn-light" href="history.php">History</a>
            <?php } ?>
            <?php if ($_SESSION['role'] == "user") { ?>
              <a class="btn btn-light" href="customer_form.php?id=<?php echo $_SESSION['id'] ?>">Profile</a>
            <?php } ?>
            <a class="btn btn-danger" href="logout.php">Logout</a>
          </div>
        </ul>
      </div>
    </div>
  </div>
</nav>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const bellButton = document.getElementById("bellButton");
    if (bellButton) {
      bellButton.addEventListener("click", notificationRead);
    }
  });

  function notificationRead() {
    fetch('http://localhost:5000/notificationRead?user_id=<?= $_SESSION['id'] ?>')
      .then(response => response.json())
      .then(data => {
        const badge = document.querySelector(".badge");
        if (badge) badge.style.display = "none";
      })
      .catch((error) => {
        console.error('Error:', error);
      });
  }
</script>