/*!
* Start Bootstrap - Creative v7.0.6 (https://startbootstrap.com/theme/creative)
* Copyright 2013-2022 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-creative/blob/master/LICENSE)
*/
//
// Scripts
// 

window.addEventListener('DOMContentLoaded', event => {

    // Navbar shrink function
    var navbarShrink = function () {
        const navbarCollapsible = document.body.querySelector('#mainNav');
        if (!navbarCollapsible) {
            return;
        }
        if (window.scrollY === 0) {
            navbarCollapsible.classList.remove('navbar-shrink')
        } else {
            navbarCollapsible.classList.add('navbar-shrink')
        }

    };

    // Shrink the navbar 
    navbarShrink();

    // Shrink the navbar when page is scrolled
    document.addEventListener('scroll', navbarShrink);

    // Activate Bootstrap scrollspy on the main nav element
    const mainNav = document.body.querySelector('#mainNav');
    if (mainNav) {
        new bootstrap.ScrollSpy(document.body, {
            target: '#mainNav',
            offset: 74,
        });
    };

    // Collapse responsive navbar when toggler is visible
    const navbarToggler = document.body.querySelector('.navbar-toggler');
    const responsiveNavItems = [].slice.call(
        document.querySelectorAll('#navbarResponsive .nav-link')
    );
    responsiveNavItems.map(function (responsiveNavItem) {
        responsiveNavItem.addEventListener('click', () => {
            if (window.getComputedStyle(navbarToggler).display !== 'none') {
                navbarToggler.click();
            }
        });
    });

    // Activate SimpleLightbox plugin for portfolio items
    new SimpleLightbox({
        elements: '#portfolio a.portfolio-box'
    });

});

var swiper = new Swiper('.swiper-container', {
    loop: true, // Enables continuous swiping
    spaceBetween: 10, // Space between slides
    pagination: {
        el: '.swiper-pagination', // Enables pagination dots
        clickable: true, // Allows users to click the dots
    },
    navigation: {
        nextEl: '.swiper-button-next', // Specifies next button
        prevEl: '.swiper-button-prev', // Specifies previous button
    },
});
function filterPets(type) {
    var pets = document.getElementsByClassName('pet');
    for (var i = 0; i < pets.length; i++) {
        var pet = pets[i];
        if (type === 'diff') {
            if (pet.classList.contains('dogs') || pet.classList.contains('cats')) {
                pet.style.display = 'none';
            } else {
                pet.style.display = 'block';
            }
        } else if (pet.classList.contains(type)) {
            pet.style.display = 'block';
        } else {
            pet.style.display = 'none';
        }
    }
}
document.querySelector('[data-toggle="dropdown"]').addEventListener('click', function (event) {
    const type = event.target.getAttribute('data-type'); 
    filterPets(type); 
  });
  
const userName = localStorage.getItem('loggedInUser');

if (userName) {
    // If a user is logged in, hide the Login button and display the user's name
    document.getElementById('loginNavItem').style.display = 'none';
    document.getElementById('userNavItem').style.display = 'block';
    document.getElementById('userName').textContent = userName;
}
document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const username = document.getElementById('username').value;

    // Save the username in local storage (for this example)
    localStorage.setItem('loggedInUser', username);

    // Redirect to the main page
    window.location.href = 'index.html';
});