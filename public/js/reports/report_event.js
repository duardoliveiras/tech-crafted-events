function getEventReports(eventId){
    console.log(eventId);
    fetch(`/admin/reports/load-reports/${eventId}`)
        .then(response => {
            if(!response.ok){
                throw Error(response.statusText)
            }
            return response.json();
        })
        .then(data => {
            console.log(data);
            document.getElementById('reportContainer').innerHTML = '';

            data.forEach(report => {
                var cardHtml = '<div class="card mb-4 border-0 shadow-sm">';
                cardHtml += '<div class="card-body">';
                cardHtml += '<strong>' + report.user.name; + '</strong>';
                cardHtml += '<p>'+ report.description +'</p>';
                cardHtml += '</div';
                cardHtml += '</div';

                document.getElementById('reportContainer').insertAdjacentHTML('beforeend', cardHtml);
            });
        })

        .catch(error => {   
            console.log(error);
        });
}