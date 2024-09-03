window.addEventListener('DOMContentLoaded', function() {

    const books = document.querySelectorAll('article');
    const search = document.querySelector('input[type="search"]');

    search.addEventListener('keyup', function() {
        const query = search.value.toLowerCase();
        const words = query.split(' ');

        books.forEach(book => {
            let title = book.querySelector('.tpl-entity-book--card__title').textContent.toLowerCase();
            let author = book.querySelector('.tpl-entity-book--card__author').textContent.toLowerCase();
            let seller = book.querySelector('.tpl-entity-book--card__seller').textContent.toLowerCase();

            seller = seller.replace('vendu par ', '');

            let match = true;

            words.forEach(word => {
                if (
                    !title.includes(word) &&
                    !author.includes(word) &&
                    !seller.includes(word)
                ) {
                    match = false;
                }
            });

            if (match) {
                book.classList.remove('--hidden');
            } else {
                book.classList.add('--hidden');
            }
        });
    });

});