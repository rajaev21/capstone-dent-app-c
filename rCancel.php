<div class="modal fade" id="rCancel" tabindex="-1" aria-labelledby="rCancelModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rCancelModalLabel">Cancel appointment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h6>Approve cancellation?</h6>
      </div>
      <div class="modal-footer">
        <form action="api/rCancel.php" method="post">
          <input type="hidden" name="aid" value="<?= $aid ?>">
          <input type="hidden" name="user_id" value="<?= $user_id ?>">
          <input type="hidden" name="admin_id" value="<?= $_SESSION['id'] ?>">
          <input type="submit" class="btn btn-secondary" name="answer" value="No">
          <input type="submit" class="btn btn-danger" name="answer" value="Yes">
        </form>
      </div>
    </div>
  </div>
</div>