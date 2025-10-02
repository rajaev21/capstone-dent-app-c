<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dent App</title>
  <!-- BS css -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <!-- BS icon -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
  <?php
  include('nav.php');
  include('welcome.php');
  ?>

  <div class="container">
    <table id="waitingTable" class="table table-striped">
      <thead>
        <tr>
          <th>Name</th>
          <th>Age</th>
          <th>Gender</th>
          <th>Number</th>
          <th>Address</th>
          <th>Request</th>
          <th>Reason</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="waitingBody">
      </tbody>
    </table>
    <div id="newModal"></div>
  </div>

  <script>
    const params = new URLSearchParams(window.location.search);

    const selectedDate = params.get("selectedDate");
    const start = params.get("starting");
    const session_id = <?= $_SESSION['id'] ?>;
    console.log(start)
    async function loadWaiting(date, start) {
      try {
        const res = await fetch(`http://localhost:5000/getWaiting?date=${date}&start=${start}`);
        const data = await res.json();

        const tbody = document.getElementById("waitingBody");
        tbody.innerHTML = "";

        data.forEach(customer => {
          const row = `
        <tr>
          <td class="text-capitalize">${customer.name}</td>
          <td>${customer.age}</td>
          <td>${customer.gender}</td>
          <td>${customer.number}</td>
          <td>${customer.address ?? ""}</td>
          <td>${customer.message}</td>
          <td>${customer.reason}</td>
          <td>
            <button 
              class="btn btn-primary" 
              onclick="approveAppointment('${customer.name}', ${customer.aid}, ${customer.user_id})">
              Approve
            </button>
            <button 
              class="btn btn-danger" 
              onclick="cancelAppointment('${customer.name}', ${customer.aid}, ${customer.user_id})">
              Cancel
            </button>
          </td>
        </tr>
        `;
          tbody.insertAdjacentHTML("beforeend", row);
        });

      } catch (err) {
        console.error("Error fetching waiting list:", err);
      }
    }

    function approveAppointment(name, aid, user_id) {
      const modal = document.getElementById("newModal");

      modal.innerHTML = `
    <div class="modal fade" id="approve${aid}" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="./api/finishAppointment.php" method="POST">
            <div class="modal-header">
              <h5 class="modal-title">Approve Appointment</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <h6>Do you want to approve <span class="fw-bold text-capitalize">${name}</span> appointment?</h6>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button class="btn btn-primary" type="submit" name="approve">Yes</button>
              <input type="hidden" name="id" value="${aid}">
              <input type="hidden" name="user_id" value="${user_id}">
              <input type="hidden" name="admin_id" value="${session_id}">
              <input type="hidden" name="date" value="${selectedDate}">
              <input type="hidden" name="start" value="${start}">
            </div>
          </form>
        </div>
      </div>
    </div>`;

      new bootstrap.Modal(document.getElementById(`approve${aid}`)).show();
    }

    function cancelAppointment(name, aid, user_id) {
      const modal = document.getElementById("newModal");

      modal.innerHTML = `
    <div class="modal fade" id="cancel${aid}" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="./api/cancelAppointment.php" method="POST">
            <div class="modal-header">
              <h5 class="modal-title">Cancel Appointment</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <h6>Do you want to cancel <span class="fw-bold text-capitalize">${name}</span> appointment?</h6>
              <div class="form-floating">
                <textarea name="reason" class="form-control" placeholder="Reason for cancellation" required></textarea>
                <label>Reason for cancellation</label>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button class="btn btn-primary" type="submit" name="cancel">Yes</button>
              <input type="hidden" name="aid" value="${aid}">
              <input type="hidden" name="user_id" value="${user_id}">
              <input type="hidden" name="admin_id" value="${session_id}">
            </div>
          </form>
        </div>
      </div>
    </div>`;

      new bootstrap.Modal(document.getElementById(`cancel${aid}`)).show();
    }

    loadWaiting(selectedDate, start);
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>