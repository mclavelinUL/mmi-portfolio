(function () {
    'use strict';

    // Soumission automatique des filtres de l'archive pédagogique.
    var form = document.querySelector('.portfolio-pedagogique-archive #filtres');
    if (form) {
        form.querySelectorAll('select').forEach(function (select) {
            select.addEventListener('change', function () {
                form.submit();
            });
        });
    }

    // Carrousels de traces.
    if (typeof Swiper === 'undefined') {
        return;
    }

    document.querySelectorAll('.swiper').forEach(function (el) {
        var count = parseInt(el.dataset.slides, 10) || el.querySelectorAll('.swiper-slide').length;

        new Swiper(el, {
            spaceBetween: 10,
            // Le mode loop casse l'affichage quand il y a moins de slides que de colonnes.
            loop: count > 3,
            slidesPerView: 1,
            breakpoints: {
                640: { slidesPerView: Math.min(2, count) },
                1024: { slidesPerView: Math.min(3, count) }
            },
            navigation: {
                nextEl: el.querySelector('.swiper-button-next'),
                prevEl: el.querySelector('.swiper-button-prev')
            },
            pagination: {
                el: el.querySelector('.swiper-pagination'),
                clickable: true
            }
        });
    });
})();
