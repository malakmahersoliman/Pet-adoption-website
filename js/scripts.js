/*!
 * Start Bootstrap - Creative (trimmed)
 * Theme behaviors; guards optional widgets per page.
 */
(function () {
  function onReady(fn) {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', fn);
    } else {
      fn();
    }
  }

  onReady(function () {
    var navbarCollapsible = document.querySelector('#mainNav');
    var navbarShrink = function () {
      if (!navbarCollapsible) return;
      if (window.scrollY === 0) {
        navbarCollapsible.classList.remove('navbar-shrink');
      } else {
        navbarCollapsible.classList.add('navbar-shrink');
      }
    };
    navbarShrink();
    document.addEventListener('scroll', navbarShrink);

    if (
      navbarCollapsible &&
      document.getElementById('about') &&
      typeof bootstrap !== 'undefined' &&
      bootstrap.ScrollSpy
    ) {
      new bootstrap.ScrollSpy(document.body, {
        target: '#mainNav',
        offset: 74,
      });
    }

    var navbarToggler = document.querySelector('.navbar-toggler');
    var responsiveNavItems = [].slice.call(
      document.querySelectorAll('#navbarResponsive .nav-link')
    );
    if (navbarToggler) {
      responsiveNavItems.forEach(function (responsiveNavItem) {
        responsiveNavItem.addEventListener('click', function () {
          if (window.getComputedStyle(navbarToggler).display !== 'none') {
            navbarToggler.click();
          }
        });
      });
    }

    if (typeof SimpleLightbox !== 'undefined' && document.querySelector('#portfolio a.portfolio-box')) {
      new SimpleLightbox({
        elements: '#portfolio a.portfolio-box',
      });
    }

    if (typeof Swiper !== 'undefined' && document.querySelector('.swiper-container')) {
      new Swiper('.swiper-container', {
        loop: true,
        spaceBetween: 10,
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
      });
    }
  });
})();
