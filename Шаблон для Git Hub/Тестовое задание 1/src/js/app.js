document.addEventListener('DOMContentLoaded', function () {
    let isScrolling = false;
    const header = document.querySelector('.header__top-section');
    if (!header) {
        console.error('Header element not found!');
        return;
    }
    window.addEventListener('scroll', function () {
        if (!isScrolling) {
            window.requestAnimationFrame(function () {
                if (window.scrollY > 10) {
                    header.classList.add('nav__scrolled');
                } else {
                    header.classList.remove('nav__scrolled');
                }
                isScrolling = false;
            });
            isScrolling = true;
        }
    });
});