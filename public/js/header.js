// // header.js
// document.addEventListener('DOMContentLoaded', () => {
//     const mobileMenuBtn = document.querySelector('.mobile-menu-toggle');
//     const navMenu = document.querySelector('.nav-menu');

//     if (!mobileMenuBtn || !navMenu) {
//         console.error('Mobile menu elements not found');
//         return;
//     }

//     mobileMenuBtn.addEventListener('click', () => {
//         // Toggle zowel menu als button state
//         navMenu.classList.toggle('active');
//         mobileMenuBtn.classList.toggle('active');

//         // Update aria-expanded voor toegankelijkheid
//         const isExpanded = navMenu.classList.contains('active');
//         mobileMenuBtn.setAttribute('aria-expanded', isExpanded);

//         // Voorkom scrolling van de pagina als menu open is
//         document.body.style.overflow = isExpanded ? 'hidden' : '';
//     });

//     // Sluit menu bij klik buiten menu
//     document.addEventListener('click', (e) => {
//         if (!navMenu.contains(e.target) && 
//             !mobileMenuBtn.contains(e.target) && 
//             navMenu.classList.contains('active')) {
//             navMenu.classList.remove('active');
//             mobileMenuBtn.classList.remove('active');
//             mobileMenuBtn.setAttribute('aria-expanded', 'false');
//             document.body.style.overflow = '';
//         }
//     });
// });









// document.addEventListener('DOMContentLoaded', function () {
//     // Hamburgermenu toggle functionaliteit
//     const toggler = document.querySelector(".navbar-toggler");
//     const navbarCollapse = document.querySelector(".navbar-collapse");

//     if (toggler && navbarCollapse) {
//         toggler.addEventListener("click", function () {
//             navbarCollapse.classList.toggle("open");
//             toggler.classList.toggle("open");
//         });
//     }

//     // Dropdown toggle functionaliteit
//     const categoryLinks = document.querySelectorAll('.nav-item > .nav-link');

//     categoryLinks.forEach(link => {
//         link.addEventListener('click', function (e) {
//             e.preventDefault(); // Voorkom standaardgedrag van de link
//             const subMenu = this.nextElementSibling; // Krijg het bijbehorende submenu
//             if (subMenu && subMenu.classList.contains('dropdown-menu')) {
//                 subMenu.classList.toggle('show'); // Toggle zichtbaar maken
//             }
//         });
//     });

//     // Sluiten van dropdowns bij klikken buiten het menu
//     document.addEventListener('click', function (e) {
//         // Controleer of de klik niet binnen een nav-item of submenu is
//         if (!e.target.closest('.navbar-nav')) {
//             // Alle geopende dropdown-menu's sluiten
//             document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
//                 menu.classList.remove('show');
//             });
//         }
//     });
// });