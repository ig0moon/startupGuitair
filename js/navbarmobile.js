document.addEventListener('DOMContentLoaded', () => {
    const menuBtn = document.createElement('button');
    menuBtn.classList.add('hamburger-btn');
    menuBtn.setAttribute('aria-label', 'Abrir menu');
    menuBtn.innerHTML = `
        <span></span>
        <span></span>
        <span></span>
    `;
    document.querySelector('.nav-mobile-menu').appendChild(menuBtn);

    const navMobile = document.querySelector('.navbar-mobile');
    navMobile.style.display = 'none';

    menuBtn.addEventListener('click', () => {
        const isOpen = navMobile.style.display === 'flex';
        navMobile.style.display = isOpen ? 'none' : 'flex';
        menuBtn.classList.toggle('active', !isOpen);
    });

    document.addEventListener('click', (e) => {
        if (!navMobile.contains(e.target) && !menuBtn.contains(e.target)) {
            navMobile.style.display = 'none';
            menuBtn.classList.remove('active');
        }
    });
});