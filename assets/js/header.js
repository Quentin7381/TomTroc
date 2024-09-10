function activeLink(url) {
    const header = document.querySelector('.tpl-header');
    const links = header.querySelectorAll('a');

    links.forEach(link => {
        if (link.getAttribute('href') === url) {
            link.classList.add('bold');
        }
    });
}