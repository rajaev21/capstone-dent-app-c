<?php
if (empty($_SESSION)) {
  session_start();
  date_default_timezone_set('Asia/Manila');
  if (empty($_SESSION['id']) && $_SESSION['confirm'] == false) {
    header('location:login.php');
  }
}
$user_id = $_SESSION['id'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dent App</title>
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js'></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <?php
  include('nav.php');
  if ($role === "user") {
    include('welcome.php');
  }
  ?>
  <div class="container my-5 d-flex" id="calendarContainer">
    <div class="container col-4 selectContainer">
      <h3 class="text-center fw-bold">Select Services here first</h3>
      <div class="row row-cols-3 mb-5" id="checkboxContainer"></div>
      <div class="input-group mb-5">
        <span class="input-group-text">Note</span>
        <textarea type="text" class="form-control" name="note" id="note"> </textarea>

        <div class="container d-flex justify-content-end mt-5">
          <button class="btn btn-primary" id="addAppointment" onclick="requestAppointment()" disabled>Add Appointment</button>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="container" id="calendar"></div>
    </div>
  </div>

  <div class="modal" tabindex="-1" id="appointmentModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Appointment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" id="rejectButton">Reject</button>
          <button type="button" class="btn btn-secondary" id="rescheduleByAdmin">Reschedule</button>
          <button type="button" class="btn btn-primary" id="viewButton">View More</button>
          <button type="button" class="btn btn-primary" id="proceedButton">Proceed</button>
          <button type="button" class="btn btn-primary" id="approveButton">Approve</button>
          <button type="button" class="btn btn-danger" id="removeAppointmentButton">Remove appointment</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal" tabindex="-1" id="rescheduleAppointment">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="rescheduleModalTitle"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="h5">Reschedule appointment ?</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
          <button type="button" class="btn btn-primary" id="rescheduleButton">Yes</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal" tabindex="-1" id="adminRequest">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="adminRequestTitle">Dental Reschedule Request</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="adminRequestBody">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
          <button type="button" class="btn btn-primary" id="adminRequestButton">Yes</button>
        </div>
      </div>
    </div>
  </div>


  <div class="modal" tabindex="-1" id="cancelModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Cancel Appointment?</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal" tabindex="-1" id="waiverModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Please sign the waiver</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <?php include("waiver.php") ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

  <script>
    const user_id = parseInt('<?= $user_id ?>')
    const role = '<?= $role ?>'
    let isAppointedData = null;
    const selectContainer = document.querySelector('.selectContainer')
    const removeAppointmentButton = document.getElementById("removeAppointmentButton");
    const rescheduleByAdmin = document.getElementById("rescheduleByAdmin");
    const rejectButton = document.getElementById("rejectButton");
    const viewButton = document.getElementById("viewButton");
    const approveButton = document.getElementById("approveButton")
    const proceedButton = document.getElementById("proceedButton")
    var calendarEl = document.getElementById('calendar');
    let addAppointment = document.getElementById("addAppointment")
    let selectedDate = new Date();
    let selectedServices = [];
    let serviceEst = [];
    let newAppointment = {};
    let isRescheduling = false;
    let bHours = [{
        startTime: '08:00',
        endTime: '12:00',
      },
      {
        startTime: '13:00',
        endTime: '20:00',
      }
    ]

    document.addEventListener("DOMContentLoaded", async function() {
      if (role === 'user') {
        isValidated = await isValidated()
        if (isValidated[0].isValidated == 0) {
          window.location.href = `http://localhost/salologan/customer_form.php?id=${user_id}`
        }
        isAppointedData = await isAppointedUser()
        if (isAppointedData.length > 0) {
          userAppointment = isAppointedData[0]
          selectContainer.innerHTML = ``
          let newHtml = "";
          switch (userAppointment.status) {
            case "1":
            case "6":
              newHtml =
                `
                <div class="text-center d-grid"> 
                <h4 class="fw-bold"> Waiting for approval </h4>
                <button class="btn btn-info" onclick="jumpToUserSchedule('${userAppointment.date}')"> Jump to my schedule </button>
                </div>
                <div class="text-center mt-3"> 
                <button class="btn btn-warning" onclick="cancelModal('${userAppointment.aid}')"> Cancel Appointment </button>
                <button class="btn btn-primary" onclick="rescheduleModal('${userAppointment.aid}')"> Reschedule </button>
                </div>
                `
              break;
            case "7":
              newHtml =
                `
                  <div class="text-center d-grid"> 
                  <h4 class="fw-bold">Dental is asking for reschedule</h4>
                  <button class="btn btn-info" onclick="jumpToUserSchedule('${userAppointment.date}')"> Jump to my schedule </button>
                  </div>
                  <div class="text-center mt-3"> 
                  <button class="btn btn-warning" onclick="cancelModal('${userAppointment.aid}')"> Cancel Appointment </button>
                  <button class="btn btn-primary" onclick="rescheduleModal('${userAppointment.aid}')"> Reschedule </button>
                  </div>
                  `
              break;
            case "9":
              newHtml =
                `
              <div class="text-center d-grid"> 
              <h4 class="fw-bold"> Waiting for cancellation approval </h4>
              <button class="btn btn-info" onclick="jumpToUserSchedule('${userAppointment.date}')"> Jump to my schedule </button>
              </div>
              <div class="text-center mt-3"> 
              <button class="btn btn-primary" onclick="rescheduleModal('${userAppointment.aid}')"> Reschedule </button>
              </div>
              `
              break;
            case "2":
              newHtml =
                `
                <div class="text-center d-grid"> 
                  <h4 class="fw-bold">Appointment Approved</h4>
                  <button class="btn btn-info" onclick="jumpToUserSchedule('${userAppointment.date}')"> Jump to my schedule </button>
                </div>
                <div class="text-center mt-3"> 
                  <button class="btn btn-warning" onclick="cancelModal('${userAppointment.aid}')"> Cancel Appointment </button>
                  <button class="btn btn-primary" onclick="rescheduleModal('${userAppointment.aid}')"> Reschedule </button>
                </div>
                `
              break;
            default:
              break;
          }
          selectContainer.innerHTML += newHtml;
        } else {
          selectContainer.classList.toggle('d-none', false)
          approveButton.classList.toggle("d-none", false)
          const services = await loadServices();
          renderServices(services)
        }
      } else {
        selectContainer.classList.toggle('d-none', true)
        removeAppointmentButton.classList.toggle("d-none", true)

      }
    })

    // main FullCalendar
    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      slotDuration: "00:05:00",
      slotMinTime: "08:00",
      slotMaxTime: "20:00",
      eventMaxStack: 1,
      allDaySlot: false,
      overlap: false,
      eventOrder: "-groupId",
      eventOverlap: true,
      eventStartEditable: function(info) {
        if (info.event.title === "expired") return true
        console.log(info.event.title)
      },
      eventAllow: function(dropInfo, draggedEvent) {
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        return dropInfo.start >= today;
      },
      // eventConstraint: 'businessHours',
      businessHours: bHours,
      slotLabelFormat: {
        hour: '2-digit',
        minute: '2-digit',
      },
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek'
      },
      eventDidMount: function(info) {
        let color, textColor = "#fff";
        switch (info.event.title) {
          case 'pending':
            color = '#dfd402ff';
            textColor = "#000";
            break;
          case 'approved':
            color = '#0D6EFD';
            break;
          case 'done':
            color = '#198754';
            break;
          case 'expired':
            color = '#d70202ff';
            break;
          case 'reschedule_pending':
          case 'cancel_pending':
            color = '#9C27B0';
            break;
          case 'reschedule_pending_admin':
          case 'cancel_pending_admin':
            color = '#c034d8ff';
            break;
          case 'done_rescheduled':
            color = '#20C997';
            textColor = "#000";
            break;
          case 'cancelled':
            color = '#91a1aeff';
            info.event.setProp('display', 'background')
            break;
          default:
            color = '#91a1aeff';
            break;
        }
        info.el.style.backgroundColor = color;
        info.el.style.color = textColor;
        info.el.querySelectorAll('*').forEach(e => e.style.color = textColor);
      },
      events: async function(fetchInfo, successCallback, failureCallback) {
        try {
          const response = await fetch(`http://localhost:5000/getAppointments?role=${role}&user_id=${user_id}`);
          const data = await response.json();
          successCallback(data);
        } catch (error) {
          console.error('Error loading appointments:', error);
          failureCallback(error);
        }
      },
      eventDrop: function(info) {
        if (isRescheduling) enableReqAppointmentButton()
        newAppointment.date = info.event.startStr.split("T")[0]
        newAppointment.start = getFormattedTime(info.event.startStr)
        newAppointment.end = getFormattedTime(info.event.endStr)
        if (role === "admin" && !isRescheduling) {
          openAppointmentModal(info)
        }
      },
      eventClick: function(info) {
        if (isRescheduling) return
        if (role === "admin") {
          openAppointmentModal(info)
        } else {
          if (info.event.title === "reschedule_pending_admin") {
            openAdminRequestModal(info.event)
          }
        }
      },
      dateClick: function(info) {
        if (info.view.type === 'timeGridWeek') {
          if (info.date < new Date()) return
          if (role === 'user') {
            if (isRescheduling) {
              selectRescheduleDateAdmin(info)
            } else if (serviceEst.length > 0) {
              const totalEstimatedTime = serviceEst.reduce((x, y) => parseInt(x) + parseInt(y), 0);
              const startDate = new Date(info.dateStr);
              const endDate = new Date(startDate.getTime() + totalEstimatedTime * 60000);
              if (!withinBusinessHours(startDate, endDate)) {
                alert("Set appointment within business hours");
                return;
              }
              const oldEvent = calendar.getEvents()
              oldEvent.forEach((e, i) => {
                if (e.title === "New Appointment") {
                  e.remove();
                }
              });

              calendar.addEvent({
                id: newAppointment.prevAid,
                title: 'New Appointment',
                start: startDate.toISOString(),
                end: endDate.toISOString(),
                editable: true,
                eventDurationEditable: false
              });

              newAppointment.date = startDate.toISOString().split("T")[0]
              newAppointment.start = getFormattedTime(startDate)
              newAppointment.end = getFormattedTime(endDate)

              addAppointment.disabled = false
            }
          } else {
            if (isRescheduling) {
              const oldEvent = calendar.getEvents()
              oldEvent.forEach((e, i) => {
                if (e.title === "New Appointment") {
                  e.remove();
                }
              });
              selectRescheduleDateAdmin(info)
            }
          }
        } else {
          calendar.changeView('timeGridWeek', info.dateStr);
        }
      }
    });

    function handleScheduleOrganize(info) {
      return
    }

    function getOverlap(targetEvent) {
      const exceptions = [
        "pending",
        "reschedule_pending_admin",
        "reschedule_pending",
        "cancelled",
        "expired",
        "cancel_pending",
        "cancel_pending_admin"
      ];

      const allEvents = calendar.getEvents();
      const overlaps = allEvents.filter(event => {
        if (event.id === targetEvent.id) return false;
        return (
          event.start < targetEvent.end &&
          event.end > targetEvent.start && !exceptions.includes(event.title)
        );
      });
      return overlaps
    }

    function jumpToUserSchedule(date) {
      calendar.changeView('timeGridWeek', date)
    }

    function renderServices(services) {
      services.forEach(service => {
        const checkboxContainer = document.createElement("div");
        checkboxContainer.className = "col form-check";

        const checkbox = document.createElement("input");
        checkbox.type = "checkbox";
        checkbox.value = service.id;
        checkbox.className = "form-check-input";
        checkbox.id = `service-${service.id}`;
        checkbox.dataset.time = service.estimated_time;
        checkbox.dataset.price = service.price;
        checkbox.addEventListener("change", (e) => {
          const event = calendar.getEvents().find(e => e.title === "New Appointment");
          console.log(event)
          if (event) {
            event.remove();
            newAppointment = {};
            addAppointment.disabled = true;
          }
          if (e.target.checked) {
            serviceEst.push(checkbox.dataset.time);
            selectedServices.push(checkbox.value);
          } else {
            serviceEst = serviceEst.filter(service => service !== checkbox.dataset.time);
            selectedServices = selectedServices.filter(service => service !== checkbox.value);
          }
        });

        const label = document.createElement("label");
        label.className = "form-check-label ms-2";
        label.htmlFor = checkbox.id;
        label.textContent = service.service_type;

        checkboxContainer.appendChild(checkbox);
        checkboxContainer.appendChild(label);
        if (isRescheduling) {
          document.querySelector('.selectContainer').prepend(checkboxContainer)
        } else {
          document.getElementById("checkboxContainer").appendChild(checkboxContainer);
        }
      });
    }

    async function loadServices() {
      const response = await fetch('http://localhost:5000/getServices');
      const data = await response.json();
      return data;
    }

    async function getAppointmentServices(aid) {
      const response = await fetch(`http://localhost:5000/getAppointmentServices?aid=${aid}`);
      const data = await response.json();
      return data;
    }

    function requestAppointment() {
      newAppointment.user_id = user_id
      newAppointment.services = selectedServices
      newAppointment.note = document.getElementById('note').value.trim();
      sessionStorage.setItem("newAppointment", JSON.stringify(newAppointment));
      window.location.href = `waiver.php?id=${user_id}`;
    }

    function getFormattedTime(time) {
      const date = new Date(time);

      const hours = String(date.getHours()).padStart(2, "0")
      const minutes = String(date.getMinutes()).padStart(2, "0")

      return `${hours}:${minutes}`;
    }

    // function hasOverlap(start, end) {
    //   const exceptions = [
    //     "pending",
    //     "reschedule_pending_admin",
    //     "reschedule_pending",
    //     "cancelled",
    //     "expired",
    //     "cancel_pending",
    //     "cancel_pending_admin"
    //   ];

    //   const overlappingEvents = calendar.getEvents().filter(event =>
    //     start < new Date(event.end) &&
    //     end > new Date(event.start) &&
    //     !exceptions.includes(event.title)
    //   );

    //   return overlappingEvents.length >= 3;
    // }
    var prevEvent = null

    function enableReqAppointmentButton() {
      const reasonInput = document.getElementById("reason").value.trim();
      const button = document.getElementById("RAbutton");
      button.disabled = (
        reasonInput === "" ||
        !isRescheduling ||
        !newAppointment.end ||
        !newAppointment.start
      );
    }

    function rescheduleModal(id) {
      const event = calendar.getEventById(id)
      const modal = new bootstrap.Modal(document.getElementById("rescheduleAppointment"));
      const modalTitle = document.getElementById("rescheduleModalTitle");
      const button = document.getElementById("rescheduleButton")
      modalTitle.textContent = `${event.title}`
      modalTitle.classList.add("text-capitalize")
      button.addEventListener("click", async function() {
        if (event) {
          event.setProp('editable', true);
          prevEvent = id
          event.setProp('display', 'background');
          isRescheduling = true
          rescheduleAppointment(id)
        }
        modal.hide()
        const services = await loadServices();
        renderServices(services)
      })
      modal.show()
    }

    function rescheduleAppointment(id) {
      selectContainer.innerHTML = ``
      let newHtml = `
        <div class="text-center"> 
        <h6>Reason:</h6>
            <div class="form-floating mb-2">
              <textarea class="form-control" placeholder="Provide reason to notify the client" id="reason" oninput="enableReqAppointmentButton()"></textarea>
              <label for="reason">Reason</label>
            </div>
          <button class="btn btn-secondary text-end" onclick="window.location.reload()"> Cancel </button> 
          <button class="btn btn-primary text-end" id="RAbutton" onclick="rescheduleRequest('${id}')" disabled> Reschedule </button> 
        </div>
        `
      selectContainer.innerHTML += newHtml
    }

    function selectRescheduleDate(event) {
      const prevEvent = calendar.getEventById(newAppointment.prevAid)
      const durationMs = prevEvent.end.getTime() - prevEvent.start.getTime();
      const startDate = new Date(event.dateStr);
      const endDate = new Date(startDate.getTime() + durationMs);
      if (!withinBusinessHours(startDate, endDate)) {
        alert("Set appointment within business hours");
        return;
      }

      const oldEvent = calendar.getEvents()
      oldEvent.forEach((e, i) => {
        if (e.title === "New Appointment") {
          e.remove();
        }
      });

      calendar.addEvent({
        id: 'newAppointment',
        title: 'New Appointment',
        start: startDate,
        end: endDate,
      });

      newAppointment.date = startDate.toISOString().split("T")[0]
      newAppointment.start = getFormattedTime(startDate)
      newAppointment.end = getFormattedTime(endDate)

      enableReqAppointmentButton()
    }

    function selectRescheduleDateAdmin(event) {
      if (serviceEst.length > 0) {
        const totalEstimatedTime = serviceEst.reduce((sum, val) => sum + Number(val), 0);
        const startDate = new Date(event.dateStr);
        const endDate = new Date(startDate.getTime() + totalEstimatedTime * 60000);
        if (!withinBusinessHours(startDate, endDate)) {
          alert("Set appointment within business hours");
          return;
        }

        const oldEvent = calendar.getEvents()
        oldEvent.forEach((e, i) => {
          if (e.title === "New Appointment") {
            e.remove();
          }
        });

        calendar.addEvent({
          id: prevEvent,
          title: 'New Appointment',
          start: startDate.toISOString(),
          end: endDate.toISOString(),
          editable: true,
          eventDurationEditable: false
        });

        newAppointment.date = startDate.toISOString().split("T")[0]
        newAppointment.start = getFormattedTime(startDate)
        newAppointment.end = getFormattedTime(endDate)
      }

      enableReqAppointmentButton()
    }

    async function openAppointmentModal(info) {
      const event = calendar.getEventById(info.event.id)
      const overlapId = getOverlap(info.event).map(item => item.id)
      const appointmentServices = await getAppointmentServices(info.event.id)
      const appointmentModal = new bootstrap.Modal(document.getElementById("appointmentModal"));
      const modalTitle = document.querySelector(".modal-title")
      modalTitle.classList.add("text-capitalize")
      modalTitle.textContent = `${appointmentServices[0].fullname}`;
      const modalBody = document.querySelector(".modal-body")
      viewButton.classList.toggle("d-none", true);
      approveButton.classList.toggle("d-none", true)
      rejectButton.classList.toggle("d-none", true)
      rescheduleByAdmin.classList.toggle("d-none", true)
      proceedButton.classList.toggle("d-none", true)
      switch (info.event.title) {
        case "pending":
        case "reschedule_pending":
          approveButton.classList.toggle("d-none", false)
          rejectButton.classList.toggle("d-none", false)
          break;
        case "reschedule_pending_admin":
          viewButton.classList.toggle("d-none", true);
          approveButton.classList.toggle("d-none", true)
          rejectButton.classList.toggle("d-none", true)
          rescheduleByAdmin.classList.toggle("d-none", true)
          break;
        case "approved":
          viewButton.classList.toggle("d-none", false)
          approveButton.classList.toggle("d-none", true)
          rejectButton.classList.toggle("d-none", true)
          break;
        case "cancel_pending":
          viewButton.classList.toggle("d-none", true)
          rescheduleByAdmin.classList.toggle("d-none", true)
          break;
        case "done":
          rescheduleByAdmin.classList.toggle("d-none", false)
          break;
        case "expired":
          rescheduleByAdmin.classList.toggle("d-none", false)
          break;
        case "cancelled":
          rescheduleByAdmin.classList.toggle("d-none", false)
          break;

        default:
          viewButton.classList.toggle("d-none", true)
          approveButton.classList.toggle("d-none", true)
          rejectButton.classList.toggle("d-none", true)
          break;
      }

      let servicesHTML = "";
      const date = new Date(info.event.start)
      const startTime = info.event.start.toTimeString().slice(0, 5)
      const endTime = info.event.end.toTimeString().slice(0, 5)
      const options = {
        year: "numeric",
        month: "long",
        day: "numeric"
      };
      const formattedDate = date.toLocaleDateString("en-US", options);
      servicesHTML += `
        <p class="mb-2">
          <strong>Status : </strong> ${info.event.title}
        </p>
        <p class="mb-2">
          <strong>Date : </strong> ${formattedDate }
        </p>
        <p class="mb-2">
          <strong>Start : </strong> ${startTime}
        </p>
        <p class="mb-2">
          <strong>End : </strong> ${endTime}
        </p>
        <h6> Services </h6>
        `
      appointmentServices.forEach(item => {
        servicesHTML += `
        <p class="mb-2">
          <strong>${item.service_type}</strong> — ₱${item.price}
        </p>
        `;
      });
      modalBody.innerHTML = servicesHTML;

      rescheduleByAdmin.addEventListener("click", async function() {
        if (event) {
          event.setProp('editable', true);
          prevEvent = info.event.id
          event.setProp('display', 'background');
          isRescheduling = true
          selectContainer.classList.toggle("d-none", false)
          if (calendar) {
            calendar.destroy();
          }
          calendar.render();
          rescheduleAppointment(info.event.id)
        }
        appointmentModal.hide()
        const services = await loadServices();
        renderServices(services)
      })

      removeAppointmentButton.addEventListener("click", function() {
        removeAppointmentButton.classList.add("btn-danger")
        info.event.remove()
        addAppointment.disabled = true
        newAppointment = undefined;
        appointmentModal.hide()
      })

      approveButton.addEventListener("click", function() {
        approveAppointment(info.event.id, overlapId)
        appointmentModal.hide()
      })

      rejectButton.addEventListener("click", function() {
        servicesHTML = ``

        servicesHTML += `
            <div class="form-floating">
              <textarea class="form-control" placeholder="Provide reason to notify the client" id="reason" oninput="enableCButton()"></textarea>
              <label for="reason">Reason</label>
            </div>`

        modalBody.innerHTML = servicesHTML;

        approveButton.classList.toggle("d-none", true)
        rejectButton.classList.toggle("d-none", true)
        proceedButton.classList.toggle("d-none", false)
        rescheduleByAdmin.classList.toggle("d-none", true)
        proceedButton.disabled = true
        proceedButton.addEventListener("click", function() {
          const reason = document.getElementById("reason").value;
          approveButton.classList.toggle("d-none", false)
          rejectButton.classList.toggle("d-none", false)
          proceedButton.classList.toggle("d-none", true)
          rejectAppointment(info.event.id, reason)
          appointmentModal.hide()
        })
      })
      viewButton.addEventListener("click", function() {
        window.location.href = `http://localhost/salologan/customer_details.php?aid=${info.event.id}`
      })

      appointmentModal.show()
    }

    function cancelModal(id) {
      selectContainer.innerHTML = ``
      let newHtml = `
        <div class="text-center"> 
        <h6>Reason:</h6>
            <div class="form-floating mb-2">
              <textarea class="form-control" placeholder="Provide reason to notify the client" id="reason" oninput="enableCButton()"></textarea>
              <label for="reason">Reason</label>
            </div>
          <button class="btn btn-secondary text-end" onclick="window.location.reload()"> Cancel </button> 
          <button class="btn btn-primary text-end" id="cButton" disabled> Cancel Appoinment </button> 
        </div>
        `
      selectContainer.innerHTML += newHtml

      document.getElementById("cButton").addEventListener("click", function() {
        const reasonInput = document.getElementById("reason").value.trim();
        const response = fetch(`http://localhost:5000/cancelBooked?aid=${id}&reason=${reasonInput}&user_id=${user_id}`);
        window.location.reload()
      })
    }

    function enableCButton() {
      const reasonInput = document.getElementById("reason").value.trim();
      const button = document.getElementById("cButton");
      document.getElementById("proceedButton").disabled = (reasonInput === "")
      button.disabled = (reasonInput === "");
    }

    async function openAdminRequestModal(event) {
      const overlapId = getOverlap(event).map(item => item.id)
      const response = await fetch(`http://localhost:5000/getReason?aid=${event.id}`);
      const reason = await response.json();
      if (reason.sentTo !== user_id) return;
      const adminRequestModal = new bootstrap.Modal(document.getElementById("adminRequest"));
      const adminRequestBody = document.getElementById("adminRequestBody")
      adminRequestBody.innerHTML = `
        <h6>Admin is requesting for appointment reschedule</h6>

        <p><strong>Reason:</strong> ${reason.reason}</p>
      `
      const adminRequestButton = document.getElementById("adminRequestButton")
      adminRequestButton.addEventListener("click", async function() {
        approveAppointment(event.id, overlapId)
      })
      adminRequestModal.show()
    }

    function rejectAppointment(aid, reason) {
      fetch(`http://localhost:5000/rejectAppointment?aid=${aid}&user_id=${user_id}&reason=${reason}`)
        .then(res => {
          if (!res.ok) throw new Error('Network response was not ok');
          return res.json();
        }).then(data => {
          console.log('Success:', data);
        }).then(() => window.location.reload())
        .catch(error => {
          console.error('Error:', error);
        });
    }

    function approveAppointment(aid, overlapId) {
      fetch('http://localhost:5000/approveAppointment', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            role,
            aid,
            user_id,
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

    function withinBusinessHours(dateStart, dateEnd) {
      const day = dateStart.getDay();
      const morningStart = convertTimeToNumber(bHours[0].startTime)
      const morningEnd = convertTimeToNumber(bHours[0].endTime)
      const afternoonStart = convertTimeToNumber(bHours[1].startTime)
      const afternoonEnd = convertTimeToNumber(bHours[1].endTime)
      const start = convertTimeToNumber(dateStart.toTimeString().slice(0, 5))
      const end = convertTimeToNumber(dateEnd.toTimeString().slice(0, 5))

      const isMorning = start >= morningStart && end <= morningEnd;
      const isAfternoon = start >= afternoonStart && end <= afternoonEnd;

      return isMorning || isAfternoon
    }

    function convertTimeToNumber(time) {
      const hours = Number(time.split(':')[0]);
      const minutes = Number(time.split(':')[1]) / 60;
      return hours + minutes;
    }

    async function isValidated() {
      const response = await fetch(`http://localhost:5000/getCustomerDetails?user_id=${user_id}`);
      const data = await response.json();
      return data;
    }
    async function isAppointedUser() {
      const response = await fetch(`http://localhost:5000/isAppointed?user_id=${user_id}`);
      const data = await response.json();
      return data;
    }

    function rescheduleRequest(id) {
      newAppointment.prevAid = prevEvent
      newAppointment.role = role
      newAppointment.reason = document.getElementById("reason").value
      newAppointment.user_id = user_id
      newAppointment.services = selectedServices
      fetch('http://localhost:5000/rescheduleRequest', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            newAppointment
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

    calendar.render();
  </script>
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>

</html>