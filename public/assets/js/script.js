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

document.addEventListener('DOMContentLoaded', () => {
    const stokInput = document.querySelector('#stok');
    const stokMinus = document.querySelector('[data-stok-minus]');
    const stokPlus = document.querySelector('[data-stok-plus]');

    if (stokInput instanceof HTMLInputElement) {
        const clampStok = () => {
            const min = Number(stokInput.min || 0);
            const max = Number(stokInput.max || 1000000);
            const value = Number(stokInput.value || 0);
            const safeValue = Number.isFinite(value) ? value : 0;

            stokInput.value = String(Math.min(max, Math.max(min, safeValue)));
        };

        stokMinus?.addEventListener('click', () => {
            stokInput.value = String(Number(stokInput.value || 0) - 1);
            clampStok();
        });

        stokPlus?.addEventListener('click', () => {
            stokInput.value = String(Number(stokInput.value || 0) + 1);
            clampStok();
        });

        stokInput.addEventListener('change', clampStok);
    }

    const deskripsi = document.querySelector('[data-char-count]');
    const charOutput = document.querySelector('[data-char-output]');

    if (deskripsi instanceof HTMLTextAreaElement && charOutput instanceof HTMLElement) {
        const updateCounter = () => {
            charOutput.textContent = String(deskripsi.value.length);
        };

        deskripsi.addEventListener('input', updateCounter);
        updateCounter();
    }

    document.querySelectorAll('form[data-submit-loading]').forEach((form) => {
        form.addEventListener('submit', () => {
            const submitButton = form.querySelector('button[type="submit"]');

            if (submitButton instanceof HTMLButtonElement) {
                submitButton.disabled = true;
                submitButton.textContent = 'Menyimpan...';
            }
        });
    });

    document.querySelectorAll('[data-auto-submit]').forEach((field) => {
        field.addEventListener('change', () => {
            const form = field.closest('form');

            if (!(form instanceof HTMLFormElement)) {
                return;
            }

            if (typeof form.requestSubmit === 'function') {
                form.requestSubmit();
            } else {
                form.submit();
            }
        });
    });
});
