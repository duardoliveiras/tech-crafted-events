let mapPreview;
let mapMarker;

function initMap() {
    mapPreview = L.map('preview-map').setView([-23.550520, -46.633308], 12); // Coordenadas iniciais (São Paulo, por exemplo)

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mapPreview);

    mapMarker = L.marker([-23.550520, -46.633308], {draggable: true}).addTo(mapPreview);

    mapMarker.on('dragend', function (event) {
        updateLatAndLonInputs(mapMarker.getLatLng().lat, mapMarker.getLatLng().lng);
    });
}

function searchInMap() {
    const searchBtn = document.getElementById('searchBtn');
    // const errorToast = new Toast(document.getElementById('errorToast'));
    // const errorToastMessage = document.getElementById('errorToastMessage');

    searchBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Searching...';
    searchBtn.disabled = true;

    const address = document.getElementById('address_search').value;

    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${address}`)
        .then(response => response.json())
        .then(data => {
            const location = data[0];
            mapPreview.setView([location.lat, location.lon], 12);
            mapMarker.setLatLng([location.lat, location.lon]);
            updateLatAndLonInputs(location.lat, location.lon);
        })
        .catch(error => {
            console.error('Error searching:', error);
            // errorToastMessage.textContent = 'Error searching: ' + error.message;
            // errorToast.show();
        })
        .finally(() => {
            searchBtn.innerHTML = 'Search in Map';
            searchBtn.disabled = false;
        });
}


function updateLatAndLonInputs(lat, lng) {
    document.getElementById('lat').value = lat;
    document.getElementById('lon').value = lng;
}

window.onload = initMap;