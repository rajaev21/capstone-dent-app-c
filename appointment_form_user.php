<form action="./api/setAppointment.php" method="POST">
  <div class="container">
    <div class="p-1">
      <label for="startAppointment">Start Date:</label>
      <input type="date" name="date" id="date" value="<?php echo $date ?>" readonly>
    </div>
    <div class="p-1">
      <label for="startAppointment">Start Time:</label>
      <input type="time" name="startAppointment" id="startAppointment" value="<?php echo $timeStart ?>" readonly>
    </div>
    <div class="p-1">
      <label for="endAppointment">End:</label>
      <input type="time" name="endAppointment" id="endAppointment" readonly>
    </div>
    <div class="p-1">
      <label for="serviceType">Dental Service:</label>
      <select name="serviceType" id="serviceType" onchange="setEndAppointmentTime(this)" required>
      </select>
    </div>
    <div class="p-1">
      <label class="form-label d-flex justify-content-start" for="note">Note:</label>
      <textarea class="form-control" name="note" id="note" row="4"></textarea>
    </div>

    <?php if ((isset($_GET['user_id']) && isset($_GET['aid']))) { ?>
      <div class="p-1">
        <label class="form-label d-flex justify-content-start" for="reason">Reason for rescheduling:</label>
        <textarea class="form-control" name="reason" id="reason" row="4" required></textarea>
      </div>
      <input type="hidden" name="aid" value="<?= $aid ?>">
      <input type="hidden" name="user_id" value="<?= $user_id ?>">

    <?php } else {
    ?> <input type="hidden" name="user_id" value="<?= $_SESSION['id'] ?>"> <?php } ?>

    <div class="p-1">
      <a class="btn btn-secondary" href="index.php?selectedDate=<?= $date ?>">Go Back</a>
      <input type="submit" value="Submit" name="submit" id="submitButton" class="btn btn-primary">
    </div>
  </div>
</form>

<script>
  async function getNextAppointment() {
    const endAppointment = document.getElementById("endAppointment").value;
    const startAppointment = document.getElementById("startAppointment").value;
    const date = document.getElementById("date").value;
    const submitButton = document.getElementById("submitButton");
    console.log("Start:", startAppointment, "End:", endAppointment, "Date:", date);
    fetch(`http://localhost:5000/getNextAppointment?date=${date}&end=${endAppointment}&start=${startAppointment}`)
      .then(res => res.json())
      .then(data => {
        if (data && data.length > 0) {
          submitButton.disabled = true;
          alert(`Conflict schedule with next appointment at ${data[0].appointment_start}`);
        }
      })
      .catch(err => console.error('Error fetching next appointment:', err));
  }

  async function getServices() {
    const res = await fetch('http://localhost:5000/getServices');
    const data = await res.json();
    const submitButton = document.getElementById("submitButton");

    const select = document.getElementById("serviceType");
    select.innerHTML = "";
    const defaultOption = document.createElement("option");
    defaultOption.value = "";
    defaultOption.textContent = "Select a service";
    select.appendChild(defaultOption);
    data.forEach(element => {
      const option = document.createElement("option");
      option.value = element.id;
      option.textContent = `${element.service_type} est time: ${element.estimated_time}`;
      option.setAttribute("data-time", element.estimated_time);
      option.setAttribute("data-price", element.price);
      select.appendChild(option);
    });

    submitButton.disabled = true;
  }

  function setEndAppointmentTime(service) {
    const submitButton = document.getElementById("submitButton");
    const selectedOption = service.options[service.selectedIndex];
    const estimatedTime = selectedOption.getAttribute("data-time");

    const endAppointment = document.getElementById("endAppointment");
    const startAppointment = document.getElementById("startAppointment");

    if (!startAppointment.value || !estimatedTime) {
      console.warn("Missing start time or estimated time");
      submitButton.disabled = true;
      endAppointment.value = "";
      return;
    }

    const selectedServiceId = document.getElementById("serviceType").value;
    if (!selectedServiceId) {
      submitButton.disabled = true;
      endAppointment.value = "";
      return;
    }

    const [startHour, startMinute] = startAppointment.value.split(":").map(Number);
    const startDate = new Date();
    startDate.setHours(startHour);
    startDate.setMinutes(startMinute);

    const [estHour, estMin] = estimatedTime.split(":").map(Number);

    const endDate = new Date(startDate.getTime());
    endDate.setHours(startDate.getHours() + (estHour || 0));
    endDate.setMinutes(startDate.getMinutes() + (estMin || 0));

    const formattedEnd = endDate.toTimeString().slice(0, 5);

    endAppointment.value = formattedEnd;
    submitButton.disabled = false;
    getNextAppointment()
  }

  document.addEventListener("DOMContentLoaded", async () => getServices());
</script>