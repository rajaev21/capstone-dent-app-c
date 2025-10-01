<form action="./api/finishAppointment.php" method="POST" class="row">
  <div class="modal fade" id="cancelBooked" tabindex="-1" aria-labelledby="cancelBookedModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cancelBookedModalLabel">Cancel Appointment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <h6>Do you want to cancel this appointment?</h6>
          <div class="p-1">
            <label class="form-label d-flex justify-content-start" for="reason">Reason for cancellation:</label>
            <textarea class="form-control" name="reason" id="reason" row="4" required></textarea>
            <input type="hidden" name="aid" value="<?= $aid ?>">
            <input type="hidden" name="user_id" value="<?= $user_id ?>">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <input class="btn btn-danger" type="submit" name="cancelBooked" value="Cancel Appointment">
        </div>
      </div>
    </div>
  </div>
</form>