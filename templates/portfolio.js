/**
 * MMI Portfolio — interactions front.
 *
 * Aucune dépendance hors Swiper (chargé uniquement sur la fiche détail).
 */
(function () {
    'use strict';

    /**
     * Filtres de l'archive : soumission au changement.
     * Le bouton « Filtrer » reste le chemin sans JavaScript ; on le masque
     * seulement une fois la soumission automatique en place.
     */
    function initFilters() {
        var form = document.getElementById('mmi-filtres');
        if (!form) {
            return;
        }

        var submit = form.querySelector('[data-mmi-submit]');
        if (submit) {
            submit.hidden = true;
        }

        form.querySelectorAll('select').forEach(function (select) {
            select.addEventListener('change', function () {
                form.submit();
            });
        });
    }

    /**
     * Sommaire des apprentissages critiques : met en évidence la section lue.
     */
    function initTableOfContents() {
        var links = document.querySelectorAll('.mmi-toc__link');
        if (!links.length || !('IntersectionObserver' in window)) {
            return;
        }

        var byId = {};
        var targets = [];

        links.forEach(function (link) {
            var id = decodeURIComponent(link.hash.slice(1));
            var target = id ? document.getElementById(id) : null;
            if (!target) {
                return;
            }
            byId[id] = link;
            targets.push(target);
        });

        if (!targets.length) {
            return;
        }

        var current = null;

        var observer = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) {
                        return;
                    }
                    var link = byId[entry.target.id];
                    if (!link || link === current) {
                        return;
                    }
                    if (current) {
                        current.classList.remove('is-current');
                    }
                    link.classList.add('is-current');
                    current = link;
                });
            },
            // Fenêtre étroite dans le tiers haut : une seule section active
            // à la fois, même quand elles sont courtes.
            { rootMargin: '-10% 0px -70% 0px', threshold: 0 }
        );

        targets.forEach(function (target) {
            observer.observe(target);
        });

    }

    /**
     * Le sommaire est un <details> repliable sur mobile, mais doit rester
     * ouvert en colonne collante sur grand écran, même si l'utilisateur
     * l'avait replié avant d'élargir la fenêtre.
     */
    function initTocDisclosure() {
        var toc = document.querySelector('.mmi-toc');
        if (!toc) {
            return;
        }

        var wide = window.matchMedia('(min-width: 64rem)');

        var sync = function () {
            if (wide.matches) {
                toc.open = true;
            }
        };

        sync();
        wide.addEventListener('change', sync);
    }

    /**
     * Carrousels de traces.
     */
    function initGalleries() {
        if (typeof window.Swiper === 'undefined') {
            return;
        }

        document.querySelectorAll('.mmi-gallery').forEach(function (el) {
            var count = parseInt(el.dataset.slides, 10) || el.querySelectorAll('.swiper-slide').length;

            new window.Swiper(el, {
                spaceBetween: 12,
                // loop casse l'affichage quand il y a moins de slides que de
                // colonnes : on ne l'active qu'au-delà.
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
                },
                a11y: {
                    prevSlideMessage: 'Image précédente',
                    nextSlideMessage: 'Image suivante'
                }
            });
        });
    }

    function init() {
        initFilters();
        initTableOfContents();
        initTocDisclosure();
        initGalleries();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
