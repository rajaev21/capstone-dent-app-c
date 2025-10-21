<?php
if (empty($_SESSION)) {
  session_start();
  date_default_timezone_set('Asia/Manila');
  if (empty($_SESSION['id']) && $_SESSION['confirm'] == false) {
    header('location:login.php');
  }
}
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light px-5 pb-3">
  <div class="col">
    <a class="navbar-brand h3 fw-bold mx-5"
      href="index.php?selectedDate=<?= date('Y-m-d') ?>">
      Dent App
    </a>
  </div>
  <div class="col d-flex justify-content-end">
    <div class="row">
      <div class="col">
        <div class="btn-group position-relative">
          <button type="button" class="btn btn-transparent dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="bellButton">
            <i class="bi bi-bell"></i>
            <span id="notifBadge" class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger d-none"></span>
          </button>
          <ul id="notifList" class="dropdown-menu dropdown-menu-end" style="width: 15em;">
            <li class="text-center fw-light">No notifications</li>
          </ul>
        </div>
      </div>
      <div class="col dropdown">
        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
          <?= $_SESSION['email'] ?>
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
    loadNotifications();

    const bellButton = document.getElementById("bellButton");
    if (bellButton) {
      bellButton.addEventListener("click", notificationRead);
    }
  });

  function loadNotifications() {
    fetch(`http://localhost:5000/getNotification?user_id=<?= $_SESSION['id'] ?>`)
      .then(res => res.json())
      .then(data => {
        const notifList = document.getElementById("notifList");
        const badge = document.getElementById("notifBadge");

        notifList.innerHTML = "";
        let unreadCount = 0;

        if (data.length > 0) {
          data.forEach(row => {
            if (row.isRead === 0) unreadCount++;

            const createdAt = new Date(row.created_at);
            const formattedCancel = createdAt.toLocaleString("en-US", {
              timeZone: "Asia/Manila",
              year: "numeric",
              month: "short",
              day: "numeric",
              hour: "2-digit",
              minute: "2-digit"
            });

            notifList.innerHTML += `
              <li class="p text-center">
                <div class="fw-bold">${row.message}</div>
                <div class="fw-light">
                  ${new Date(row.date).toLocaleDateString()} 
                  ${new Date("1970-01-01T" + row.appointment_start + "Z").toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
                </div>
                ${row.reason ? `<div class="p">Reason: ${row.reason}</div>` : ""}
                <div class="fw-light" style="font-size: 10px;">Date: ${formattedCancel}</div>
              </li>
            `;
          });
        } else {
          notifList.innerHTML = `<li class="text-center fw-light">No notifications</li>`;
        }

        if (unreadCount > 0) {
          badge.textContent = unreadCount;
          badge.classList.remove("d-none");
        } else {
          badge.classList.add("d-none");
        }
      })
      .catch(err => console.error(err));
  }

  function notificationRead() {
    fetch('http://localhost:5000/notificationRead?user_id=<?= $_SESSION['id'] ?>')
      .then(res => res.json())
      .then(() => {
        const badge = document.getElementById("notifBadge");
        if (badge) badge.classList.add("d-none");
      })
      .catch(err => console.error(err));
  }
</script>