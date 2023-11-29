document.addEventListener("DOMContentLoaded", function () {
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = "scale(1.05)";
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = "scale(1)";
        });
    });
});