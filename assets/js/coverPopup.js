window.addEventListener('DOMContentLoaded', function() {

    // --- popup toggle
    const togglers = document.querySelectorAll('[data-toggle="coverPopup"]');
    let coverPopup = document.querySelector('.tpl-coverPopup');

    togglers.forEach(function(toggler) {
        toggler.addEventListener('click', function(e) {
            e.preventDefault();
            coverPopup.classList.toggle('--hidden');
        });
    });

    // --- image preview
    const fileInput = document.getElementById('file-upload');
    let preview = document.querySelector('.tpl-coverPopup__preview');

    fileInput.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                preview.src = e.target.result;
                preview.classList.remove('--hidden');
            };
            reader.readAsDataURL(file);
        }
    });
});

