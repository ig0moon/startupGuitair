<?= view('templates/header', ['pageTitle' => 'GuitAIr - Central de Ajuda & Dúvidas']) ?>

<div class="container">

    <div class="section-header" style="margin-top: 20px;">
        <span class="badge badge-orange" style="margin-bottom: 12px;">SUPORTE & PERGUNTAS FREQUENTES</span>
        <h1 class="titleAI">CENTRAL DE <span class="gradientAI">AJUDA</span></h1>
        <p class="subtitleAI">Encontre respostas rápidas para as dúvidas mais frequentes sobre nossa tecnologia, entrega, garantia e suporte técnico.</p>
    </div>

    <!-- Live Search Input -->
    <div class="faq-search-box">
        <span class="material-symbols-outlined faq-search-icon">search</span>
        <input type="text" class="faq-search-input" id="faqSearchInput" placeholder="Digite sua dúvida (ex: IA, afinação, entrega, garantia)..." autocomplete="off">
    </div>

    <!-- FAQ Accordion List -->
    <div class="faq-list">
        <?php foreach ($faqs as $index => $faq): ?>
            <div class="faq-item <?= ($index === 0) ? 'active' : '' ?>">
                <div class="faq-question">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <span class="badge badge-orange" style="font-size: 0.72rem; padding: 2px 8px;"><?= esc($faq['category']) ?></span>
                        <span><?= esc($faq['question']) ?></span>
                    </div>
                    <span class="material-symbols-outlined faq-chevron">expand_more</span>
                </div>
                <div class="faq-answer">
                    <p><?= esc($faq['answer']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Still Have Questions? Contact Box -->
    <div class="glass-card" style="margin: 60px auto 20px; max-width: 860px; text-align: center; padding: 40px 30px; border-color: rgba(255, 107, 43, 0.3);">
        <div style="width: 64px; height: 64px; border-radius: 50%; background: rgba(255, 107, 43, 0.12); color: var(--brand-primary); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
            <span class="material-symbols-outlined" style="font-size: 32px;">support_agent</span>
        </div>
        <h2 style="font-size: 1.6rem; margin-bottom: 8px;">Ainda precisa de auxílio?</h2>
        <p style="color: var(--text-secondary); max-width: 500px; margin: 0 auto 24px; font-size: 0.98rem;">
            Nossos especialistas em lutheria e engenheiros de som estão disponíveis de Segunda a Sexta, das 09h às 18h.
        </p>

        <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
            <a href="mailto:suporte@guitair.com.br" class="btn btn-primary">
                <span class="material-symbols-outlined">mail</span>
                suporte@guitair.com.br
            </a>
            <a href="<?= whatsapp_link() ?>" target="_blank" class="btn btn-secondary btn-whatsapp" style="border-color: rgba(16, 185, 129, 0.4); color: #34d399; display: inline-flex; align-items: center; gap: 8px;">
                <?= whatsapp_svg(20) ?>
                Falar pelo WhatsApp (42) 99807-2016
            </a>
        </div>
    </div>

</div>

<?= view('templates/footer') ?>
