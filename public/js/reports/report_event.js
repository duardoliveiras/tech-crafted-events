function getEventReportsView(eventId){
    var reason = document.getElementById("reportReason");
    reason.value = "All";
    getEventReports(eventId, 1);
}

function getEventReports(eventId, page){
 
    var reason = document.getElementById("reportReason").value;
    
    fetch(`/admin/reports/load-reports/${eventId}/${reason}`)
        .then(response => {
            if(!response.ok){
                throw Error(response.statusText)
            }
            return response.json();
        })
        .then(data => {
            pagination(data);
            var curr_pag = document.getElementById('pg'+page);
            curr_pag.classList.add('disabled');

            console.log(data);
            document.getElementById('tableBody').innerHTML = '';
            var i = (page-1)*5;
            var len = i+5;
            for(i; i < len; i++){
                var report = data[i];
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td>${report.user.name}</td>
                    <td>${report.reason}</td>
                    <td>${report.description}</td>
                    <td>
                        <button type="button" class="btn btn-primary"><i class="far fa-eye"></i></button>
                    </td>
                `;

                document.getElementById('tableBody').appendChild(newRow);
            }
        })

        .catch(error => {   
            console.log(error);
        });
}

function pagination(data){
    var keys = Object.keys(data);
    var qt = keys.length;
    var pags = Math.ceil(qt/5);
    var elementoHTML = '';
    document.getElementById('pagination').innerHTML = '';onchange="getEventReports('{{ $event->id }}',1)"
    for(var i = 1; i <= pags; i++){
        elementoHTML += '<li class="page-item" id="pg' + i + '"><a class="page-link" href="javascript:void(0);" onclick="getEventReports(\'' + eventId + '\', ' + i + ')">' + i + '</a></li>';

    }
    document.getElementById('pagination').insertAdjacentHTML('beforeend', elementoHTML);
}

