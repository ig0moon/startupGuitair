function subirTela() {
    window.scrollTo({
        top: 0,
        left: 0,
        behavior: 'smooth'
    });
}

function dsplBtnScrl() {
    const btn = document.querySelector('.scrlbtn');
    if (!btn) return;
    if (window.scrollY === 0) {
        btn.style.display = 'none';
    } else {
        btn.style.display = 'block';
    }
}

window.addEventListener('scroll', dsplBtnScrl);