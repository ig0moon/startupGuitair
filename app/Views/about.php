<?= view('templates/header', ['pageTitle' => 'GuitAIr - Sobre Nossa História e Tecnologia']) ?>

<div class="container">

    <!-- About Hero -->
    <div class="about-hero">
        <span class="badge badge-orange" style="margin-bottom: 12px;">MANIFESTO GUITAIR</span>
        <h1 class="titleAI">REVOLUCIONANDO A MÚSICA COM <br><span class="gradientAI">INTELIGÊNCIA ARTIFICIAL</span></h1>
        <p class="subtitleAI">
            Nascemos da convicção de que a tecnologia de ponta não deve substituir a sensibilidade humana, mas sim potencializar a criatividade, acelerar o aprendizado e derrubar todas as barreiras técnicas.
        </p>
    </div>

    <!-- Pillars Grid: Missão, Visão, Valores -->
    <div class="pillars-grid">
        <div class="pillar-card">
            <div class="pillar-icon">
                <span class="material-symbols-outlined">rocket_launch</span>
            </div>
            <h3>MISSÃO</h3>
            <p>
                Desenvolver instrumentos musicais inteligentes que integrem engenharia acústica de precisão e algoritmos de inteligência artificial em tempo real, tornando a expressão musical acessível, intuitiva e estimulante para qualquer pessoa no planeta.
            </p>
        </div>

        <div class="pillar-card">
            <div class="pillar-icon" style="color: var(--brand-secondary); background: rgba(139, 92, 246, 0.12); border-color: rgba(139, 92, 246, 0.3);">
                <span class="material-symbols-outlined">visibility</span>
            </div>
            <h3>VISÃO</h3>
            <p>
                Consolidar-se como a principal referência global em inovação musical aplicada, construindo um ecossistema conectado onde instrumentos inteligentes, software em nuvem e músicos evoluem juntos em perfeita harmonia.
            </p>
        </div>

        <div class="pillar-card">
            <div class="pillar-icon" style="color: var(--brand-cyan); background: rgba(6, 182, 212, 0.12); border-color: rgba(6, 182, 212, 0.3);">
                <span class="material-symbols-outlined">diamond</span>
            </div>
            <h3>VALORES</h3>
            <p style="text-align: left; max-width: 280px; margin: 0 auto;">
                &bull; <strong>Inovação Sem Limites:</strong> Desafiar o status quo.<br>
                &bull; <strong>Foco no Músico:</strong> Ergonomia e tocabilidade real.<br>
                &bull; <strong>Acessibilidade Universal:</strong> Música para todos.<br>
                &bull; <strong>Sustentabilidade:</strong> Madeiras de manejo certificado.<br>
                &bull; <strong>Transparência & Ética:</strong> IA como extensão do ser.
            </p>
        </div>
    </div>

    <!-- The GuitAIr Story & Timeline -->
    <div class="glass-card" style="margin: 60px 0; padding: 48px 36px;">
        <div style="text-align: center; max-width: 720px; margin: 0 auto 40px;">
            <span class="badge badge-purple" style="margin-bottom: 10px;">NOSSA TRAJETÓRIA</span>
            <h2 style="font-size: 2rem;">A Jornada de Criação do <span class="gradientAI">GuitAIr</span></h2>
            <p style="color: var(--text-secondary); margin-top: 10px;">
                Como transformamos uma ideia audaciosa em uma linha de instrumentos premiada internacionalmente.
            </p>
        </div>

        <div class="story-timeline">
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-year">2024 &bull; O COMEÇO</div>
                <h3 class="timeline-title">Concepção da Ideia e Primeiros Laboratórios</h3>
                <p class="timeline-text">
                    Luthieres tradicionais e engenheiros de áudio e aprendizado de máquina uniram-se em São Paulo para solucionar a maior dor de quem aprende um instrumento: a curva de frustração inicial e a falta de feedback instantâneo.
                </p>
            </div>

            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-year">2025 &bull; PATENTE & DSP</div>
                <h3 class="timeline-title">Desenvolvimento do Chip Neural Audio Engine</h3>
                <p class="timeline-text">
                    Criação do microprocessador dedicado com latência inferior a 1.2ms, capaz de reconhecer dedilhados, afinações e dinâmicas de palhetada sem depender de placas de áudio externas ou cabos pesados.
                </p>
            </div>

            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-year">2026 &bull; LANÇAMENTO GLOBAL</div>
                <h3 class="timeline-title">Lançamento Comercial e Expansão</h3>
                <p class="timeline-text">
                    Apresentação da linha completa GuitAIr Acoustic Pro, Electric GuitAIr Neural e DrumIA Smart Beats, integrando plataforma e-commerce moderna, suporte e comunidade de músicos criadores.
                </p>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div style="text-align: center; padding: 50px 20px; background: linear-gradient(135deg, rgba(255, 107, 43, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); margin-bottom: 40px;">
        <h2 style="font-size: 2rem; margin-bottom: 12px;">Pronto para transformar sua jornada musical?</h2>
        <p style="color: var(--text-secondary); max-width: 550px; margin: 0 auto 28px;">
            Conheça os modelos em estoque e experimente sem compromisso com garantia de 30 dias de satisfação.
        </p>
        <a href="<?= base_url('produtos') ?>" class="btn btn-primary btn-lg">
            <span class="material-symbols-outlined">queue_music</span>
            Conhecer Nossos Instrumentos
        </a>
    </div>

</div>

<?= view('templates/footer') ?>
