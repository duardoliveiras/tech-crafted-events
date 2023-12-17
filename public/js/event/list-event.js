function clearForm() {
    let currentUrl = new URL(window.location.href);

    let formFields = ["full-text-search", "event-type", "location", "date-filter"];
    formFields.forEach(function (field) {
        currentUrl.searchParams.delete(field);
    });

    window.location.href = currentUrl.href;
}

document.querySelectorAll('.dropdown-item').forEach(item => {
    item.addEventListener('click', function() {
        // Set the sort option in a hidden form field
        document.getElementById('sort').value = this.getAttribute('data-sort');

        console.log(this.getAttribute('data-sort'));

        // Submit the form
        document.getElementById('filter-form').submit();
    });
});
