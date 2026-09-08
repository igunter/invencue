<div class="modal fade" id="age-range-confirm-modal" tabindex="-1" aria-labelledby="age-range-confirm-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="age-range-confirm-modal-label">Change age range?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                You already have an age range selected. Do you want to change it?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="age-range-confirm-button">Change</button>
            </div>
        </div>
    </div>
</div>

<script>
    window.confirmAgeRangeChange = function (button, proceed) {
        const currentlySelected = document.querySelector('.age-range-button.selected');

        if (!currentlySelected || currentlySelected === button) {
            proceed();
            return;
        }

        const modalElement = document.getElementById('age-range-confirm-modal');
        const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
        const confirmButton = document.getElementById('age-range-confirm-button');

        const onConfirm = () => {
            confirmButton.removeEventListener('click', onConfirm);
            modal.hide();
            proceed();
        };

        confirmButton.addEventListener('click', onConfirm);
        modal.show();
    };
</script>
