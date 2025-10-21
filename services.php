<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Appointment Details</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
  <div class="container mt-4">
    <h4 class="mb-3">Appointment Details</h4>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Date</th>
          <th>Complaints</th>
          <th>Services</th>
          <th>Dentist/DA</th>
          <th>Total Payment</th>
          <th>Partial Payment</th>
          <th>Balance</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
    
    <button class="btn btn-success d-none">Save Changes</button>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", async function() {
      const urlParams = new URLSearchParams(window.location.search);
      const aid = urlParams.get("aid");

      const tbody = document.querySelector("tbody");
      tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted">Loading...</td></tr>`;

      try {
        const services = await getAppointmentServices(aid);
        console.log(services);

        tbody.innerHTML = "";

        services.forEach(item => {
          const row = document.createElement("tr");
          row.innerHTML = `
            <td>${item.date || ''}</td>
            <td>${item.complaints || ''}</td>
            <td>${item.service_type || ''}</td>
            <td>${item.dentist || ''}</td>
            <td>₱${item.total_payment || 0}</td>
            <td>₱${item.partial_payment || 0}</td>
            <td>₱${item.balance || 0}</td>
          `;
          tbody.appendChild(row);
        });
      } catch (err) {
        console.error(err);
        tbody.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Error loading appointment details</td></tr>`;
      }
    });

    async function getAppointmentServices(aid) {
      const response = await fetch(`http://localhost:5000/getAppointmentServices?aid=${aid}`);
      if (!response.ok) throw new Error("Failed to fetch appointment services");
      return await response.json();
    }
  </script>
</body>

</html>