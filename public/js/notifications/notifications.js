function getNotifications() {
    $.ajax({
        url: '/load-notifications',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log(data);
            $('#notificacoesLista').empty();

            data.forEach(function(notifications) {
                $('#notificacoesLista').append('<li>' + notifications.notification_id + '</li>');
            });

            $('#popupNotificacoes').show();
        },
        error: function(error) {
            console.error('Erro na requisição AJAX', error);
        }
    });
}