const sidebar = document.getElementById("sidebar");
const toggleBtn = document.getElementById("toggle-btn");
const navLinks = document.querySelectorAll(".nav-link");

toggleBtn.addEventListener("click", () => {
    sidebar.classList.toggle("minimized");
});

// Active Link Highlighting for .nav-link only
navLinks.forEach(link => {
    link.addEventListener("click", () => {
        navLinks.forEach(nav => nav.classList.remove("active"));
        link.classList.add("active");
    });
});