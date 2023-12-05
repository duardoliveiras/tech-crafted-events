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
                cardHtml += '<p class="card-text text-muted mb-4">' + notification.event_notification.notification_text + '</p>';
                cardHtml += '</div>';
                cardHtml += '</div>';

                document.getElementById('notificacoesContainer').insertAdjacentHTML('beforeend', cardHtml);
            });
        })

        .catch(error => {   
            console.log(error);
        });
}