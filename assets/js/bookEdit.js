window.addEventListener('DOMContentLoaded', function() {

    // --- label change
    const label = document.querySelector('label[for="photo"]');
    const input = document.querySelector('input[type="file"]');

    input.addEventListener('change', function() {
        console.log('change');
        console.log(label);
        label.textContent = "Modifier la photo";
        label.classList.add('--modify');
    });

});