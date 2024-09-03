window.addEventListener('DOMContentLoaded', function() {

    // --- popup toggle
    const togglers = document.querySelectorAll('[data-popup-toggler]');
    let popups = document.querySelectorAll('[data-popup]');

    togglers.forEach(toggler => {
        popups = Array.from(popups);
        const popup = popups.find(popup => popup.dataset.popup === toggler.dataset.popupToggler);

        console.log(popups);
        console.log(togglers);

        if(!popup) {
            return;
        }

        new Popup([toggler], popup);
    });
});

class Popup {
    togglers
    popup
    closers

    constructor(togglers, popup) {
        this.togglers = togglers;
        this.popup = popup;

        this.togglers.forEach(toggler => {
            toggler.addEventListener('click', this.togglePopup.bind(this));
        });

        this.popup.addEventListener('click', this.closePopup.bind(this));
    }

    togglePopup(e) {
        e.preventDefault();
        this.popup.classList.toggle('--hidden');
    }

    closePopup(e) {
        if (e.target === this.popup) {
            this.popup.classList.add('--hidden');
        }
    }
}