function clearForm() {
    let currentUrl = new URL(window.location.href);

    let formFields = ["full-text-search", "eventType", "location", "date-filter"];
    formFields.forEach(function (field) {
        currentUrl.searchParams.delete(field);
    });

    window.location.href = currentUrl.href;
}