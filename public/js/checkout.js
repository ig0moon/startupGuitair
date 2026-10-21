/**
 * GUITAIR - Interactive Checkout Scripts
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Payment Method Switcher
    const methodCards = document.querySelectorAll('.payment-method-card');
    const paymentSections = {
        pix: document.getElementById('payment-pix-section'),
        credit_card: document.getElementById('payment-card-section'),
        boleto: document.getElementById('payment-boleto-section')
    };
    const paymentInput = document.getElementById('selectedPaymentMethod');

    methodCards.forEach(card => {
        card.addEventListener('click', () => {
            const method = card.getAttribute('data-method');

            methodCards.forEach(c => c.classList.remove('active'));
            card.classList.add('active');

            if (paymentInput) {
                paymentInput.value = method;
            }

            Object.keys(paymentSections).forEach(key => {
                if (paymentSections[key]) {
                    paymentSections[key].style.display = (key === method) ? 'block' : 'none';
                }
            });
        });
    });

    // 2. Interactive Credit Card Live Preview
    const cardNumberInput = document.getElementById('card_number');
    const cardHolderInput = document.getElementById('card_holder');
    const cardExpiryInput = document.getElementById('card_expiry');

    const displayCardNumber = document.getElementById('displayCardNumber');
    const displayCardHolder = document.getElementById('displayCardHolder');
    const displayCardExpiry = document.getElementById('displayCardExpiry');

    if (cardNumberInput && displayCardNumber) {
        cardNumberInput.addEventListener('input', (e) => {
            let val = e.target.value.replace(/\D/g, '').substring(0, 16);
            let formatted = val.replace(/(\d{4})/g, '$1 ').trim();
            e.target.value = formatted;
            displayCardNumber.textContent = formatted || '•••• •••• •••• ••••';
        });
    }

    if (cardHolderInput && displayCardHolder) {
        cardHolderInput.addEventListener('input', (e) => {
            displayCardHolder.textContent = e.target.value.toUpperCase() || 'SEU NOME COMPLETO';
        });
    }

    if (cardExpiryInput && displayCardExpiry) {
        cardExpiryInput.addEventListener('input', (e) => {
            let val = e.target.value.replace(/\D/g, '').substring(0, 4);
            if (val.length >= 2) {
                val = val.substring(0, 2) + '/' + val.substring(2);
            }
            e.target.value = val;
            displayCardExpiry.textContent = val || 'MM/AA';
        });
    }

    // 3. Copy PIX code
    const copyPixBtn = document.getElementById('copyPixBtn');
    if (copyPixBtn) {
        copyPixBtn.addEventListener('click', () => {
            const pixCode = document.getElementById('pixCopyCode');
            if (pixCode) {
                navigator.clipboard.writeText(pixCode.value).then(() => {
                    if (window.showToast) {
                        window.showToast('Código PIX copiado com sucesso! Abra seu banco para pagar.', 'success');
                    } else {
                        alert('Código PIX copiado!');
                    }
                }).catch(() => {
                    pixCode.select();
                    document.execCommand('copy');
                    if (window.showToast) window.showToast('Código copiado!', 'success');
                });
            }
        });
    }

    // 4. CEP Autocomplete with ViaCEP
    const cepInput = document.getElementById('cep');
    if (cepInput) {
        cepInput.addEventListener('blur', () => {
            const cep = cepInput.value.replace(/\D/g, '');
            if (cep.length === 8) {
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(res => res.json())
                    .then(data => {
                        if (!data.erro) {
                            const end = document.getElementById('endereco');
                            const bai = document.getElementById('bairro');
                            const cid = document.getElementById('cidade');
                            const est = document.getElementById('estado');

                            if (end) end.value = data.logradouro || '';
                            if (bai) bai.value = data.bairro || '';
                            if (cid) cid.value = data.localidade || '';
                            if (est) est.value = data.uf || '';

                            const num = document.getElementById('numero');
                            if (num) num.focus();

                            if (window.showToast) {
                                window.showToast('Endereço preenchido automaticamente via CEP!', 'info');
                            }
                        }
                    })
                    .catch(() => {});
            }
        });
    }
});
