function clearForm() {
    let currentUrl = new URL(window.location.href);

    let formFields = ["full-text-search", "event-type", "location", "date-filter", "university"];
    formFields.forEach(function (field) {
        currentUrl.searchParams.delete(field);
    });

    window.location.href = currentUrl.href;
}

document.querySelectorAll('.dropdown-sort').forEach(item => {
    item.addEventListener('click', function () {
        // Set the sort option in a hidden form field
        document.getElementById('sort').value = this.getAttribute('data-sort');

        // Submit the form
        document.getElementById('filter-form').submit();
    });
});

document.addEventListener('DOMContentLoaded', function () {
    let page = 1; // Track the current page

    function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        let regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        let results = regex.exec(window.location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    }

    function loadMoreData(page, sortType, eventType, location, dateFilter, nameFilter, universityFilter) {
        fetch(`?page=${page}&ajax=true&sort=${sortType}&event-type=${eventType}&location=${location}&date-filter=${dateFilter}&full-text-search=${nameFilter}&university=${universityFilter}`, {
            method: 'GET'
        })
            .then(response => response.json())
            .then(data => {
                if (data.html === "") {
                    document.getElementById('loadMore').textContent = 'No more records';
                    document.getElementById('loadMore').style.display = 'none';
                    return;
                }
                document.getElementById('loadMore').textContent = 'Load More';
                let anchor = document.getElementById('anchor');
                anchor.insertAdjacentHTML('beforebegin', data.html);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('server not responding...');
            });
    }

    document.getElementById('loadMore').addEventListener('click', function () {
        page++;
        let sortType = getUrlParameter('sort');
        let eventType = getUrlParameter('event-type');
        let location = getUrlParameter('location');
        let dateFilter = getUrlParameter('date-filter');
        let nameFilter = getUrlParameter('full-text-search');
        let universityFilter = getUrlParameter('university');

        loadMoreData(page, sortType, eventType, location, dateFilter, nameFilter, universityFilter);
    });

});