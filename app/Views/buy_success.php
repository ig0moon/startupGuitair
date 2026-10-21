<?= view('templates/header', ['pageTitle' => 'GuitAIr - Pedido Confirmado com Sucesso!']) ?>

<div class="container container-narrow">

    <div class="glass-card" style="text-align: center; padding: 48px 32px; margin: 40px auto;">
        
        <div style="width: 80px; height: 80px; border-radius: 50%; background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.4); color: var(--brand-emerald); display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
            <span class="material-symbols-outlined" style="font-size: 44px;">check_circle</span>
        </div>

        <span class="badge badge-green" style="margin-bottom: 12px;">PEDIDO RECEBIDO</span>
        <h1 class="titleAI" style="margin-bottom: 8px;">PARABÉNS PELO SEU NOVO <span class="gradientAI">GUITAIR</span>!</h1>
        <p style="color: var(--text-secondary); max-width: 500px; margin: 0 auto 28px;">
            Seu pedido foi registrado em nosso sistema. Enviamos todos os detalhes e o código de rastreamento para o seu e-mail.
        </p>

        <!-- Order Info Box -->
        <div style="background: #0c0e16; border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 24px; text-align: left; margin-bottom: 30px;">
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-subtle); padding-bottom: 14px; margin-bottom: 16px;">
                <div>
                    <span style="color: var(--text-muted); font-size: 0.85rem;">Número do Pedido:</span>
                    <div style="font-family: var(--font-code); font-size: 1.25rem; font-weight: 700; color: var(--brand-primary);">
                        #<?= esc($order['order_number'] ?? 'GT-849201') ?>
                    </div>
                </div>

                <div style="text-align: right;">
                    <span style="color: var(--text-muted); font-size: 0.85rem;">Status:</span>
                    <div>
                        <span class="badge badge-orange">Aguardando Envio</span>
                    </div>
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
                    <span>Valor Total Pago:</span>
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
