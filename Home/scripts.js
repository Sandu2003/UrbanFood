document.querySelectorAll('.dropdown > a').forEach(dropdownLink => {
    dropdownLink.addEventListener('click', (e) => {
        e.preventDefault();
        const dropdownMenu = dropdownLink.nextElementSibling;

        // Toggle dropdown visibility
        if (dropdownMenu.style.display === 'block') {
            dropdownMenu.style.display = 'none';
        } else {
            dropdownMenu.style.display = 'block';
        }
    });
});
let slideIndex = 0;
const slides = document.querySelectorAll(".slide-container img");

function showSlides() {
    slides.forEach(slide => (slide.style.display = "none")); // Hide all images
    slideIndex++;
    if (slideIndex > slides.length) slideIndex = 1; // Loop back to the first image
    slides[slideIndex - 1].style.display = "block"; // Show the current image
    setTimeout(showSlides, 3000); // Change image every 3 seconds
}

showSlides();
