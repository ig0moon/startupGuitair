<?= view('templates/header', ['pageTitle' => 'GuitAIr - Carrinho de Compras']) ?>

<div class="container">

    <div class="section-header" style="text-align: left; margin-bottom: 24px;">
        <span class="badge badge-orange" style="margin-bottom: 8px;">CHECKOUT STEP 1</span>
        <h1 class="titleAI" style="text-align: left;">SEU <span class="gradientAI">CARRINHO</span> DE COMPRAS</h1>
    </div>

    <?php if (!empty($cart['items'])): ?>
        <div class="cart-layout">
            <!-- Left Column: Products in Cart -->
            <div class="cart-table-wrap">
                <form id="cartUpdateForm" method="POST" action="<?= base_url('carrinho/atualizar') ?>">
                    <?= csrf_field() ?>

                    <?php foreach ($cart['items'] as $item): ?>
                        <div class="cart-item">
                            <div class="cart-thumb">
                                <img src="<?= base_url($item['image']) ?>" alt="<?= esc($item['name']) ?>">
                            </div>

                            <div class="cart-info">
                                <h3 class="cart-title"><?= esc($item['name']) ?></h3>
                                <div class="cart-unit-price">
                                    R$ <?= number_format($item['price'], 2, ',', '.') ?> cada
                                </div>
                            </div>

                            <!-- Quantity controls -->
                            <div class="qty-control">
                                <button type="button" class="qty-btn" onclick="decreaseQty(<?= esc($item['id']) ?>)">-</button>
                                <input type="number" 
                                       class="qty-input" 
                                       id="qty_<?= esc($item['id']) ?>" 
                                       name="quantities[<?= esc($item['id']) ?>]" 
                                       value="<?= esc($item['quantity']) ?>" 
                                       min="1" 
                                       max="99" 
                                       readonly>
                                <button type="button" class="qty-btn" onclick="increaseQty(<?= esc($item['id']) ?>)">+</button>
                            </div>

                            <!-- Item Subtotal -->
                            <div class="cart-subtotal">
                                R$ <?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?>
                            </div>

                            <!-- Remove Button -->
                            <a href="<?= base_url('carrinho/remover/' . esc($item['id'])) ?>" class="cart-remove-btn" title="Remover item">
                                <span class="material-symbols-outlined">delete</span>
                            </a>
                        </div>
                    <?php endforeach; ?>

                    <div style="padding: 16px 24px; background: rgba(0,0,0,0.2); display: flex; justify-content: space-between; align-items: center; flex-wrap: gap: 12px;">
                        <a href="<?= base_url('carrinho/limpar') ?>" style="color: var(--text-muted); font-size: 0.88rem; display: inline-flex; align-items: center; gap: 6px;">
                            <span class="material-symbols-outlined" style="font-size: 18px;">remove_shopping_cart</span>
                            Esvaziar Carrinho
                        </a>
                        <button type="submit" class="btn btn-secondary btn-sm">
                            <span class="material-symbols-outlined" style="font-size: 18px;">sync</span>
                            Atualizar Quantidades
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Column: Order Summary & Coupon -->
            <div class="order-summary-card">
                <h2 class="summary-title">Resumo do Pedido</h2>

                <!-- Free Shipping Progress -->
                <?php 
                $freeShippingThreshold = 2500.00;
                $remainingForFree = max(0, $freeShippingThreshold - $cart['subtotal']);
                $progressPercent = min(100, ($cart['subtotal'] / $freeShippingThreshold) * 100);
                ?>
                <div style="margin-bottom: 20px; padding: 14px; background: rgba(255, 107, 43, 0.08); border: 1px solid rgba(255, 107, 43, 0.25); border-radius: var(--radius-md);">
                    <?php if ($remainingForFree <= 0): ?>
                        <div style="color: var(--brand-emerald); font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 6px;">
                            <span class="material-symbols-outlined">local_shipping</span>
                            Parabéns! Você ganhou Frete Grátis!
                        </div>
                    <?php else: ?>
                        <div style="color: var(--text-secondary); font-size: 0.88rem; margin-bottom: 8px;">
                            Adicione mais <strong style="color: var(--brand-primary);">R$ <?= number_format($remainingForFree, 2, ',', '.') ?></strong> para garantir <strong>Frete Grátis</strong>!
                        </div>
                        <div style="width: 100%; height: 6px; background: rgba(255,255,255,0.1); border-radius: 3px; overflow: hidden;">
                            <div style="width: <?= $progressPercent ?>%; height: 100%; background: var(--brand-gradient); border-radius: 3px;"></div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="summary-row">
                    <span>Subtotal dos itens (<?= $cart['total_items'] ?>):</span>
                    <span>R$ <?= number_format($cart['subtotal'], 2, ',', '.') ?></span>
                </div>

                <div class="summary-row">
                    <span>Frete:</span>
                    <span><?= ($cart['shipping'] == 0) ? '<strong style="color: var(--brand-emerald)">GRÁTIS</strong>' : 'R$ ' . number_format($cart['shipping'], 2, ',', '.') ?></span>
                </div>

                <?php if ($cart['discount'] > 0): ?>
                    <div class="summary-row" style="color: var(--brand-emerald);">
                        <span>Desconto Aplicado:</span>
                        <span>- R$ <?= number_format($cart['discount'], 2, ',', '.') ?></span>
                    </div>
                <?php endif; ?>

                <div class="summary-row total">
                    <span>Total:</span>
                    <span class="total-amount">R$ <?= number_format($cart['total'], 2, ',', '.') ?></span>
                </div>

                <!-- Coupon Form -->
                <form method="POST" action="<?= base_url('carrinho/cupom') ?>" class="coupon-box">
                    <?= csrf_field() ?>
                    <input type="text" name="coupon" placeholder="Cupom (ex: GUITAIR10)" value="<?= esc($cart['coupon']['code'] ?? '') ?>" required style="text-transform: uppercase;">
                    <button type="submit" class="btn btn-secondary btn-sm">Aplicar</button>
                </form>

                <!-- Proceed to Checkout -->
                <a href="<?= base_url('comprar') ?>" class="btn btn-primary btn-block btn-lg" style="margin-top: 10px;">
                    <span>Finalizar Compra</span>
                    <span class="material-symbols-outlined">arrow_forward</span>
                </a>

                <div style="margin-top: 18px; text-align: center;">
                    <a href="<?= base_url('produtos') ?>" style="color: var(--text-muted); font-size: 0.9rem;">
                        ← Continuar Comprando
                    </a>
                </div>
            </div>
        </div>

    <?php else: ?>
        <!-- Empty Cart State -->
        <div class="glass-card" style="text-align: center; padding: 70px 24px; max-width: 600px; margin: 40px auto;">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: rgba(255, 107, 43, 0.1); border: 1px solid rgba(255, 107, 43, 0.25); color: var(--brand-primary); display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                <span class="material-symbols-outlined" style="font-size: 40px;">shopping_cart</span>
            </div>
            <h2 style="font-size: 1.6rem; margin-bottom: 12px;">Seu carrinho está vazio no momento.</h2>
            <p style="color: var(--text-secondary); max-width: 440px; margin: 0 auto 30px; font-size: 1rem;">
                Explore nossa linha pioneira de guitarras, violões e baterias inteligentes com inteligência artificial embarcada!
            </p>
            <a href="<?= base_url('produtos') ?>" class="btn btn-primary btn-lg">
                <span class="material-symbols-outlined">queue_music</span>
                Explorar Instrumentos
            </a>
        </div>
    <?php endif; ?>

</div>

<script>
function increaseQty(id) {
    const input = document.getElementById('qty_' + id);
    if (input) {
        let val = parseInt(input.value) || 1;
        input.value = Math.min(99, val + 1);
        document.getElementById('cartUpdateForm').submit();
    }
}
function decreaseQty(id) {
    const input = document.getElementById('qty_' + id);
    if (input) {
        let val = parseInt(input.value) || 1;
        if (val > 1) {
            input.value = val - 1;
            document.getElementById('cartUpdateForm').submit();
        }
    }
}
</script>

<?= view('templates/footer') ?>
