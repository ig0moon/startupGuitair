<?= view('templates/header', ['pageTitle' => 'GuitAIr - Finalizar Compra Segura']) ?>

<div class="container">

    <div class="section-header" style="text-align: left; margin-bottom: 28px;">
        <span class="badge badge-orange" style="margin-bottom: 8px;">CHECKOUT SEGURO</span>
        <h1 class="titleAI" style="text-align: left;">FINALIZAR <span class="gradientAI">PEDIDO</span></h1>
        <p style="color: var(--text-secondary);">Ambiente seguro criptografado com chave SSL de 256 bits.</p>
    </div>

    <form method="POST" action="<?= base_url('comprar/finalizar') ?>" id="checkoutForm">
        <?= csrf_field() ?>

        <div class="cart-layout">
            <!-- Left Column: Forms -->
            <div style="display: flex; flex-direction: column; gap: 28px;">

                <!-- 1. Dados do Cliente -->
                <div class="glass-card">
                    <h2 style="font-size: 1.3rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <span class="material-symbols-outlined" style="color: var(--brand-primary);">person</span>
                        1. Dados Pessoais
                    </h2>

                    <div class="form-row">
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label class="form-label" for="nm">Nome Completo *</label>
                            <input type="text" name="nome" id="nm" placeholder="Ex: Lucas Gabriel da Silva" value="<?= esc($user['name'] ?? '') ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="em">E-mail para Confirmação *</label>
                            <input type="email" name="email" id="em" placeholder="seuemail@exemplo.com" value="<?= esc($user['email'] ?? '') ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="tel">Telefone / WhatsApp</label>
                            <input type="text" name="telefone" id="tel" placeholder="(11) 98765-4321" value="<?= esc($user['phone'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <!-- 2. Endereço de Entrega -->
                <div class="glass-card">
                    <h2 style="font-size: 1.3rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <span class="material-symbols-outlined" style="color: var(--brand-cyan);">local_shipping</span>
                        2. Endereço de Envio
                    </h2>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="cep">CEP *</label>
                            <input type="text" name="cep" id="cep" placeholder="00000-000" maxlength="9" required>
                            <small style="color: var(--text-muted); font-size: 0.78rem;">Digite o CEP para preenchimento automático</small>
                        </div>

                        <div class="form-group" style="grid-column: span 2;">
                            <label class="form-label" for="endereco">Rua / Avenida *</label>
                            <input type="text" name="endereco" id="endereco" placeholder="Ex: Av. Paulista" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="numero">Número *</label>
                            <input type="text" name="numero" id="numero" placeholder="1000" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="complemento">Complemento</label>
                            <input type="text" name="complemento" id="complemento" placeholder="Apto, Bloco">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="bairro">Bairro *</label>
                            <input type="text" name="bairro" id="bairro" placeholder="Bela Vista" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="cidade">Cidade *</label>
                            <input type="text" name="cidade" id="cidade" placeholder="São Paulo" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="estado">Estado (UF) *</label>
                            <input type="text" name="estado" id="estado" placeholder="SP" maxlength="2" required style="text-transform: uppercase;">
                        </div>
                    </div>
                </div>

                <!-- 3. Forma de Pagamento -->
                <div class="glass-card">
                    <h2 style="font-size: 1.3rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <span class="material-symbols-outlined" style="color: var(--brand-secondary);">payments</span>
                        3. Forma de Pagamento
                    </h2>

                    <input type="hidden" name="payment_method" id="selectedPaymentMethod" value="pix">

                    <div class="payment-methods-grid">
                        <div class="payment-method-card active" data-method="pix">
                            <span class="material-symbols-outlined">qr_code_2</span>
                            <div style="font-weight: 600; font-size: 0.95rem;">PIX Instantâneo</div>
                            <small style="color: var(--brand-emerald); font-weight: 500;">Aprovação Imediata</small>
                        </div>

                        <div class="payment-method-card" data-method="credit_card">
                            <span class="material-symbols-outlined">credit_card</span>
                            <div style="font-weight: 600; font-size: 0.95rem;">Cartão de Crédito</div>
                            <small style="color: var(--text-muted);">Até 12x sem juros</small>
                        </div>

                        <div class="payment-method-card" data-method="boleto">
                            <span class="material-symbols-outlined">receipt_long</span>
                            <div style="font-weight: 600; font-size: 0.95rem;">Boleto Bancário</div>
                            <small style="color: var(--text-muted);">Vencimento em 3 dias</small>
                        </div>
                    </div>

                    <!-- PIX Section -->
                    <div id="payment-pix-section" style="padding: 24px; background: #0c0e16; border-radius: var(--radius-md); border: 1px solid var(--border-subtle); text-align: center;">
                        <div style="display: inline-block; padding: 12px; background: #ffffff; border-radius: 12px; margin-bottom: 16px;">
                            <!-- Simulated QR code representation -->
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=00020126580014BR.GOV.BCB.PIX0136guitair-pagamento-simulado-20265204000053039865802BR5925GUITAIR%20MUSICAL%20LABS6009SAO%20PAULO62070503***6304" alt="QR Code PIX" style="width: 150px; height: 150px;">
                        </div>
                        <h4 style="margin-bottom: 8px;">Escaneie o QR Code ou copie a chave Pix</h4>
                        <p style="color: var(--text-secondary); font-size: 0.88rem; max-width: 480px; margin: 0 auto 16px;">
                            Abra o aplicativo do seu banco, escolha Pagar via Pix e aponte a câmera ou utilize a chave copia-e-cola abaixo.
                        </p>

                        <div style="display: flex; gap: 8px; max-width: 480px; margin: 0 auto;">
                            <input type="text" id="pixCopyCode" value="00020126580014BR.GOV.BCB.PIX0136guitair-pagamento-simulado-20265204000053039865802BR5925GUITAIR%20MUSICAL%20LABS" readonly style="font-family: var(--font-code); font-size: 0.8rem;">
                            <button type="button" id="copyPixBtn" class="btn btn-secondary btn-sm" style="flex-shrink: 0;">
                                Copiar
                            </button>
                        </div>
                    </div>

                    <!-- Credit Card Section -->
                    <div id="payment-card-section" style="display: none;">
                        <!-- Interactive Live Credit Card Preview -->
                        <div class="credit-card-preview">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div class="card-chip"></div>
                                <span style="font-family: var(--font-code); font-weight: 700; font-size: 1.1rem; color: #fff;">GUITAIR CARD</span>
                            </div>

                            <div class="card-number-display" id="displayCardNumber">
                                •••• •••• •••• ••••
                            </div>

                            <div class="card-details-row">
                                <div class="card-holder-name">
                                    <span>TITULAR</span>
                                    <strong id="displayCardHolder"><?= esc(!empty($user['name']) ? strtoupper($user['name']) : 'SEU NOME COMPLETO') ?></strong>
                                </div>
                                <div class="card-holder-name" style="text-align: right;">
                                    <span>VALIDADE</span>
                                    <strong id="displayCardExpiry">MM/AA</strong>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="card_number">Número do Cartão *</label>
                            <input type="text" id="card_number" placeholder="4000 1234 5678 9010" maxlength="19">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="card_holder">Nome Impresso no Cartão *</label>
                            <input type="text" id="card_holder" placeholder="NOME COMO NO CARTÃO" style="text-transform: uppercase;">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="card_expiry">Validade (MM/AA) *</label>
                                <input type="text" id="card_expiry" placeholder="12/28" maxlength="5">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="card_cvv">CVV (Código de Segurança) *</label>
                                <input type="password" id="card_cvv" placeholder="•••" maxlength="4">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="card_installments">Parcelamento</label>
                                <select id="card_installments">
                                    <option value="1">1x de R$ <?= number_format($total, 2, ',', '.') ?> (sem juros)</option>
                                    <option value="3">3x de R$ <?= number_format($total / 3, 2, ',', '.') ?> (sem juros)</option>
                                    <option value="6">6x de R$ <?= number_format($total / 6, 2, ',', '.') ?> (sem juros)</option>
                                    <option value="12">12x de R$ <?= number_format($total / 12, 2, ',', '.') ?> (sem juros)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Boleto Section -->
                    <div id="payment-boleto-section" style="display: none; padding: 24px; background: #0c0e16; border-radius: var(--radius-md); border: 1px solid var(--border-subtle); text-align: center;">
                        <span class="material-symbols-outlined" style="font-size: 48px; color: var(--text-muted); margin-bottom: 12px;">barcode_scanner</span>
                        <h4 style="margin-bottom: 8px;">Boleto Bancário Registrado</h4>
                        <p style="color: var(--text-secondary); font-size: 0.9rem; max-width: 480px; margin: 0 auto;">
                            Ao finalizar a compra, o boleto bancário será gerado na tela com código de barras digitável. Pode ser pago em qualquer banco ou aplicativo até o vencimento.
                        </p>
                    </div>

                </div>

            </div>

            <!-- Right Column: Order Summary -->
            <div class="order-summary-card">
                <h2 class="summary-title">Itens do Pedido</h2>

                <div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 20px;">
                    <?php foreach ($items as $item): ?>
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 44px; height: 44px; background: #0c0e16; border-radius: 6px; padding: 4px; display: flex; align-items: center; justify-content: center;">
                                    <img src="<?= base_url($item['image']) ?>" alt="" style="max-height: 100%;">
                                </div>
                                <div>
                                    <div style="font-size: 0.92rem; font-weight: 600;"><?= esc($item['name']) ?></div>
                                    <small style="color: var(--text-muted);">Qtd: <?= esc($item['quantity']) ?></small>
                                </div>
                            </div>
                            <div style="font-family: var(--font-code); font-size: 0.95rem; font-weight: 600;">
                                R$ <?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
                </div>

                <div class="summary-row">
                    <span>Frete:</span>
                    <span><?= ($shipping == 0) ? '<strong style="color: var(--brand-emerald)">GRÁTIS</strong>' : 'R$ ' . number_format($shipping, 2, ',', '.') ?></span>
                </div>

                <?php if ($discount > 0): ?>
                    <div class="summary-row" style="color: var(--brand-emerald);">
                        <span>Desconto Cupom:</span>
                        <span>- R$ <?= number_format($discount, 2, ',', '.') ?></span>
                    </div>
                <?php endif; ?>

                <div class="summary-row total">
                    <span>Total a Pagar:</span>
                    <span class="total-amount">R$ <?= number_format($total, 2, ',', '.') ?></span>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top: 24px;">
                    <span class="material-symbols-outlined">lock</span>
                    Confirmar e Finalizar Pedido
                </button>

                <div style="margin-top: 20px; display: flex; flex-direction: column; gap: 8px; font-size: 0.8rem; color: var(--text-muted); text-align: center;">
                    <div>🔒 Seus dados bancários nunca são armazenados em nossos servidores.</div>
                    <div>📦 Rastreamento garantido enviado para o seu e-mail.</div>
                </div>
            </div>
        </div>
    </form>

</div>

<script src="<?= base_url('js/checkout.js') ?>"></script>

<?= view('templates/footer') ?>
