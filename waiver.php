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
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
	<style>
		#waiver {
			position: relative;
		}

		#canvasimg {
			position: relative;
			bottom: 50px;
			right: 10%;
			width: 10rem;
		}
	</style>
</head>

<body>
	<?php include('nav.php'); ?>
	<div class="container <?= $_SESSION['role'] == "admin" ? "d-none" : ""; ?>">
		<!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
			Click here to sign
		</button> -->

		<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="exampleModalLabel">Signature here</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<canvas id="can" width="460" height="400" style="border:2px solid;"></canvas>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" id="closeButtonModal" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" onclick="save()" data-bs-dismiss="modal">Save changes</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="container card p-5 my-3" id="waiver">
		<p class="lh-lg" id="waiverNote">
		<div style="font-family: 'Times New Roman', serif; line-height: 1.6;">

			<h3 style="text-align:center;">SALAPANTAN DENTAL CLINIC</h3>
			<p style="text-align:center;">Brgy. 6, Santiago St., San Miguel, Iloilo</p>

			<h2 style="text-align:center; text-decoration: underline;">WAIVER AND INFORMED CONSENT</h2>

			<p>
				I, <span class="name"></span>, here by acknowledge that Dr. <span class="doctor"></span>
				and the dental team of <strong>Salapantan Dental Clinic</strong> have fully explained to me my current dental condition,
				as well as the recommended treatment plan deemed appropriate for my case.
			</p>

			<p>
				I further acknowledge that I have been adequately informed of the nature, purpose, benefits,
				and potential risks associated with the recommended treatment. In addition, I have been made aware
				of the possible consequences of refusing or delaying such treatment, which may include, but are not limited to:
			</p>

			<ul>
				<li>Progression or worsening of my dental condition</li>
				<li>Increased pain or discomfort</li>
				<li>Infection or spread of disease</li>
				<li>Tooth mobility or potential tooth loss</li>
				<li>Increased future costs for corrective or more extensive treatment</li>
			</ul>

			<p>
				Not withstanding the foregoing explanations, I hereby voluntarily and knowingly choose to decline or postpone
				the recommended treatment at this time. I fully understand and accept that Salapantan Dental Clinic,
				Dr. <span class="doctor"></span>, and all affiliated staff shall not be held liable or responsible
				for any complications, progression of disease, or adverse outcomes that may arise as a result of my decision.
			</p>

			<p>By signing this waiver, I hereby affirm that:</p>

			<ul>
				<li>I have been given sufficient opportunity to ask questions, and all my concerns have been addressed to my satisfaction.</li>
				<li>I am making this decision freely, voluntarily, and without any form of pressure, coercion, or undue influence.</li>
				<li>I accept full responsibility for any consequences resulting from my decision to decline or postpone the recommended treatment.</li>
			</ul>

			<br><br>

			<div style="display: flex; justify-content: space-between; width: 100%;">
				<div>
					<button type="button" class="btn btn-transparent"
						data-bs-toggle="modal"
						data-bs-target="#exampleModal"
						style="width: 270px; height: 140px; position: absolute; left: 0px;"
						id="openCanvasBtn">
						Click here to sign
					</button>
					<p>Signature over Printed Name:</p>
					<p class="text-center"><span class="name"></span></p>
					<p>Date: <span class="date"></span> </p>
				</div>
				<div>
					<p><strong>LEA GRACE S. SALAPANTAN, DMD</strong></p>
					<p>Owner/Dentist</p>
					<p>Date: <span class="date"></span> </p>
				</div>

			</div>

		</div>
		</p>

	</div>

	<div class="container d-flex justify-content-end mb-5">
		<button class="btn btn-primary" id="submitRequest" onclick="handleRequestAppointment()" disabled>Submit</button>
	</div>


	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			waiverDetails()
		});
		const today = new Date();
		const formatted = today.toLocaleDateString('en-US', {
			year: 'numeric',
			month: 'long',
			day: 'numeric'
		});

		function handleRequestAppointment() {
			const newAppointment = JSON.parse(sessionStorage.getItem("newAppointment"));
			newAppointment.waiverDate = today
			console.log(newAppointment)
			fetch('https://dent-flask-production.up.railway.app/requestAppointment', {
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
				}).then(() => window.location.href = "index.php")
				.catch(error => {
					console.error('Error:', error);
				});
		}

		function waiverDetails() {
			const newAppointment = JSON.parse(sessionStorage.getItem("newAppointment"));

			const name = "Test"
			const doctor = "_______________ "

			let nameElements = document.getElementsByClassName("name");
			for (let i = 0; i < nameElements.length; i++) {
				nameElements[i].innerHTML = name;
			}

			let doctorElements = document.getElementsByClassName("doctor");
			for (let i = 0; i < doctorElements.length; i++) {
				doctorElements[i].innerHTML = doctor;
			}

			let dateElements = document.getElementsByClassName("date");
			for (let i = 0; i < dateElements.length; i++) {
				dateElements[i].innerHTML = formatted;
			}
		}

		var customerID = `<?= $user_id; ?>`
		var canvas, ctx, flag = false,
			prevX = 0,
			currX = 0,
			prevY = 0,
			currY = 0,
			dot_flag = false;

		var x = "black",
			y = 2;

		function init() {
			canvas = document.getElementById('can');
			ctx = canvas.getContext("2d");
			w = canvas.width;
			h = canvas.height;

			canvas.addEventListener("mousemove", e => findxy('move', e), false);
			canvas.addEventListener("mousedown", e => findxy('down', e), false);
			canvas.addEventListener("mouseup", e => findxy('up', e), false);
			canvas.addEventListener("mouseout", e => findxy('out', e), false);
		}

		document.addEventListener('shown.bs.modal', function(event) {
			if (event.target.id === 'exampleModal') {
				init();
			}
		});
		document.addEventListener('hidden.bs.modal', function(event) {
			if (event.target.id === 'exampleModal') {
				erase();
			}
		});

		function draw() {
			ctx.beginPath();
			ctx.moveTo(prevX, prevY);
			ctx.lineTo(currX, currY);
			ctx.strokeStyle = x;
			ctx.lineWidth = y;
			ctx.stroke();
			ctx.closePath();
		}

		function save() {
			document.getElementById("submitRequest").disabled = false;
			const dataURL = canvas.toDataURL("image/png");

			fetch("https://dent-flask-production.up.railway.app/setSignature", {
					method: "POST",
					headers: {
						"Content-Type": "application/json"
					},
					body: JSON.stringify({
						signature: dataURL,
						user_id: customerID
					})
				})
				.then(res => res.json())
				.then(data => {
					if (data.success) {
						document.getElementById("openCanvasBtn").innerHTML = `<img src="${dataURL}"style="width: 270px; height: 140px;">`;
						document.getElementById("closeButtonModal").click();
					} else {
						console.error("Save failed:", data.message);
					}
				})
				.catch(err => console.error(err));
		}

		function erase() {
			ctx.clearRect(0, 0, w, h);
		}

		function findxy(res, e) {
			const rect = canvas.getBoundingClientRect();
			if (res == 'down') {
				prevX = currX;
				prevY = currY;
				currX = e.clientX - rect.left;
				currY = e.clientY - rect.top;

				flag = true;
				dot_flag = true;
				if (dot_flag) {
					ctx.beginPath();
					ctx.fillStyle = x;
					ctx.fillRect(currX, currY, 2, 2);
					ctx.closePath();
					dot_flag = false;
				}
			}
			if (res == 'up' || res == "out") flag = false;
			if (res == 'move' && flag) {
				prevX = currX;
				prevY = currY;
				currX = e.clientX - rect.left;
				currY = e.clientY - rect.top;
				draw();
			}
		}
	</script>
</body>