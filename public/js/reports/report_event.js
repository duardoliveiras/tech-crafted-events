var rowsPerPag = 5;


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
            document.getElementById('tableBody').innerHTML = '';
            pagination(data);
            
            if(data.length > 0){
                

                var curr_pag = document.getElementById('pg'+page);
                curr_pag.classList.add('disabled');   
       
                var i = (page-1)*5;
                var len =  i+rowsPerPag;

                var len = ((data.length-i) < 5) ? data.length : i+rowsPerPag;
       
                for(i; i < len; i++){
                    var report = data[i];
                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <td>${report.user.name}</td>
                        <td>${report.reason}</td>
                        <td>${report.description}</td>
                        <td>
                            <button type="button" class="btn btn-success" onclick="checkOneReport('${report.id}')">Check</button>
                        </td>
                    `;
                    document.getElementById('tableBody').appendChild(newRow);
            }

            }
        })

        .catch(error => {   
            console.log(error);
        });
}

function pagination(data){
    var keys = Object.keys(data);
    var qt = keys.length;
    var pags = Math.ceil(qt/rowsPerPag);
    var elementoHTML = '';

    document.getElementById('pagination').innerHTML = '';
    for(var i = 1; i <= pags; i++){
        elementoHTML += '<li class="page-item" id="pg' + i + '"><a class="page-link" href="javascript:void(0);" onclick="getEventReports(\'' + eventId + '\', ' + i + ')">' + i + '</a></li>';

    }
    document.getElementById('pagination').insertAdjacentHTML('beforeend', elementoHTML);
}

function checkOneReport(id){

    var url = '/admin/reports/check/' + id;
    console.log(url);
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
            if(!response.ok){
                throw Error(response.statusText);
            }
            console.log('check!');

        })
        .catch(error =>{
            console.error('Erro', error);
        });
}   
