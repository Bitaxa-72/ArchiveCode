document.addEventListener('click', burgerInit)
function burgerInit(e) {
    const burgerIcon = e.target.closest('.burger-icon, .esc__burger')
    const burgerNavLink = e.target.closest('.nav__link')
    if (!burgerIcon && !burgerNavLink) return
    if (document.documentElement.clientWidth > 1500) return
    if (burgerIcon) {
        e.preventDefault()
    }
    if (!document.body.classList.contains('body--opened-menu')) {
        document.body.classList.add('body--opened-menu')
    } else {
        document.body.classList.remove('body--opened-menu')
    }
}

