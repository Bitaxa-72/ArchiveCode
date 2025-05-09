    // программа        

    const tabControls = document.querySelector('.tab')

    tabControls.addEventListener('click', toggleTab)

    function toggleTab(e) {

        const tabControl = e.target.closest('.tab__link')

        if (!tabControl) return
        e.preventDefault()
        if (tabControl.classList.contains('tab__conrol--active')) return


        const tabContentId = tabControl.getAttribute('href')

        document.querySelector('.prog__tab-content--show').classList.remove('prog__tab-content--show')

        document.querySelector(tabContentId).classList.add('prog__tab-content--show')

        document.querySelector('.tab__conrol--active').classList.remove('tab__conrol--active')

        tabControl.classList.add('tab__conrol--active')
    }
    // аккордион

    const accordionLists = document.querySelectorAll('.accordion-list');

    accordionLists.forEach(el => {

        el.addEventListener('click', (e) => {

            const accordionList = e.currentTarget
            const accordionOpenedItem = accordionList.querySelector('.accordion-list__item--opened')
            const accordionOpenedContent = accordionList.querySelector('.accordion-list__item--opened .accordion-list__content')

            const accordionControl = e.target.closest('.accordion-list__control');
            if (!accordionControl) return
            const accordionItem = accordionControl.parentElement;
            const accordionContent = accordionControl.nextElementSibling;

            if (accordionOpenedItem && accordionItem != accordionOpenedItem) {
                accordionOpenedItem.classList.remove('accordion-list__item--opened');
                accordionOpenedContent.style.maxHeight = null;
            }
            accordionItem.classList.toggle('accordion-list__item--opened');

            if (accordionItem.classList.contains('accordion-list__item--opened')) {
                accordionContent.style.maxHeight = accordionContent.scrollHeight + 'px';
            } else {
                accordionContent.style.maxHeight = null;
            }

        });

    });