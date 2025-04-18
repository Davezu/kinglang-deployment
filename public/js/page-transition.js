document.addEventListener('DOMContentLoaded', function() {
    // Store the current page type (login or signup)
    const currentPage = window.location.pathname.includes('signup') ? 'signup' : 'login';
    const contentDiv = document.querySelector('.content');
    
    // Add initial animation class on page load
    if (!sessionStorage.getItem('animated')) {
        contentDiv.classList.add('animate-in');
        // Store flag in session storage to avoid re-animating on back/forward navigation
        sessionStorage.setItem('animated', 'true');
        
        // Remove animation class after it completes to avoid interference with other animations
        setTimeout(function() {
            contentDiv.classList.remove('animate-in');
        }, 800);
    }
    
    // Get navigation links
    const loginLinks = document.querySelectorAll('a[href="/home/login"], a[href*="login"]');
    const signupLinks = document.querySelectorAll('a[href="/home/signup"], a[href*="signup"]');
    
    // Add event listeners to login links when on signup page
    if (currentPage === 'signup') {
        loginLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                contentDiv.classList.add('signup-to-login');
                
                // Clear the animation flag for the next page load
                sessionStorage.removeItem('animated');
                
                // Redirect after animation completes
                setTimeout(function() {
                    window.location.href = link.getAttribute('href');
                }, 700);
            });
        });
    }
    
    // Add event listeners to signup links when on login page
    if (currentPage === 'login') {
        signupLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                contentDiv.classList.add('login-to-signup');
                
                // Clear the animation flag for the next page load
                sessionStorage.removeItem('animated');
                
                // Redirect after animation completes
                setTimeout(function() {
                    window.location.href = link.getAttribute('href');
                }, 700);
            });
        });
    }
}); 