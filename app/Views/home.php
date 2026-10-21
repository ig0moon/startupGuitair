<?= view('templates/header', ['pageTitle' => 'GuitAIr - Instrumentos Musicais Inteligentes com IA']) ?>

<!-- HERO SECTION -->
<section class="hero-section">
    <div class="hero-glow"></div>
    <div class="hero-content">
        <div class="hero-tag">
            <span class="dot"></span>
            <span>NOVA GERAÇÃO DE ÁUDIO NEURAL 2026</span>
        </div>

        <h1 class="hero-title">
            O FUTURO DA MÚSICA <br>
            IMPULSIONADO POR <span class="gradientAI">INTELIGÊNCIA ARTIFICIAL</span>
        </h1>

        <p class="hero-desc">
            Instrumentos inteligentes com processamento neural em tempo real, guia de escalas iluminado no braço, afinação assistida e simulação analógica de alta fidelidade.
        </p>

        <!-- Dynamic Soundwave -->
        <div class="soundwave-container" aria-hidden="true">
            <span class="soundwave-bar"></span>
            <span class="soundwave-bar"></span>
            <span class="soundwave-bar"></span>
            <span class="soundwave-bar"></span>
            <span class="soundwave-bar"></span>
            <span class="soundwave-bar"></span>
            <span class="soundwave-bar"></span>
            <span class="soundwave-bar"></span>
            <span class="soundwave-bar"></span>
            <span class="soundwave-bar"></span>
        </div>

        <div class="hero-cta">
            <a href="<?= base_url('produtos') ?>" class="btn btn-primary btn-lg">
                <span class="material-symbols-outlined">queue_music</span>
                Explorar Instrumentos
            </a>
            <a href="<?= base_url('sobre') ?>" class="btn btn-secondary btn-lg">
                <span class="material-symbols-outlined">play_circle</span>
                Como Funciona a IA
            </a>
        </div>
    </div>
</section>

<div class="container">

    <!-- BANNERS SHOWCASE -->
    <div class="hero-banners">
        <div class="banner-card">
            <img src="<?= base_url('img/banner_guitair.png') ?>" alt="GuitAIr Performance Banner" loading="lazy">
        </div>
        <div class="banner-card">
            <img src="<?= base_url('img/banner_guitair2.png') ?>" alt="GuitAIr Studio Banner" loading="lazy">
        </div>
    </div>

    <!-- VALUE PROPOSITIONS -->
    <div class="features-grid">
        <div class="feature-box">
            <div class="feature-icon">
                <span class="material-symbols-outlined">memory</span>
            </div>
            <h3>Processamento DSP Neural</h3>
            <p>Latência ultrabaixa de 1.2ms. Toda a inteligência roda nativamente no próprio instrumento, sem necessidade de computador ligado.</p>
        </div>

        <div class="feature-box">
            <div class="feature-icon" style="color: var(--brand-secondary); background: rgba(139, 92, 246, 0.12); border-color: rgba(139, 92, 246, 0.3);">
                <span class="material-symbols-outlined">auto_fix_high</span>
            </div>
            <h3>Afinação & Guias em LED</h3>
            <p>Micro-LEDs discretos embutidos na escala indicam notas, escalas e dedilhados para acelerar seu aprendizado musical em até 4x.</p>
        </div>

        <div class="feature-box">
            <div class="feature-icon" style="color: var(--brand-cyan); background: rgba(6, 182, 212, 0.12); border-color: rgba(6, 182, 212, 0.3);">
                <span class="material-symbols-outlined">podcasts</span>
            </div>
            <h3>Conectividade DAW & App</h3>
            <p>Grave suas faixas diretamente via USB-C em 24-bit/96kHz ou sincronize sem fio via Bluetooth 5.3 com o aplicativo oficial GuitAIr.</p>
        </div>
    </div>

    <!-- FEATURED PRODUCTS SHOWCASE -->
    <section style="margin: 70px 0;">
        <div class="section-header">
            <div class="badge badge-orange" style="margin-bottom: 12px;">LINHA EXCLUSIVA</div>
            <h2 class="titleAI">NOSSOS INSTRUMENTOS <span class="gradientAI">INTELIGENTES</span></h2>
            <p class="subtitleAI">Escolha seu instrumento e sinta a sinergia perfeita entre madeiras nobres e chips neurais de última geração.</p>
        </div>

        <div class="products-grid">
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

                        <div class="product-footer">
                            <div class="price-box">
                                <?php if (!empty($prod['old_price'])): ?>
                                    <span class="old-price">R$ <?= number_format($prod['old_price'], 2, ',', '.') ?></span>
                                <?php endif; ?>
                                <span class="current-price">R$ <?= number_format($prod['price'], 2, ',', '.') ?></span>
                            </div>

                            <div class="product-actions">
                                <!-- Add to Cart Form -->
                                <form method="POST" action="<?= base_url('carrinho/adicionar') ?>">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="product_id" value="<?= esc($prod['id']) ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn-icon" title="Adicionar ao Carrinho" aria-label="Adicionar ao Carrinho">
                                        <span class="material-symbols-outlined">add_shopping_cart</span>
                                    </button>
                                </form>

                                <!-- Direct Buy Now Form -->
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
        </div>
    </section>

    <!-- GUITAIR ECOSYSTEM BANNER -->
    <div class="campaign-card" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.12) 0%, rgba(24, 27, 40, 0.85) 100%); border-color: rgba(139, 92, 246, 0.35);">
        <div class="campaign-content">
            <div class="campaign-badge" style="background: rgba(139, 92, 246, 0.2); border-color: rgba(139, 92, 246, 0.4); color: #c4b5fd;">
                <span class="material-symbols-outlined" style="font-size: 16px;">phone_iphone</span>
                ECOSSISTEMA CONECTADO
            </div>
            <h2 class="campaign-title">Aplicativo <span class="gradientCyan">GuitAIr Studio</span></h2>
            <p class="campaign-desc">
                Sincronize seu instrumento via Bluetooth 5.3 de baixíssima latência. Baixe novos timbres analógicos simulados por redes neurais, visualize tablaturas interativas com guia luminoso e acompanhe sua evolução técnica em tempo real.
            </p>
            <div style="display: flex; gap: 14px; flex-wrap: wrap;">
                <a href="<?= base_url('sobre') ?>" class="btn btn-primary btn-sm">
                    <span class="material-symbols-outlined">explore</span>
                    Conhecer a Tecnologia
                </a>
                <a href="<?= base_url('produtos') ?>" class="btn btn-secondary btn-sm">
                    <span class="material-symbols-outlined">shopping_cart</span>
                    Ver Instrumentos
                </a>
            </div>
        </div>
        <div class="campaign-image-wrap">
            <img src="<?= base_url('img/banner_guitair.png') ?>" alt="GuitAIr App Ecosystem" style="border-radius: var(--radius-lg); max-height: 280px; object-fit: cover;" loading="lazy">
        </div>
    </div>

</div>

<?= view('templates/footer') ?>
