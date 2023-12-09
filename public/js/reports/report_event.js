function getEventReports(eventId){
    console.log(eventId);
    var selectedValue = document.getElementById("reportReason").value;
    fetch(`/admin/reports/load-reports/${eventId}`)
        .then(response => {
            if(!response.ok){
                throw Error(response.statusText)
            }
            return response.json();
        })
        .then(data => {
            console.log(data);
            document.getElementById('tableBody').innerHTML = '';

            data.forEach(report => {
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
            });
        })

        .catch(error => {   
            console.log(error);
        });
}

