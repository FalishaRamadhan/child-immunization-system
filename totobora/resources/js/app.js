document.addEventListener('DOMContentLoaded', function () {
    const flash = document.querySelectorAll('[data-flash]');
    flash.forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        }, 4000);
    });
});
