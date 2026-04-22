function subirTela() {
    window.scrollTo ({
        top: 0,
        left: 0,
        behavior: 'smooth'
    })
}

function dsplBtnScrl() {
    if(window.scrollY === 0) {
        // oculta o botão
        document.querySelector('.scrlbtn').style.display = 'none'
    } else {
        //mostra o botão
        document.querySelector('.scrlbtn').style.display = 'block'
    }
}

// trigger display button
window.addEventListener('scroll', dsplBtnScrl);