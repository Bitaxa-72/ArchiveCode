document.addEventListener('DOMContentLoaded', function () {
    const languageDropdown = document.getElementById('languageDropdown');
    const languageList = document.getElementById('languageList');
    const languageIcon = document.getElementById('languageIcon');
    const currentLangElement = document.querySelector('[data-translate="currentLang"]');


    languageDropdown.addEventListener('click', function (event) {
        event.preventDefault();
        languageList.style.display = languageList.style.display === 'block' ? 'none' : 'block';
    });


    languageList.querySelectorAll('a').forEach(function (langLink) {
        langLink.addEventListener('click', function (event) {
            event.preventDefault();
            const selectedLang = this.getAttribute('data-lang');
            changeLanguage(selectedLang);
            languageList.style.display = 'none';
        });
    });


    function changeLanguage(lang) {

        if (lang === 'RU') {
            languageIcon.src = 'src/img/icons/languagemask.svg';
            currentLangElement.textContent = 'RU';
        } else if (lang === 'ENG') {
            languageIcon.src = 'src/img/icons/en-lang.svg';
            currentLangElement.textContent = 'ENG';
        }
        const nameInput = document.querySelector('.application__name');
        if (nameInput) {
            nameInput.placeholder = translations[lang].placeholderName;
        }


        document.querySelectorAll('[data-translate]').forEach(function (element) {
            const key = element.getAttribute('data-translate');
            element.textContent = translations[lang][key];
        });
    }

    const translations = {
        RU: {
            tr1: 'RU',
        
        },
        ENG: {
            tr1: 'ENG',
            
        }
    };
});

