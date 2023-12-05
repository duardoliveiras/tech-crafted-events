function getNotifications(){

    fetch('/load-notifications')
        .then(response => {
            if(!response.ok){
                throw Error(response.statusText)
            }
            return response.json();
        })
        .then(data => {
            console.log(data);
            document.getElementById('notificacoesContainer').innerHTML = '';

            data.forEach(notification => {
                var cardHtml = '<div class="card mb-4 border-0 shadow-sm">';
                cardHtml += '<div class="card-body">';
                cardHtml += '<div class="d-flex align-items-center">';
                cardHtml += '<img class="rounded-circle shadow-1-strong me-2" src="' + asset('storage/' + notification.event_notification.event.image_url ) + '" alt="event" width="50" height="50"/>';
                cardHtml += '<p class="card-text text-muted mb-4">' + notification.event_notification.notification_text + '</p>';
                cardHtml += '</div>';
                cardHtml += '<a href="' + routeEventsShow.replace(':id', notification.event_notification.event.id) + '" class="btn btn-primary m-1 btn-sm">';
                cardHtml += '<i class="bi bi-eye"></i> View Event </a>';
                cardHtml += '<button onclick="readNotification(' + notification.id + ')" class="btn btn-secondary btn-sm">Read</button>';
                cardHtml += '</div>';
                cardHtml += '</div>';

                document.getElementById('notificacoesContainer').insertAdjacentHTML('beforeend', cardHtml);
            });
        })

        .catch(error => {   
            console.log(error);
        });
}

function updateRead() {
    document.getElementById('formUpdateRead').submit();
}

function readNotification(notificationId) {
    var url = '/update-read/' + notificationId;

    var options = {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({}),
    };

    fetch(url, options)
        .then(response => {
            if (!response.ok) {
                throw Error(response.statusText);
            }
            console.log('read!');
            getNotifications();''
        })
        .catch(error => {
            console.error('Erro', error);
        });
}
