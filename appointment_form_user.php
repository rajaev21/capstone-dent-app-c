<form action="./api/setAppointment.php" method="POST">
  <div class="container d-flex align-items-center">
    <div class="container">
      <div class="p-1">
        <label for="startAppointment">Start Date:</label>
        <input type="date" name="date" value="<?php echo $date ?>" readonly>

      </div>
      <div class="p-1">
        <label for="startAppointment">Start Time:</label>
        <input type="time" name="startAppointment" value="<?php echo $timeStart ?>" readonly>
      </div>
      <div class="p-1">
        <label for="endAppointment">End:</label>
        <input type="time" name="endAppointment" value="<?php echo $timeEnd ?>" id="endAppointment" readonly>
      </div>
      <div class="p-1">
        <label for="serviceType">Type</label>
        <select name="serviceType" id="serviceType">
          <?php
          $response = file_get_contents('http://localhost:5000/getServices?');
          $response = json_decode($response, true);
          foreach ($response as $result) {
            echo '<option value="' . $result['id'] . '">' . $result['service_type'] . '</option>';
          }
          ?>
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
      <?php } ?>

      <div class="p-1">
        <input type="hidden" name="user_id" value="<?= $_SESSION['id'] ?>">
        <input type="hidden" name="status" value="1">
        <input type="submit" value="Submit" name="submit" class="btn btn-primary">
      </div>
    </div>
</form>