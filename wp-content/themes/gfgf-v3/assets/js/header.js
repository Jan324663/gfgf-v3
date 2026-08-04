(function () {
    var button = document.querySelector('.site-header__menu');
    var navigation = document.getElementById('primary-navigation');

    if (!button || !navigation) {
        return;
    }

    button.addEventListener('click', function () {
        var isOpen = button.getAttribute('aria-expanded') === 'true';
        button.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
        navigation.classList.toggle('is-open', !isOpen);
    });
}());
