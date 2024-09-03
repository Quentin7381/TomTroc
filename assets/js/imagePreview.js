window.addEventListener('DOMContentLoaded', function() {

    // --- image preview
    const imageInputs = document.querySelectorAll('[data-image-preview-input]');
    const imagePreviews = document.querySelectorAll('[data-image-preview]');

    imageInputs.forEach(input => {
        let previews = Array.from(imagePreviews);
        const preview = previews.find(preview => preview.dataset.imagePreview === input.dataset.imagePreviewInput);

        console.log(input, preview);

        if(!preview || !input) {
            return;
        }

        new ImagePreview(input, preview);
    });
});

class ImagePreview {
    input
    preview

    constructor(input, preview) {
        this.input = input;
        this.preview = preview;

        this.input.addEventListener('change', this.previewImage.bind(this));
    }

    previewImage(e) {
        const file = e.target.files[0];
        const reader = new FileReader();

        reader.onload = (e) => {
            this.preview.src = e.target.result;
            this.preview.classList.remove('--hidden');
        };

        reader.readAsDataURL(file);
    }
}