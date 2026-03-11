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
		<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
			Click here to sign
		</button>

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
						<button type="button" class="btn btn-primary" onclick="save()">Save changes</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="container card p-5 my-3" id="waiver">
		<p class="lh-lg">
			I understand that the information on my account profile was completed correctly and to the best
			of my knowledge; and thus i assume all risks arising from or connected with any
			ommission or interpretation of the same. I also understand that it is my responsibility
			to inform Salapantan Dental Clinic of any charges in the information that i have provided.
			I voluntarily entrust all my dental treatment to Salapantan Dental Clinic and confirm that
			I am consenting to all their dental procedures and clinical recommendations, being as I am
			at all times provided by Salapantan Dental Clinic with sufficient information to give
			my intelligent consent to the same. Having given my voluntary and intelligent consent
			to the same, I hold Salapantan Dental Clinic without responsible for any untoward claim,
			damage or liability in connection with such procedures and recommendations.
		</p>
		<div class="p mt-5">Signature here:<img id="canvasimg" style="width: 400; height: 200px;"></div>
	</div>

	<div class="container d-flex justify-content-end mb-5">
		<button class="btn btn-primary" id="submitRequest" onclick="handleRequestAppointment()" disabled>Submit</button>
	</div>


	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<script>
		function handleRequestAppointment() {
			const newAppointment = JSON.parse(sessionStorage.getItem("newAppointment"));
			fetch('http://localhost:5000/requestAppointment', {
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

			fetch("http://localhost:5000/setSignature", {
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
						document.getElementById("canvasimg").src = dataURL;
						checkAgreement()
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