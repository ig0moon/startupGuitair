<?= view('templates/header', ['pageTitle' => 'GuitAIr - Minha Conta']) ?>

<div class="container container-narrow">

    <div class="glass-card" style="margin: 30px auto; max-width: 520px;">
        
        <!-- Tab Selector: Entrar vs Cadastrar -->
        <div class="auth-tabs">
            <button type="button" class="auth-tab-btn active" data-target="loginPanel">
                <span class="material-symbols-outlined" style="font-size: 20px;">login</span>
                Entrar
            </button>
            <button type="button" class="auth-tab-btn" data-target="registerPanel">
                <span class="material-symbols-outlined" style="font-size: 20px;">person_add</span>
                Criar Conta
            </button>
        </div>

        <!-- 1. LOGIN FORM PANEL -->
        <div class="auth-form-panel" id="loginPanel">
            <div style="text-align: center; margin-bottom: 24px;">
                <h2 style="font-size: 1.5rem; margin-bottom: 6px;">BEM-VINDO DE VOLTA</h2>
                <p style="color: var(--text-secondary); font-size: 0.92rem;">Acesse sua conta para gerenciar seus pedidos e firmware.</p>
            </div>

            <form method="POST" action="<?= base_url('conta/login') ?>">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label class="form-label" for="loginEmail">E-mail ou Usuário</label>
                    <div style="position: relative;">
                        <input type="email" name="email" id="loginEmail" placeholder="seuemail@exemplo.com" required style="padding-left: 44px;">
                        <span class="material-symbols-outlined" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 20px;">mail</span>
                    </div>
                </div>

                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <label class="form-label" for="loginPassword" style="margin: 0;">Sua Senha</label>
                        <a href="<?= base_url('ajuda') ?>" style="color: var(--brand-primary); font-size: 0.82rem;">Esqueceu a senha?</a>
                    </div>
                    <div style="position: relative;">
                        <input type="password" name="senha" id="loginPassword" placeholder="••••••••" required style="padding-left: 44px; padding-right: 44px;">
                        <span class="material-symbols-outlined" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 20px;">lock</span>
                        <span class="material-symbols-outlined toggle-password-btn" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 20px; cursor: pointer;">visibility</span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top: 24px;">
                    <span class="material-symbols-outlined">login</span>
                    Entrar na Conta
                </button>
            </form>

            <!-- Demo helper shortcut -->
            <div style="margin-top: 28px; padding-top: 20px; border-top: 1px solid var(--border-subtle); text-align: center;">
                <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 10px;">
                    Quer testar sem precisar se cadastrar?
                </p>
                <button type="button" class="btn btn-secondary btn-sm" id="fillDemoBtn">
                    <span class="material-symbols-outlined" style="font-size: 18px; color: var(--brand-primary);">bolt</span>
                    Usar Credenciais de Teste
                </button>
            </div>
        </div>

        <!-- 2. REGISTER FORM PANEL -->
        <div class="auth-form-panel" id="registerPanel" style="display: none;">
            <div style="text-align: center; margin-bottom: 24px;">
                <h2 style="font-size: 1.5rem; margin-bottom: 6px;">CRIE SUA CONTA</h2>
                <p style="color: var(--text-secondary); font-size: 0.92rem;">Junte-se à comunidade GuitAIr de música inteligente.</p>
            </div>

            <form method="POST" action="<?= base_url('conta/cadastro') ?>">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label class="form-label" for="regName">Nome Completo *</label>
                    <div style="position: relative;">
                        <input type="text" name="nome" id="regName" placeholder="Seu Nome e Sobrenome" required style="padding-left: 44px;">
                        <span class="material-symbols-outlined" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 20px;">badge</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="regEmail">E-mail *</label>
                    <div style="position: relative;">
                        <input type="email" name="email" id="regEmail" placeholder="seuemail@exemplo.com" required style="padding-left: 44px;">
                        <span class="material-symbols-outlined" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 20px;">mail</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="regPhone">WhatsApp / Telefone</label>
                    <div style="position: relative;">
                        <input type="text" name="telefone" id="regPhone" placeholder="(11) 98765-4321" style="padding-left: 44px;">
                        <span class="material-symbols-outlined" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 20px;">call</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="regPassword">Crie uma Senha * (mínimo 6 caracteres)</label>
                    <div style="position: relative;">
                        <input type="password" name="senha" id="regPassword" placeholder="••••••••" required minlength="6" style="padding-left: 44px; padding-right: 44px;">
                        <span class="material-symbols-outlined" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 20px;">lock</span>
                        <span class="material-symbols-outlined toggle-password-btn" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 20px; cursor: pointer;">visibility</span>
                    </div>
                </div>

                <div class="form-group" style="display: flex; align-items: flex-start; gap: 8px;">
                    <input type="checkbox" id="termsCheck" required style="width: auto; margin-top: 4px;">
                    <label for="termsCheck" style="font-size: 0.85rem; color: var(--text-secondary);">
                        Concordo com os Termos de Uso e Política de Privacidade da GuitAIr.
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top: 14px;">
                    <span class="material-symbols-outlined">person_add</span>
                    Finalizar Cadastro
                </button>
            </form>
        </div>

    </div>

</div>

<?= view('templates/footer') ?>
