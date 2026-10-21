<?= view('templates/header', ['pageTitle' => $pageTitle ?? 'GuitAIr - Produtos Inteligentes']) ?>

<div class="container">

    <div class="section-header" style="margin-top: 20px;">
        <span class="badge badge-orange" style="margin-bottom: 12px;">CATÁLOGO DE INSTRUMENTOS</span>
        <h1 class="titleAI">LINHA DE INSTRUMENTOS <span class="gradientAI">GUITAIR</span></h1>
        <p class="subtitleAI">Explore nossos instrumentos equipados com hardware de inteligência artificial em tempo real, criados para transformar seu aprendizado e performance musical.</p>
    </div>

    <!-- Category Filter Tabs -->
    <div class="category-tabs">
        <a href="<?= base_url('produtos?categoria=todos') ?>" class="tab-btn <?= ($currentCategory === 'todos') ? 'active' : '' ?>">
            Todos os Instrumentos
        </a>
        <a href="<?= base_url('produtos?categoria=violao') ?>" class="tab-btn <?= ($currentCategory === 'violao') ? 'active' : '' ?>">
            Violões Acústicos IA
        </a>
        <a href="<?= base_url('produtos?categoria=guitarra') ?>" class="tab-btn <?= ($currentCategory === 'guitarra') ? 'active' : '' ?>">
            Guitarras Elétricas Neurais
        </a>
        <a href="<?= base_url('produtos?categoria=bateria') ?>" class="tab-btn <?= ($currentCategory === 'bateria') ? 'active' : '' ?>">
            Baterias Eletrônicas Smart
        </a>
    </div>

    <!-- Products Grid -->
    <div class="products-grid">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $prod): ?>
                <div class="product-card">
                    <div class="product-image-wrap">
                        <?php if (!empty($prod['badge'])): ?>
                            <div class="product-badge-wrap">
                                <span class="badge <?= ($prod['category'] === 'guitarra') ? 'badge-purple' : (($prod['category'] === 'bateria') ? 'badge-cyan' : 'badge-orange') ?>">
                                    <?= esc($prod['badge']) ?>
                                </span>
                            </div>
                        <?php endif; ?>

                        <img src="<?= base_url($prod['image']) ?>" alt="<?= esc($prod['name']) ?>" loading="lazy">

                        <div class="product-rating">
                            <span class="star">★</span>
                            <span><?= esc($prod['rating'] ?? '4.9') ?></span>
                            <span style="color: var(--text-muted); font-size: 0.75rem;">(<?= esc($prod['reviews_count'] ?? '35') ?>)</span>
                        </div>
                    </div>

                    <div class="product-body">
                        <span class="product-category"><?= esc(strtoupper($prod['category'])) ?></span>
                        <h3 class="product-title"><?= esc($prod['name']) ?></h3>
                        <p class="product-tagline"><?= esc($prod['tagline']) ?></p>

                        <p style="color: var(--text-secondary); font-size: 0.9rem; line-height: 1.5; margin-bottom: 16px;">
                            <?= esc(mb_strimwidth($prod['description'] ?? '', 0, 140, '...')) ?>
                        </p>

                        <!-- Highlights -->
                        <?php if (!empty($prod['features']) && is_array($prod['features'])): ?>
                            <ul class="product-features-list">
                                <?php foreach (array_slice($prod['features'], 0, 3) as $feat): ?>
                                    <li>
                                        <span class="material-symbols-outlined check-icon">check_circle</span>
                                        <span><?= esc($feat) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <!-- Technical Specs Pills -->
                        <?php if (!empty($prod['specs']) && is_array($prod['specs'])): ?>
                            <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 20px;">
                                <?php foreach (array_slice($prod['specs'], 0, 2) as $key => $spec): ?>
                                    <span style="font-family: var(--font-code); font-size: 0.75rem; background: rgba(255,255,255,0.05); padding: 3px 8px; border-radius: 4px; border: 1px solid var(--border-subtle); color: var(--text-muted);">
                                        <strong style="color: var(--text-secondary);"><?= esc($key) ?>:</strong> <?= esc($spec) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="product-footer">
                            <div class="price-box">
                                <?php if (!empty($prod['old_price'])): ?>
                                    <span class="old-price">R$ <?= number_format($prod['old_price'], 2, ',', '.') ?></span>
                                <?php endif; ?>
                                <span class="current-price">R$ <?= number_format($prod['price'], 2, ',', '.') ?></span>
                            </div>

                            <div class="product-actions">
                                <form method="POST" action="<?= base_url('carrinho/adicionar') ?>">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="product_id" value="<?= esc($prod['id']) ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn-icon" title="Adicionar ao Carrinho" aria-label="Adicionar ao Carrinho">
                                        <span class="material-symbols-outlined">add_shopping_cart</span>
                                    </button>
                                </form>

                                <form method="POST" action="<?= base_url('comprar') ?>">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="modelo" value="<?= esc($prod['slug']) ?>">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        Comprar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px; background: var(--bg-surface); border-radius: var(--radius-lg);">
                <span class="material-symbols-outlined" style="font-size: 48px; color: var(--text-muted); margin-bottom: 12px;">search_off</span>
                <h3>Nenhum instrumento encontrado nesta categoria.</h3>
                <br>
                <a href="<?= base_url('produtos?categoria=todos') ?>" class="btn btn-secondary">Ver Todos os Produtos</a>
            </div>
        <?php endif; ?>
    </div>

</div>

<?= view('templates/footer') ?>
