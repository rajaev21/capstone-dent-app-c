<?php $user_id = $_SESSION['id'] ?>
<div class="container mt-4">
  <h4 class="mb-3">Appointment Details</h4>

  <table class="table table-striped">
    <thead>
      <tr>
        <th>Complaints</th>
        <th>Services</th>
        <th>Dentist/DA</th>
        <th>Payment</th>
        <th>Total Services Cost</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
          <div class="input-group">
            <input
              class="form-control"
              type="text"
              id="complaints"
              placeholder="Add complaints here" />
            <button class="btn btn-primary btn-sm" onclick="saveComplaint()">Save complaints</button>
          </div>
        </td>
        <td>
          <div class="input-group">
            <select id="services" class="form-control">
              <option value="">Select Service</option>
            </select>
            <button class="btn btn-primary btn-sm" id="servicesButton" onclick="addService()" disabled>Add Service</button>
          </div>
        </td>
        <td>
          <div class="input-group">
            <input
              class="form-control"
              type="text"
              id="dentist"
              placeholder="Add dentist here" />
            <button class="btn btn-primary btn-sm" onclick="addDentist()">Add Dentist</button>
          </div>
        </td>
        <td>
          <div class="input-group">
            <input
              class="form-control"
              type="number"
              min="0"
              id="payment"
              placeholder="Add payment here" />
            <button class="btn btn-primary btn-sm" onclick="addPayment()">Add Payment</button>
          </div>
        </td>
        <td>
          <div id="total-cost"></div>
        </td>
      </tr>
      <tr>
        <td>
          <div id="complaints-list"></div>
        </td>
        <td>
          <div id="services-list"></div>
        </td>
        <td>
          <div id="dentist-list"></div>
        </td>
        <td>
          <div id="payment-list"></div>
        </td>
      </tr>
    </tbody>
  </table>
  <button class="btn btn-success d-none">Save Changes</button>
</div>

<script>
  const user_id = <?= $user_id ?>;
  const urlParams = new URLSearchParams(window.location.search);
  const aid = urlParams.get('aid');
  const servicesBtn = document.getElementById("servicesButton")
  const selectServices = document.getElementById("services")

  selectServices.addEventListener("change", function() {
    console.log(selectServices.value)
    if (selectServices.value === "") {
      servicesBtn.disabled = true
    } else {
      servicesBtn.disabled = false
    }
  })

  document.addEventListener("DOMContentLoaded", function() {
    getComplaints();
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

    services.forEach(s => {
      const option = document.createElement("option");
      option.value = s.id;
      option.textContent = s.service_type;
      selectServices.appendChild(option);
    });
  }


  async function getCustomerServices() {
    const serviceList = document.getElementById("services-list");
    const response = await fetch(`http://localhost:5000/getCustomerServices?appointment_id=${aid}`);
    if (!response.ok) throw new Error("Failed to fetch complaints");
    const service_type = await response.json();
    service_type.forEach(c => {
      const div = document.createElement("div");
      div.textContent = c.serviceType;
      serviceList.appendChild(div);
    });
  }

  async function getDentist() {
    const dentistList = document.getElementById("dentist-list");
    const response = await fetch(`http://localhost:5000/getDentist?aid=${aid}`);
    if (!response.ok) throw new Error("Failed to fetch complaints");
    const lists = await response.json();
    lists.forEach(l => {
      const div = document.createElement("div");
      div.textContent = l.dentist;
      dentistList.appendChild(div);
    });
  }

  async function getComplaints() {
    const complaintsList = document.getElementById("complaints-list");
    const response = await fetch(`http://localhost:5000/getComplaints?aid=${aid}`);
    if (!response.ok) throw new Error("Failed to fetch complaints");
    const complaints = await response.json();

    complaintsList.innerHTML = "";

    complaints.forEach(c => {
      const div = document.createElement("div");
      div.textContent = c.complaint;
      complaintsList.appendChild(div);
    });
  }

  async function getPayments() {
    const payment = document.getElementById("payment-list");
    const response = await fetch(`http://localhost:5000/getPayments?aid=${aid}`);
    if (!response.ok) throw new Error("Failed to fetch complaints");
    const res = await response.json();

    console.log(res)
    payment.innerHTML = "";

    res.forEach(c => {
      const div = document.createElement("div");
      div.textContent = c.payment;
      payment.appendChild(div);
    });

    const total = res.reduce((x, y) => x + y.payment, 0)
    const newdiv = document.createElement("div");
    newdiv.textContent = `Total : ${total}`;
    newdiv.classList.add("text-end", "fw-bold")
    payment.appendChild(newdiv);
  }

  function saveComplaint() {
    const complaints = document.getElementById("complaints").value
    fetch('http://localhost:5000/saveComplaints', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          complaints,
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

  function addService() {
    const additionalServices = document.getElementById("services").value

    fetch('http://localhost:5000/additionalServices', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          additionalServices,
          aid,
          user_id
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

  function addDentist() {
    const dentist = document.getElementById("dentist").value

    fetch('http://localhost:5000/addDentist', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          dentist,
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

  function addPayment() {
    const payment = document.getElementById("payment").value

    fetch('http://localhost:5000/addPayment', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          payment,
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
</script>