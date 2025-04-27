document.querySelectorAll('.dropdown > a').forEach(dropdownLink => {
    dropdownLink.addEventListener('click', (e) => {
        e.preventDefault();
        const dropdownMenu = dropdownLink.nextElementSibling;

        // Toggle dropdown
        dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
    });
});

let slideIndex = 0;
const slides = document.querySelectorAll(".slide-container img");

function showSlides() {
    slides.forEach(slide => (slide.style.display = "none")); // Hide all
    slideIndex = (slideIndex + 1 > slides.length) ? 1 : slideIndex + 1; // Loop
    slides[slideIndex - 1].style.display = "block"; // Show current
    setTimeout(showSlides, 3000); // Auto-change
}

showSlides();
