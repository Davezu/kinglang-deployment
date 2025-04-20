document.addEventListener('DOMContentLoaded', function() {
    const testimonialSlider = document.querySelector('.testimonial-slider');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');

    const scrollAmount = 320;

    if (prevBtn && nextBtn && testimonialSlider) {
        prevBtn.addEventListener('click', function() {
            testimonialSlider.scrollBy({
                left: -scrollAmount,
                behavior: 'smooth'
            });
        });

        nextBtn.addEventListener('click', function() {
            testimonialSlider.scrollBy({
                left: scrollAmount,
                behavior: 'smooth'
            });
        });
    }

    const mobileNavToggle = document.querySelector('.mobile-nav-toggle');
    const navigation = document.querySelector('.navigation');

    if (mobileNavToggle && navigation) {
        mobileNavToggle.addEventListener('click', function() {
            navigation.classList.toggle('active');
            mobileNavToggle.classList.toggle('active');
        });
    }

    // Header scroll behavior
    const header = document.querySelector('header');
    const scrollThreshold = 50; // Adjust this value as needed

    function toggleHeaderClass() {
        if (window.scrollY > scrollThreshold) {
            header.classList.add('header-scrolled');
        } else {
            header.classList.remove('header-scrolled');
        }
    }

    // Call on initial load and on scroll
    window.addEventListener('scroll', toggleHeaderClass);
    toggleHeaderClass();

    // Add animation to elements when they come into view
    const animateOnScroll = function() {
        const animatedElements = document.querySelectorAll('.animate-on-scroll');
        
        animatedElements.forEach(element => {
            const elementPosition = element.getBoundingClientRect().top;
            const screenPosition = window.innerHeight / 1.2;
            
            if (elementPosition < screenPosition) {
                element.classList.add('animated');
            }
        });
    };

    // Call on initial load and on scroll
    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll();
}); 