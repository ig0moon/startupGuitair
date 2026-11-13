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

                        <a href="<?= base_url('produtos/' . $prod['slug']) ?>#avaliacoes" class="product-rating" style="text-decoration: none;" title="Ver avaliações">
                            <?php if ((int)($prod['reviews_count'] ?? 0) > 0): ?>
                                <span class="star">★</span>
                                <span><?= esc(number_format((float)$prod['rating'], 1, ',', '')) ?></span>
                                <span style="color: var(--text-muted); font-size: 0.75rem;">(<?= (int)$prod['reviews_count'] ?>)</span>
                            <?php else: ?>
                                <span class="star">☆</span>
                                <span style="color: var(--text-muted); font-size: 0.75rem;">Sem avaliações</span>
                            <?php endif; ?>
                        </a>
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

    <?php if (!empty($selectedProduct)): ?>
    <!-- Avaliações do produto (RN14) -->
    <div class="glass-card" id="avaliacoes" style="margin: 40px 0;">
        <h2 style="margin-bottom: 6px;">Avaliações</h2>
        <?php $total = count($reviews ?? []); ?>
        <p style="color: var(--text-secondary); margin-bottom: 24px;">
            <?php if ($total > 0): ?>
                <span class="star">★</span> <strong><?= esc(number_format((float)$selectedProduct['rating'], 1, ',', '')) ?></strong>
                de 5 · <?= $total ?> <?= $total === 1 ? 'avaliação' : 'avaliações' ?>
            <?php else: ?>
                Este produto ainda não tem avaliações. Seja a primeira pessoa a avaliar!
            <?php endif; ?>
        </p>

        <?php if (!empty($loggedUser['id'])): ?>
            <form method="POST" action="<?= base_url('produtos/avaliar') ?>" style="margin-bottom: 32px;">
                <?= csrf_field() ?>
                <input type="hidden" name="product_id" value="<?= esc($selectedProduct['id']) ?>">
                <h3 style="font-size: 1.05rem; margin-bottom: 12px;"><?= !empty($myReview) ? 'Editar sua avaliação' : 'Avaliar este produto' ?></h3>
                <div class="form-group" style="margin-bottom: 12px;">
                    <label for="rating">Nota</label>
                    <select name="rating" id="rating" class="form-control" required>
                        <?php for ($n = 5; $n >= 1; $n--): ?>
                            <option value="<?= $n ?>" <?= ((int)($myReview['rating'] ?? 5) === $n) ? 'selected' : '' ?>>
                                <?= str_repeat('★', $n) . str_repeat('☆', 5 - $n) ?> (<?= $n ?>)
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 12px;">
                    <label for="comment">Comentário (opcional)</label>
                    <textarea name="comment" id="comment" class="form-control" rows="3" maxlength="500" placeholder="Conte como foi sua experiência"><?= esc($myReview['comment'] ?? '') ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-sm"><?= !empty($myReview) ? 'Atualizar avaliação' : 'Publicar avaliação' ?></button>
            </form>
        <?php else: ?>
            <p style="margin-bottom: 32px;">
                <a href="<?= base_url('conta') ?>" class="btn btn-secondary btn-sm">Entre na sua conta para avaliar</a>
            </p>
        <?php endif; ?>

        <?php foreach (($reviews ?? []) as $rev): ?>
            <?php
                $parts = preg_split('/\s+/', trim($rev['author']));
                $shortName = $parts[0] . (count($parts) > 1 ? ' ' . mb_substr(end($parts), 0, 1) . '.' : '');
            ?>
            <div style="border-top: 1px solid var(--border-subtle); padding: 16px 0;">
                <div style="display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                    <strong><?= esc($shortName) ?></strong>
                    <span style="color: var(--text-muted); font-size: 0.8rem;"><?= esc(date('d/m/Y', strtotime($rev['created_at']))) ?></span>
                </div>
                <div class="star" style="margin: 4px 0;"><?= str_repeat('★', (int)$rev['rating']) . str_repeat('☆', 5 - (int)$rev['rating']) ?></div>
                <?php if (!empty($rev['comment'])): ?>
                    <p style="color: var(--text-secondary);"><?= esc($rev['comment']) ?></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</div>

<?= view('templates/footer') ?>
