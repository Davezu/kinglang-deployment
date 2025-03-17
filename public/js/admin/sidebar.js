const sidebar = document.getElementById('sidebar');
const content = document.getElementById('content');
const toggleBtn = document.getElementById('toggleBtn');
const toggleIcon = toggleBtn.querySelector('i');

toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
    sidebar.classList.toggle('expanded');
    content.classList.toggle('collapsed');
    toggleIcon.classList.toggle('bi-chevron-left');
    toggleIcon.classList.toggle('bi-chevron-right');
});

function checkWidth() {
    if (window.innerWidth <= 768) {
        sidebar.classList.add('collapsed');
        sidebar.classList.remove('expanded');
        content.classList.add('collapsed');
    } else {
        // if (!sidebar.classList.contains('collapsed')) {
        //     sidebar.classList.add('expanded');
        //     sidebar.classList.remove('collapsed');
        //     content.classList.remove('collapsed');
        // }
    }
}

window.addEventListener('resize', checkWidth);
checkWidth();