<div class="modal fade" id="reschedule" tabindex="-1" aria-labelledby="rescheduleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rescheduleModalLabel">Reschedule patient</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h6>Do you want to reschedule?</h6>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <a type="button" class="btn btn-primary" href="<?= "./index.php?user_id=" . urlencode($user_id) . "&aid=" . urlencode($aid) ?>"> Yes </a>
      </div>
    </div>
  </div>
</div>