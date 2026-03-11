<?php
$user_id = $_SESSION['id'];
$aid = $_GET['aid'];
?>
<div class="container mt-4">
  <h4 class="mb-3">Billing</h4>
  <div class="btn btn-primary" onclick="handleAddBilling('<?= $aid ?>', '<?= $row['user'] ?>')"><i class="bi bi-plus"></i></div>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Date</th>
        <th>Remarks</th>
        <th>Procedure/Treatment</th>
        <th>Dentist/DA</th>
        <th>Total Payment</th>
        <th>Partial Payment</th>
        <th>Balance</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <?php $response = file_get_contents('http://localhost:5000/getBilling?aid=' . $aid);
        $response = json_decode($response, true); ?>
        <?php if (count($response) > 0) : ?>
          <?php foreach ($response as $row) :
            $date = new DateTime($row['last_updated']);
          ?>
      <tr id="<?= $row['id'] ?>">
        <td>
          <?= $date->format("d/m/Y"); ?>
        </td>
        <td>
          <?= $row['remarks'] ?>
        </td>
        <td>
          <?= $row['service_type'] ?>
        </td>
        <td>
          <?= $row['dentist'] ?>
        </td>
        <td>
          <?= $row['total_payment'] ?>
        </td>
        <td>
          <?= $row['partial_payment'] ?>
        </td>
        <td>
          <?= $row['balance'] ?>
        </td>
        <td>
          <div class="btn btn-primary" onclick="handleBillEdit('<?= $row['id'] ?>')"><i class="bi bi-pencil-square"></i></div>
          <div class="btn btn-danger" onclick="handleBillDelete('<?= $row['id'] ?>')"><i class="bi bi-trash"></i></div>
        </td>
      </tr>
    <?php endforeach; ?>
  <?php endif; ?>
    </tbody>
  </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  const user_id = <?= $user_id ?>;
  const urlParams = new URLSearchParams(window.location.search);
  const aid = urlParams.get('aid');

  document.addEventListener("DOMContentLoaded", function() {
    getServices()
    getCustomerServices()
    getDentist()
    getPayments()
    getCservice()
  })

  async function getCservice() {
    const totalCost = document.getElementById("total-cost");
    const response = await fetch(`http://localhost:5000/getCustomerServices?appointment_id=${aid}`);
    if (!response.ok) throw new Error("Failed to fetch complaints");
    const res = await response.json();

    const total = res.map(item => item.servicePrice).reduce((x, y) => parseFloat(x) + parseFloat(y), 0)
    const newdiv = document.createElement("div");
    newdiv.textContent = total;
    newdiv.classList.add("text-center", "fw-bold")
    totalCost.appendChild(newdiv);
  }

  async function getServices() {
    const servicesOptions = document.getElementById("services")
    const response = await fetch(`http://localhost:5000/getServices`);
    if (!response.ok) throw new Error("Failed to fetch complaints");
    const services = await response.json();
    return services;
  }

  async function handleBillEdit(rowId) {
    const tr = document.getElementById(rowId);
    const tds = tr.querySelectorAll('td');

    let services = [];
    try {
      services = await getServices();
    } catch (err) {
      console.error(err)
    }

    console.log(services)

    tds.forEach((td, index) => {
      if (index === 0) return;
      if (index === tds.length - 1) {
        td.innerHTML = `<div class="btn btn-success" onclick="save('${rowId}')"><i class="bi bi-check-lg"></i></div>`
        return;
      }

      if (index === 2) {
        const currentValue = td.textContent.trim();

        const select = document.createElement('select');
        select.className = "form-select";

        select.innerHTML = services
          .map(s => `<option value="${s.id}" ${s.service_type === currentValue ? "selected" : ""}>
                   ${s.service_type}
                 </option>`)
          .join("");

        td.innerHTML = "";
        td.appendChild(select);
        return;
      }

      const value = td.textContent.trim();
      let inputType = 'text';

      if (index == [4] || index == [5] || index == [6]) {
        inputType = 'number';
      }

      td.innerHTML = `<input type="${inputType}" value="${value}" class="form-control">`;
    });
  }

  function save(id) {
    const tr = document.getElementById(id);
    const tds = tr.querySelectorAll('td');
    var billings = []

    tds.forEach((td, index) => {
      console.log(td)
      if (index == 0 || index == tds.length - 1) return;
      const input = td.querySelector('input');
      if (input) {
        billings.push(input.value)
      }
      const select = td.querySelector('select');
      if (select) {
        billings.push(select.value)
      }
    })

    fetch('http://localhost:5000/saveBilling', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          id,
          billings
        })
      }).then(res => {
        if (!res.ok) throw new Error('Network response was not ok');
        return res.json();
      }).then(data => {
        console.log('Success:', data);
      })
      .then(() => window.location.reload())
      .catch(error => {
        console.error('Error:', error);
      });
  }

  function handleAddBilling(aid,user) {
    console.log(aid, user)
    fetch('http://localhost:5000/addBilling', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          user,
          aid
        })
      }).then(res => {
        if (!res.ok) throw new Error('Network response was not ok');
        return res.json();
      }).then(data => {
        console.log('Success:', data);
      })
      .then(() => window.location.reload())
      .catch(error => {
        console.error('Error:', error);
      });
  }

  function handleBillDelete(id) {
    Swal.fire({
      title: 'Are you sure?',
      text: "This billing row will be permanently deleted!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        fetch('http://localhost:5000/deleteBilling', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              id
            })
          })
          .then(res => {
            if (!res.ok) throw new Error('Network response was not ok');
            return res.json();
          })
          .then(data => {
            Swal.fire(
              'Deleted!',
              'The billing row has been deleted.',
              'success'
            );

            const tr = document.getElementById(id);
            if (tr) tr.remove();
          })
          .then(() => window.location.reload())
          .catch(error => {
            Swal.fire(
              'Error!',
              'Something went wrong. Please try again.',
              'error'
            );
            console.error('Error:', error);
          });
      }
    });
  }
</script>