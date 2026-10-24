/**
 * GUITAIR - Interactive App Scripts
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Sticky Header Scroll Effect
    const header = document.querySelector('header');
    if (header) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }

    // 2. Scroll To Top Button
    const scrollBtn = document.querySelector('.scrlbtn');
    if (scrollBtn) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                scrollBtn.classList.add('show');
            } else {
                scrollBtn.classList.remove('show');
            }
        });

        scrollBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // 3. Mobile Navigation Menu
    const menuBtn = document.querySelector('.hamburger-btn');
    const mobileNav = document.querySelector('.navbar-mobile');

    if (menuBtn && mobileNav) {
        menuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = mobileNav.style.display === 'flex';
            mobileNav.style.display = isOpen ? 'none' : 'flex';
            menuBtn.classList.toggle('active', !isOpen);
        });

        document.addEventListener('click', (e) => {
            if (!mobileNav.contains(e.target) && !menuBtn.contains(e.target)) {
                mobileNav.style.display = 'none';
                menuBtn.classList.remove('active');
            }
        });
    }

    // 4. FAQ Accordion
    const faqQuestions = document.querySelectorAll('.faq-question');
    faqQuestions.forEach(btn => {
        btn.addEventListener('click', () => {
            const parentItem = btn.closest('.faq-item');
            const isActive = parentItem.classList.contains('active');
            
            // Close other items
            document.querySelectorAll('.faq-item').forEach(item => {
                item.classList.remove('active');
            });

            if (!isActive) {
                parentItem.classList.add('active');
            }
        });
    });

    // 5. FAQ Live Search
    const faqSearch = document.getElementById('faqSearchInput');
    if (faqSearch) {
        faqSearch.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase().trim();
            const faqItems = document.querySelectorAll('.faq-item');

            faqItems.forEach(item => {
                const questionText = item.querySelector('.faq-question').textContent.toLowerCase();
                const answerText = item.querySelector('.faq-answer').textContent.toLowerCase();

                if (questionText.includes(query) || answerText.includes(query)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    // 6. Auth Tabs (Login / Register)
    const authTabButtons = document.querySelectorAll('.auth-tab-btn');
    const authForms = document.querySelectorAll('.auth-form-panel');

    authTabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-target');
            
            authTabButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            authForms.forEach(panel => {
                if (panel.id === targetId) {
                    panel.style.display = 'block';
                } else {
                    panel.style.display = 'none';
                }
            });
        });
    });

    // 7. Demo Fill Button for Account
    const fillDemoBtn = document.getElementById('fillDemoBtn');
    if (fillDemoBtn) {
        fillDemoBtn.addEventListener('click', () => {
            const emailInput = document.getElementById('loginEmail');
            const passInput = document.getElementById('loginPassword');
            if (emailInput && passInput) {
                emailInput.value = 'demo@guitair.com.br';
                passInput.value = '123456';
                showToast('Credenciais de demonstração preenchidas!', 'info');
            }
        });
    }

    // 8. Password Toggle
    document.querySelectorAll('.toggle-password-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = btn.parentElement.querySelector('input');
            if (input) {
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                btn.textContent = isPassword ? 'visibility_off' : 'visibility';
            }
        });
    });

    // 9. Custom Div Dropdowns (Admin Orders & Form controls)
    const customDropdowns = document.querySelectorAll('.custom-div-dropdown');
    customDropdowns.forEach(dropdown => {
        const trigger = dropdown.querySelector('.custom-div-trigger');
        const menu = dropdown.querySelector('.custom-div-menu');
        const hiddenInput = dropdown.closest('form')?.querySelector('.dropdown-hidden-status') || dropdown.querySelector('.dropdown-hidden-status');
        const labelSpan = dropdown.querySelector('.trigger-label');
        const dotSpan = dropdown.querySelector('.trigger-dot');
        const options = dropdown.querySelectorAll('.custom-div-option');
        const saveBtn = dropdown.closest('form')?.querySelector('.btn-status-save');

        if (!trigger || !menu) return;

        // Toggle dropdown open
        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = dropdown.classList.contains('open');

            // Close all other dropdowns
            customDropdowns.forEach(d => {
                if (d !== dropdown) {
                    d.classList.remove('open');
                }
            });

            if (!isOpen) {
                dropdown.classList.add('open');
                const rect = trigger.getBoundingClientRect();
                menu.style.position = 'fixed';
                menu.style.left = `${rect.left}px`;
                menu.style.minWidth = `${Math.max(rect.width, 140)}px`;

                const menuHeight = 185;
                const spaceBelow = window.innerHeight - rect.bottom;

                if (spaceBelow < menuHeight + 10 && rect.top > menuHeight + 10) {
                    menu.style.top = `${rect.top - menuHeight - 4}px`;
                } else {
                    menu.style.top = `${rect.bottom + 4}px`;
                }
            } else {
                dropdown.classList.remove('open');
            }
        });

        // Trigger keyboard support
        trigger.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                trigger.click();
            } else if (e.key === 'Escape') {
                dropdown.classList.remove('open', 'open-up');
            }
        });

        // Option selection
        options.forEach(option => {
            option.addEventListener('click', (e) => {
                e.stopPropagation();
                const val = option.getAttribute('data-val');
                const label = option.getAttribute('data-label') || option.textContent.trim();

                if (hiddenInput) {
                    hiddenInput.value = val;
                }
                if (labelSpan) {
                    labelSpan.textContent = label;
                }
                if (dotSpan) {
                    dotSpan.className = 'trigger-dot dot-' + val;
                }

                options.forEach(opt => opt.classList.remove('selected'));
                option.classList.add('selected');

                dropdown.classList.remove('open', 'open-up');

                // Visual highlight feedback on save button
                if (saveBtn) {
                    saveBtn.style.transform = 'scale(1.06)';
                    saveBtn.style.boxShadow = '0 0 18px rgba(6, 182, 212, 0.7)';
                    setTimeout(() => {
                        saveBtn.style.transform = '';
                        saveBtn.style.boxShadow = '';
                    }, 400);
                }
            });
        });
    });

    // Close dropdowns on outside click
    document.addEventListener('click', () => {
        customDropdowns.forEach(d => d.classList.remove('open', 'open-up'));
    });

    // Close dropdowns on ESC key, scroll or window resize
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            customDropdowns.forEach(d => d.classList.remove('open'));
        }
    });
    window.addEventListener('scroll', () => {
        customDropdowns.forEach(d => d.classList.remove('open'));
    }, { capture: true, passive: true });
    window.addEventListener('resize', () => {
        customDropdowns.forEach(d => d.classList.remove('open'));
    }, { passive: true });

    // 10. Global Click Ripple Animation on Interactive Elements
    const rippleSelectors = '.btn, .botao, .btn-icon, .qty-btn, .cart-remove-btn, .tab-btn, .auth-tab-btn, .admin-tab, .payment-method-card, .custom-div-trigger, .btn-status-save';
    document.addEventListener('click', (e) => {
        const target = e.target.closest(rippleSelectors);
        if (!target) return;

        const rect = target.getBoundingClientRect();
        const ripple = document.createElement('span');
        ripple.classList.add('click-ripple');

        const diameter = Math.max(rect.width, rect.height);
        const radius = diameter / 2;

        ripple.style.width = ripple.style.height = `${diameter}px`;
        ripple.style.left = `${e.clientX - rect.left - radius}px`;
        ripple.style.top = `${e.clientY - rect.top - radius}px`;

        const existingRipple = target.querySelector('.click-ripple');
        if (existingRipple) {
            existingRipple.remove();
        }

        target.appendChild(ripple);
        setTimeout(() => ripple.remove(), 600);
    });
});

/**
 * Global Toast Notification
 */
function showToast(message, type = 'success') {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.style.cssText = `
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        `;
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    const bg = type === 'error' ? 'rgba(239, 68, 68, 0.95)' : (type === 'info' ? 'rgba(6, 182, 212, 0.95)' : 'rgba(16, 185, 129, 0.95)');
    toast.style.cssText = `
        background: ${bg};
        color: #ffffff;
        padding: 12px 24px;
        border-radius: 9999px;
        font-family: var(--font-main, sans-serif);
        font-weight: 600;
        font-size: 0.95rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.5);
        pointer-events: auto;
        opacity: 0;
        transform: translateY(10px);
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        display: flex;
        align-items: center;
        gap: 8px;
    `;
    toast.innerHTML = `<span class="material-symbols-outlined" style="font-size:20px;">${type === 'error' ? 'error' : (type === 'info' ? 'info' : 'check_circle')}</span> ${message}`;

    container.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
    }, 10);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-10px)';
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}
window.showToast = showToast;
