document.addEventListener('DOMContentLoaded', function() {
    let togglers = document.querySelectorAll('.js-burgerToggler');

    togglers.forEach(function(toggler) {
        toggler.addEventListener('click', function() {
            let target = toggler.dataset.target;
            let targetElement = document.querySelector('[data-id="' + target + '"]');

            targetElement.classList.toggle('--hidden');
        });
    });
});