document.addEventListener('DOMContentLoaded', () => {
    const themeToggleInput = document.getElementById('theme-toggle');
    const darkModeClass = 'bg-dark-mode';

    if (localStorage.getItem('dark-mode') === 'enabled') {
        document.body.classList.add(darkModeClass);
        document.querySelector('.navbar').classList.add('bg-dark-mode');
        document.querySelector('.footer').classList.add('bg-dark-mode');
        themeToggleInput.checked = true;
    }

    themeToggleInput.addEventListener('change', () => {
        document.body.classList.toggle(darkModeClass);
        document.querySelector('.navbar').classList.toggle('bg-dark-mode');
        document.querySelector('.footer').classList.toggle('bg-dark-mode');

        if (document.body.classList.contains(darkModeClass)) {
            localStorage.setItem('dark-mode', 'enabled');
        } else {
            localStorage.setItem('dark-mode', 'disabled');
        }
    });

    document.querySelectorAll('.fade-in').forEach(element => {
        element.classList.add('visible');
    });
});