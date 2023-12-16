document.addEventListener('DOMContentLoaded', function () {
    let legalIdInput = document.getElementById('legal_id');
    let connectStripeBtn = document.getElementById('connectStripeBtn');

    legalIdInput.addEventListener('input', function () {
        connectStripeBtn.disabled = legalIdInput.value.trim() === '';
    });
});