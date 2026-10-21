    </main>

    <!-- Footer -->
    <footer>
        <div class="footer-inner">
            <div class="footer-top">
                <!-- Brand Info -->
                <div class="footer-brand">
                    <a href="<?= base_url('/') ?>" class="logo" style="margin-bottom: 12px; display: inline-flex;">
                        <img src="<?= base_url('img/guitair.png') ?>" alt="GuitAIr Logo" style="height: 32px;">
                    </a>
                    <p>
                        A startup pioneira em unir a arte dos instrumentos musicais tradicionais ao poder transformador da Inteligência Artificial em tempo real.
                    </p>
                    <div class="footer-trust-badges">
                        <span><span class="material-symbols-outlined" style="color: var(--brand-emerald); font-size:18px;">lock</span> SSL 256-Bit</span>
                        <span><span class="material-symbols-outlined" style="color: var(--brand-cyan); font-size:18px;">verified_user</span> Garantia 2 Anos</span>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="footer-col">
                    <h4>Navegação</h4>
                    <ul>
                        <li><a href="<?= base_url('/') ?>">Início</a></li>
                        <li><a href="<?= base_url('produtos') ?>">Instrumentos IA</a></li>
                        <li><a href="<?= base_url('sobre') ?>">Tecnologia & Visão</a></li>
                        <li><a href="<?= base_url('ajuda') ?>">Central de Suporte</a></li>
                    </ul>
                </div>

                <!-- Legal / Account -->
                <div class="footer-col">
                    <h4>Minha Conta</h4>
                    <ul>
                        <li><a href="<?= base_url('conta') ?>">Acessar Painel</a></li>
                        <li><a href="<?= base_url('carrinho') ?>">Ver Carrinho</a></li>
                        <li><a href="<?= base_url('comprar') ?>">Finalizar Pedido</a></li>
                        <li><a href="<?= base_url('ajuda') ?>">Rastreamento</a></li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div class="footer-col footer-newsletter">
                    <h4>Novidades & Atualizações</h4>
                    <p>Receba lançamentos exclusivos e atualizações do nosso firmware neural.</p>
                    <form class="newsletter-form" onsubmit="event.preventDefault(); showToast('Obrigado por se inscrever!', 'success'); this.reset();">
                        <input type="email" placeholder="Seu e-mail..." required style="padding: 10px 16px; font-size: 0.9rem;">
                        <button type="submit" class="btn btn-primary btn-sm">Inscrever</button>
                    </form>
                </div>
            </div>

            <div class="footer-bottom">
                <div>
                    &copy; <?= date('Y') ?> GuitAIr Musical Labs Inc. Todos os direitos reservados.
                </div>
                <div style="display: flex; gap: 16px;">
                    <a href="<?= base_url('ajuda') ?>" style="color: var(--text-muted); font-size: 0.85rem;">Privacidade</a>
                    <a href="<?= base_url('ajuda') ?>" style="color: var(--text-muted); font-size: 0.85rem;">Termos de Uso</a>
                    <a href="<?= base_url('ajuda') ?>" style="color: var(--text-muted); font-size: 0.85rem;">Contato</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating Scroll-To-Top Button -->
    <div class="scrlbtn" title="Voltar ao Topo" aria-label="Subir ao topo">
        <span class="material-symbols-outlined" style="font-size: 26px;">keyboard_arrow_up</span>
    </div>

    <!-- Global App JavaScript -->
    <script src="<?= base_url('js/app.js') ?>"></script>
</body>
</html>
