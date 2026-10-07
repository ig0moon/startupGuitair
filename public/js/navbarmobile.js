function initMobileMenu() {
    const navMobileMenuContainer = document.querySelector('.nav-mobile-menu');
    const navMobile = document.querySelector('.navbar-mobile');
    if (!navMobileMenuContainer || !navMobile) return;

    if (navMobileMenuContainer.querySelector('.hamburger-btn')) return;

    const menuBtn = document.createElement('button');
    menuBtn.classList.add('hamburger-btn');
    menuBtn.setAttribute('aria-label', 'Abrir menu');
    menuBtn.setAttribute('type', 'button');
    menuBtn.innerHTML = `
        <span></span>
        <span></span>
        <span></span>
    `;
    navMobileMenuContainer.appendChild(menuBtn);

    navMobile.style.display = 'none';

    menuBtn.addEventListener('click', (e) => {
        e.stopPropagation();
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
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMobileMenu);
} else {
    initMobileMenu();
}