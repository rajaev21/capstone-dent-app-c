<div class="modal fade" id="approval" tabindex="-1" aria-labelledby="approvalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approvalModalLabel">Approval</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Do you want to approve?</h6>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form action="./api/finishAppointment.php" method="POST" >
                    <button class="btn btn-primary" type="submit" name="approve">Approve</button>
                    <input type="hidden" name="id" value="<?= $aid ?>" >
                </form>
            </div>
        </div>
    </div>
</div>