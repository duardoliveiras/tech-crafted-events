function onScanSuccess(qrCodeMessage) {
    document.getElementById('result').innerHTML = qrCodeMessage;

    let parts = qrCodeMessage.split(','); // Separar por vírgulas

    // Preencher os campos do formulário
    let form = document.getElementById('qrForm');
    form.action = '/events/' + parts[0] + '/ticket/authenticate'; // Event ID
    document.getElementById('ticket_id').value = parts[1]; // Ticket ID
    document.getElementById('user_id').value = parts[2]; // User ID
    document.getElementById('submitForm').click();
}

function onScanError(errorMessage) {
    // Tratar erro de leitura do QR Code
    console.error(errorMessage);
}

let html5QrcodeScanner = new Html5QrcodeScanner(
    "reader", { fps: 10, qrbox: 250 });
html5QrcodeScanner.render(onScanSuccess, onScanError);