document.addEventListener('DOMContentLoaded', function() {
    let slideIndex = 0;
    let slides = document.getElementsByClassName("slideshow-slide");
    let timer = null;
    
    // Show the first slide immediately on page load
    if (slides.length > 0) {
        slides[0].classList.add("active-slide");
    }
    
    // Start the slideshow
    showSlides();

    function showSlides() {
        clearTimeout(timer); // Clear any existing timer
        
        // Remove active class from all slides
        for (let i = 0; i < slides.length; i++) {
            slides[i].classList.remove("active-slide");
        }
        
        // Increment slide index and reset if necessary
        slideIndex++;
        if (slideIndex > slides.length) {
            slideIndex = 1;
        }
        
        // Show the current slide
        if (slides.length > 0) {
            slides[slideIndex - 1].classList.add("active-slide");
        }
        
        // Change slide every 2 seconds
        timer = setTimeout(showSlides, 2000);
    }

    // Function to change slide when dot is clicked
    window.currentSlide = function(n) {
        clearTimeout(timer); // Stop the automatic slideshow when manually changing slides
        showSlideN(n);
        timer = setTimeout(showSlides, 4000); // Restart the timer with a longer delay after manual interaction
    }

    function showSlideN(n) {
        // Remove active class from all slides
        for (let i = 0; i < slides.length; i++) {
            slides[i].classList.remove("active-slide");
        }
        
        // Set slideIndex
        slideIndex = n;
        
        if (n > slides.length) {
            slideIndex = 1;
        }
        if (n < 1) {
            slideIndex = slides.length;
        }
        
        // Display the selected slide
        slides[slideIndex - 1].classList.add("active-slide");
    }
}); 