document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-flash]').forEach((flash) => {
        const closeButton = flash.querySelector('[data-flash-close]');

        closeButton?.addEventListener('click', () => {
            flash.remove();
        });

        window.setTimeout(() => {
            flash.style.transition = 'opacity 300ms ease, transform 300ms ease';
            flash.style.opacity = '0';
            flash.style.transform = 'translateY(-4px)';

            window.setTimeout(() => flash.remove(), 300);
        }, 6000);
    });
});
