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
      <?php $response = file_get_contents('http://localhost:5000/getBilling?user_id=' . $user);
      $response = json_decode($response, true); ?>
      <?php if (count($response) > 0) : ?>
        <?php foreach ($response as $row) :
          $date = new DateTime($row['last_updated']);
        ?>
          <tr id="tr-<?= $row['id'] ?>">
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
  const user_id = <?= $user ?>;
  const aid = <?= $aid ?>;

  async function getServices() {
    const response = await fetch(`http://localhost:5000/getServices`);
    if (!response.ok) throw new Error("Failed to fetch services");
    return await response.json();
  }

  let cachedServices = null;
  document.addEventListener("DOMContentLoaded", async () => {
    cachedServices = await getServices();
  });

  async function handleBillEdit(rowId) {
    const tr = document.getElementById(`tr-${rowId}`);
    const tds = tr.querySelectorAll('td');

    let services = cachedServices;
    if (!services) {
      try {
        services = await getServices();
      } catch (err) {
        console.error('Could not load services:', err);
        return;
      }
    }

    tds.forEach((td, index) => {
      if (index === 0) return;

      if (index === tds.length - 1) {
        td.innerHTML = `<div class="btn btn-success" onclick="save('${rowId}')"><i class="bi bi-check-lg"></i></div>`;
        return;
      }

      if (index === 2) {
        const currentValue = td.textContent.trim();
        const select = document.createElement('select');
        select.className = 'form-select';
        select.innerHTML = services
          .map(s => `<option value="${s.id}" ${s.service_type === currentValue ? 'selected' : ''}>${s.service_type}</option>`)
          .join('');
        td.innerHTML = '';
        td.appendChild(select);
        return;
      }

      const value = td.textContent.trim();
      const inputType = (index === 4 || index === 5 || index === 6) ? 'number' : 'text';
      td.innerHTML = `<input type="${inputType}" value="${value}" class="form-control">`;
    });
  }

  function save(id) {
    const tr = document.getElementById(`tr-${id}`);
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

  function handleAddBilling(aid, user) {
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

            const tr = document.getElementById(`tr-${id}`);
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