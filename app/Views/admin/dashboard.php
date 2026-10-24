<?= view('templates/header', ['pageTitle' => 'GuitAIr - Painel Administrativo']) ?>

<div class="container">

    <!-- Admin Header & Tabs -->
    <div class="admin-header">
        <div>
            <div class="badge badge-cyan" style="margin-bottom: 8px;">ADMINISTRAÇÃO MYSQL</div>
            <h1 class="titleAI" style="text-align: left; margin: 0;">PAINEL DE <span class="gradientCyan">CONTROLE</span></h1>
        </div>

        <div style="display: flex; gap: 10px;">
            <a href="<?= base_url('conta') ?>" class="btn btn-secondary btn-sm">
                <span class="material-symbols-outlined">arrow_back</span>
                Meu Painel de Usuário
            </a>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="admin-nav-tabs">
        <a href="<?= base_url('admin') ?>" class="admin-tab active">
            <span class="material-symbols-outlined">manage_accounts</span>
            Gestão de Usuários & Acessos
        </a>
        <a href="<?= base_url('admin/compras-usuarios') ?>" class="admin-tab">
            <span class="material-symbols-outlined">shopping_basket</span>
            Produtos Comprados por Usuário
        </a>
        <a href="<?= base_url('admin/pedidos') ?>" class="admin-tab">
            <span class="material-symbols-outlined">receipt_long</span>
            Todos os Pedidos
        </a>
    </div>

    <!-- Stat Highlights -->
    <div class="features-grid" style="margin: 0 0 32px 0;">
        <div class="feature-box">
            <div class="feature-icon" style="color: var(--brand-cyan); background: rgba(6, 182, 212, 0.12); border-color: rgba(6, 182, 212, 0.3);">
                <span class="material-symbols-outlined">group</span>
            </div>
            <div style="font-family: var(--font-code); font-size: 1.8rem; font-weight: 700; color: var(--brand-cyan);">
                <?= $totalUsers ?>
            </div>
            <h3 style="font-size: 1.05rem; margin-top: 4px;">Usuários Cadastrados</h3>
        </div>

        <div class="feature-box">
            <div class="feature-icon">
                <span class="material-symbols-outlined">receipt_long</span>
            </div>
            <div style="font-family: var(--font-code); font-size: 1.8rem; font-weight: 700; color: var(--brand-primary);">
                <?= $totalOrders ?>
            </div>
            <h3 style="font-size: 1.05rem; margin-top: 4px;">Total de Pedidos</h3>
        </div>

        <div class="feature-box">
            <div class="feature-icon" style="color: var(--brand-emerald); background: rgba(16, 185, 129, 0.12); border-color: rgba(16, 185, 129, 0.3);">
                <span class="material-symbols-outlined">payments</span>
            </div>
            <div style="font-family: var(--font-code); font-size: 1.8rem; font-weight: 700; color: var(--brand-emerald);">
                R$ <?= number_format($totalRevenue, 2, ',', '.') ?>
            </div>
            <h3 style="font-size: 1.05rem; margin-top: 4px;">Faturamento Total</h3>
        </div>
    </div>

    <!-- User Access Management (Gerenciar quem tem acesso pelo banco) -->
    <div class="glass-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
            <div>
                <h2 style="font-size: 1.3rem; display: flex; align-items: center; gap: 10px;">
                    <span class="material-symbols-outlined" style="color: var(--brand-primary);">security</span>
                    Gerenciamento de Acessos & Permissões (MySQL)
                </h2>
                <p style="color: var(--text-secondary); font-size: 0.88rem; margin-top: 4px;">
                    Altere quem possui acesso ao Painel Administrativo ou rebaixe para Usuário Comum com 1 clique. As alterações são gravadas no MySQL via <strong>Query Builder</strong>.
                </p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">ID</th>
                        <th>USUÁRIO</th>
                        <th>FUNÇÃO NO SISTEMA</th>
                        <th>CADASTRO</th>
                        <th style="text-align: right;">AÇÃO NO BANCO (QUERY BUILDER)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td>
                                <span style="font-family: var(--font-code); font-weight: 700; color: var(--text-muted); background: rgba(255, 255, 255, 0.05); padding: 4px 10px; border-radius: 6px; font-size: 0.82rem;">
                                    #<?= esc($u['id']) ?>
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 38px; height: 38px; border-radius: 50%; background: var(--brand-gradient); display: flex; align-items: center; justify-content: center; font-weight: 700; color: #fff; font-size: 0.95rem; flex-shrink: 0; box-shadow: 0 2px 8px rgba(255, 107, 43, 0.3);">
                                        <?= esc(strtoupper(substr($u['name'], 0, 1))) ?>
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; color: #fff; font-size: 0.95rem;">
                                            <?= esc($u['name']) ?>
                                        </div>
                                        <div style="color: var(--text-muted); font-size: 0.82rem; margin-top: 2px;">
                                            <?= esc($u['email']) ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="role-badge <?= ($u['role'] === 'admin') ? 'role-admin' : 'role-user' ?>">
                                    <span class="material-symbols-outlined"><?= ($u['role'] === 'admin') ? 'shield' : 'person' ?></span>
                                    <?= ($u['role'] === 'admin') ? 'ADMINISTRADOR' : 'CLIENTE' ?>
                                </span>
                            </td>
                            <td style="color: var(--text-muted); font-size: 0.88rem;">
                                <?= date('d/m/Y', strtotime($u['created_at'] ?? 'now')) ?>
                            </td>
                            <td style="text-align: right;">
                                <form method="POST" action="<?= base_url('admin/alterar-funcao') ?>" style="display: inline-flex; align-items: center; gap: 8px;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="user_id" value="<?= esc($u['id']) ?>">
                                    
                                    <?php if ($u['role'] === 'admin'): ?>
                                        <input type="hidden" name="role" value="user">
                                        <button type="submit" class="btn btn-secondary btn-sm" style="color: #f87171; border-color: rgba(239, 68, 68, 0.35); padding: 6px 14px; font-size: 0.82rem; display: inline-flex; align-items: center; gap: 6px;">
                                            <span class="material-symbols-outlined" style="font-size: 16px;">remove_moderator</span>
                                            Rebaixar a Usuário
                                        </button>
                                    <?php else: ?>
                                        <input type="hidden" name="role" value="admin">
                                        <button type="submit" class="btn btn-secondary btn-sm" style="color: var(--brand-cyan); border-color: rgba(6, 182, 212, 0.45); padding: 6px 14px; font-size: 0.82rem; display: inline-flex; align-items: center; gap: 6px;">
                                            <span class="material-symbols-outlined" style="font-size: 16px;">add_moderator</span>
                                            Promover a Admin
                                        </button>
                                    <?php endif; ?>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?= view('templates/footer') ?>
