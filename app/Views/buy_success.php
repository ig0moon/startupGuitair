<?= view('templates/header', ['pageTitle' => 'GuitAIr - Pedido Confirmado com Sucesso!']) ?>

<div class="container container-narrow">

    <!-- Passos do Pedido / Stepper -->
    <div class="order-stepper-wrap" style="margin: 20px 0 30px;">
        <div class="order-stepper">
            <div class="stepper-step completed">
                <span class="step-circle"><span class="material-symbols-outlined" style="font-size: 18px;">check</span></span>
                <div class="step-info">
                    <span class="step-number">Passo 1</span>
                    <span class="step-name">Carrinho</span>
                </div>
            </div>
            <div class="stepper-line completed"></div>
            <div class="stepper-step completed">
                <span class="step-circle"><span class="material-symbols-outlined" style="font-size: 18px;">check</span></span>
                <div class="step-info">
                    <span class="step-number">Passo 2</span>
                    <span class="step-name">Entrega & Dados</span>
                </div>
            </div>
            <div class="stepper-line completed"></div>
            <div class="stepper-step completed active">
                <span class="step-circle"><span class="material-symbols-outlined" style="font-size: 18px;">done_all</span></span>
                <div class="step-info">
                    <span class="step-number">Passo 3</span>
                    <span class="step-name">Pedido Confirmado</span>
                </div>
            </div>
        </div>
    </div>

    <div class="glass-card" style="text-align: center; padding: 48px 32px; margin: 0 auto 40px;">
        
        <div style="width: 80px; height: 80px; border-radius: 50%; background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.4); color: var(--brand-emerald); display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; box-shadow: 0 0 25px rgba(16, 185, 129, 0.3);">
            <span class="material-symbols-outlined" style="font-size: 44px;">check_circle</span>
        </div>

        <span class="badge badge-green" style="margin-bottom: 12px;">PEDIDO RECEBIDO COM SUCESSO</span>
        <h1 class="titleAI" style="margin-bottom: 8px;">PARABÉNS PELO SEU NOVO <span class="gradientAI">GUITAIR</span>!</h1>
        <p style="color: var(--text-secondary); max-width: 500px; margin: 0 auto 28px;">
            Seu pedido foi registrado em nosso sistema. Enviamos todos os detalhes e o código de rastreamento para o seu e-mail.
        </p>

        <!-- Order Progress Timeline -->
        <div style="background: rgba(0,0,0,0.25); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 20px; margin-bottom: 24px; text-align: left;">
            <div style="font-size: 0.88rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 14px;">
                Status do Fluxo de Envio
            </div>
            <div class="order-progress-track">
                <div class="progress-point done">
                    <div class="point-dot"><span class="material-symbols-outlined">check</span></div>
                    <span>Pedido Feito</span>
                </div>
                <div class="progress-connector done"></div>
                <div class="progress-point current">
                    <div class="point-dot"><span class="material-symbols-outlined">payments</span></div>
                    <span>Pagamento</span>
                </div>
                <div class="progress-connector"></div>
                <div class="progress-point">
                    <div class="point-dot"><span class="material-symbols-outlined">precision_manufacturing</span></div>
                    <span>Preparação</span>
                </div>
                <div class="progress-connector"></div>
                <div class="progress-point">
                    <div class="point-dot"><span class="material-symbols-outlined">local_shipping</span></div>
                    <span>Em Transporte</span>
                </div>
                <div class="progress-connector"></div>
                <div class="progress-point">
                    <div class="point-dot"><span class="material-symbols-outlined">home</span></div>
                    <span>Entregue</span>
                </div>
            </div>
        </div>

        <!-- Order Info Box -->
        <div style="background: #0c0e16; border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 24px; text-align: left; margin-bottom: 30px;">
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-subtle); padding-bottom: 14px; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
                <div>
                    <span style="color: var(--text-muted); font-size: 0.85rem;">Número do Pedido:</span>
                    <div style="font-family: var(--font-code); font-size: 1.25rem; font-weight: 700; color: var(--brand-primary);">
                        #<?= esc($order['order_number'] ?? 'GT-849201') ?>
                    </div>
                </div>

                <div>
                    <span style="color: var(--text-muted); font-size: 0.85rem; display: block; margin-bottom: 4px;">Forma de Pagamento:</span>
                    <?= payment_method_badge($order['payment_method'] ?? 'pix') ?>
                </div>

                <div style="text-align: right;">
                    <span style="color: var(--text-muted); font-size: 0.85rem; display: block; margin-bottom: 4px;">Status do Pedido:</span>
                    <?= order_status_badge($order['status'] ?? 'pending') ?>
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <h4 style="font-size: 0.95rem; margin-bottom: 8px; color: var(--text-secondary);">Endereço de Entrega:</h4>
                <p style="font-size: 0.95rem; color: var(--text-primary);">
                    <?= esc($order['address'] ?? '') ?>, <?= esc($order['number'] ?? '') ?> <?= esc($order['complement'] ?? '') ?><br>
                    <?= esc($order['neighborhood'] ?? '') ?> - <?= esc($order['city'] ?? '') ?>/<?= esc($order['state'] ?? '') ?><br>
                    CEP: <?= esc($order['zipcode'] ?? '') ?>
                </p>
            </div>

            <div style="border-top: 1px solid var(--border-subtle); padding-top: 14px;">
                <div class="summary-row total" style="margin: 0; padding: 0; border: none;">
                    <span>Valor Total:</span>
                    <span class="total-amount">R$ <?= number_format($order['total'] ?? 0, 2, ',', '.') ?></span>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
            <a href="<?= base_url('conta') ?>" class="btn btn-secondary">
                <span class="material-symbols-outlined">person</span>
                Ver Meus Pedidos
            </a>
            <a href="<?= base_url('produtos') ?>" class="btn btn-primary">
                <span class="material-symbols-outlined">queue_music</span>
                Continuar Navegando
            </a>
        </div>

    </div>

</div>

<?= view('templates/footer') ?>
