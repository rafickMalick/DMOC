import Alpine from 'alpinejs';

window.Alpine = Alpine;

window.showToast = (type, message) => {
    window.dispatchEvent(new CustomEvent('dmoc-toast', { detail: { type, message } }));
};

Alpine.start();
