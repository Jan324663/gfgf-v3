(function () {
    var button = document.querySelector('.site-header__menu');
    var navigation = document.getElementById('primary-navigation');
    var mobileQuery = window.matchMedia('(max-width: 720px)');

    if (!button || !navigation) {
        return;
    }

    function setMenuState(isOpen, returnFocus) {
        button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        button.setAttribute(
            'aria-label',
            isOpen ? button.dataset.closeLabel : button.dataset.openLabel
        );
        navigation.classList.toggle('is-open', isOpen);

        if (returnFocus) {
            button.focus();
        }
    }

    button.addEventListener('click', function () {
        setMenuState(button.getAttribute('aria-expanded') !== 'true', false);
    });

    navigation.addEventListener('click', function (event) {
        if (mobileQuery.matches && event.target.closest('a')) {
            setMenuState(false, false);
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && button.getAttribute('aria-expanded') === 'true') {
            setMenuState(false, true);
        }
    });

    mobileQuery.addEventListener('change', function () {
        setMenuState(false, false);
    });
}());
