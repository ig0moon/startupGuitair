<?= view('templates/header', ['pageTitle' => 'GuitAIr - Painel do Usuário']) ?>

<div class="container">

    <!-- User Header Card -->
    <div class="glass-card" style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
        <div style="display: flex; align-items: center; gap: 20px;">
            <div style="width: 72px; height: 72px; border-radius: 50%; background: var(--brand-gradient); color: #fff; font-size: 2rem; font-weight: 700; display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-glow);">
                <?= esc(strtoupper(substr($user['name'], 0, 1))) ?>
            </div>
            <div>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px; flex-wrap: wrap;">
                    <h1 style="font-size: 1.6rem; margin: 0;"><?= esc($user['name']) ?></h1>
                    <span class="role-badge <?= ($user['role'] === 'admin') ? 'role-admin' : 'role-user' ?>">
                        <?= esc(strtoupper($user['role'] ?? 'Membro')) ?>
                    </span>
                </div>
                <div style="color: var(--text-secondary); font-size: 0.95rem;">
                    <?= esc($user['email']) ?>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            <?php if (($user['role'] ?? '') === 'admin'): ?>
                <a href="<?= base_url('admin') ?>" class="btn btn-primary btn-sm" style="background: linear-gradient(135deg, #06b6d4, #8b5cf6);">
                    <span class="material-symbols-outlined">admin_panel_settings</span>
                    Painel Administrativo
                </a>
            <?php endif; ?>

            <a href="<?= base_url('produtos') ?>" class="btn btn-secondary btn-sm">
                <span class="material-symbols-outlined">shopping_bag</span>
                Ver Catálogo
            </a>
            
            <a href="<?= base_url('conta/sair') ?>" class="btn btn-secondary btn-sm" style="color: #ef4444; border-color: rgba(239, 68, 68, 0.3);">
                <span class="material-symbols-outlined">logout</span>
                Sair
            </a>
        </div>
    </div>

    <!-- Quick Stat: Total de Pedidos -->
    <div style="display: flex; gap: 20px; margin-bottom: 32px; flex-wrap: wrap;">
        <div class="glass-card" style="flex: 1; min-width: 240px; display: flex; align-items: center; gap: 20px; padding: 20px 24px;">
            <div style="width: 52px; height: 52px; border-radius: var(--radius-md); background: rgba(255, 107, 43, 0.15); color: var(--brand-primary); display: flex; align-items: center; justify-content: center;">
                <span class="material-symbols-outlined" style="font-size: 28px;">receipt_long</span>
            </div>
            <div>
                <div style="font-family: var(--font-code); font-size: 1.8rem; font-weight: 700; color: var(--text-primary); line-height: 1;">
                    <?= count($orders) ?>
                </div>
                <div style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 4px;">Pedidos Realizados</div>
            </div>
        </div>
    </div>

    <!-- Meus Produtos Comprados -->
    <div class="glass-card" style="margin-bottom: 32px;">
        <h2 style="font-size: 1.3rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <span class="material-symbols-outlined" style="color: var(--brand-primary);">queue_music</span>
            Meus Instrumentos Adquiridos
        </h2>

        <?php 
        $hasItems = false;
        foreach ($orders as $ord) {
            if (!empty($ord['items'])) $hasItems = true;
        }
        ?>

        <?php if ($hasItems): ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 18px;">
                <?php foreach ($orders as $ord): ?>
                    <?php if (!empty($ord['items'])): ?>
                        <?php foreach ($ord['items'] as $item): ?>
                            <div style="display: flex; align-items: center; gap: 16px; padding: 16px; background: #0c0e16; border: 1px solid var(--border-subtle); border-radius: var(--radius-md);">
                                <div style="width: 60px; height: 60px; background: #151824; border-radius: 8px; display: flex; align-items: center; justify-content: center; padding: 6px; flex-shrink: 0;">
                                    <img src="<?= base_url($item['image'] ?? 'img/violao.png') ?>" alt="" style="max-height: 100%; object-fit: contain;">
                                </div>
                                <div style="flex: 1;">
                                    <div style="font-weight: 600; font-size: 0.95rem; color: #fff;"><?= esc($item['product_name']) ?></div>
                                    <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 2px;">
                                        Qtd: <?= esc($item['quantity']) ?> &bull; Pedido <span style="font-family: var(--font-code); color: var(--brand-primary); font-weight: 600;">#<?= esc($ord['order_number']) ?></span>
                                    </div>
                                    <div style="font-family: var(--font-code); font-size: 0.9rem; color: var(--brand-emerald); font-weight: 700; margin-top: 4px;">
                                        R$ <?= number_format($item['price'], 2, ',', '.') ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="color: var(--text-muted); font-size: 0.95rem;">Nenhum produto registrado em sua conta ainda.</p>
        <?php endif; ?>
    </div>

    <!-- Orders History Table -->
    <div class="glass-card" style="margin-bottom: 32px;">
        <h2 style="font-size: 1.3rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <span class="material-symbols-outlined" style="color: var(--brand-cyan);">receipt_long</span>
            Histórico Detalhado de Pedidos
        </h2>

        <?php if (!empty($orders)): ?>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>PEDIDO</th>
                            <th>DATA</th>
                            <th>PAGAMENTO</th>
                            <th>VALOR TOTAL</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $ord): ?>
                            <tr>
                                <td>
                                    <span class="order-tag">
                                        #<?= esc($ord['order_number']) ?>
                                    </span>
                                </td>
                                <td style="color: var(--text-secondary); font-size: 0.9rem;">
                                    <?= date('d/m/Y H:i', strtotime($ord['created_at'] ?? 'now')) ?>
                                </td>
                                <td>
                                    <span class="payment-badge payment-<?= strtolower(esc($ord['payment_method'] ?? 'pix')) ?>">
                                        <?= esc($ord['payment_method'] ?? 'PIX') ?>
                                    </span>
                                </td>
                                <td>
                                    <span style="font-family: var(--font-code); font-weight: 800; color: var(--brand-emerald); font-size: 1.05rem;">
                                        R$ <?= number_format($ord['total'] ?? 0, 2, ',', '.') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge status-<?= esc($ord['status'] ?? 'pending') ?>">
                                        <?= esc(ucfirst($ord['status'] ?? 'Pendente')) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 40px; color: var(--text-muted);">
                <span class="material-symbols-outlined" style="font-size: 40px; margin-bottom: 8px;">inventory_2</span>
                <p>Você ainda não realizou nenhum pedido na loja.</p>
                <br>
                <a href="<?= base_url('produtos') ?>" class="btn btn-secondary btn-sm">Explorar Instrumentos</a>
            </div>
        <?php endif; ?>
    </div>

</div>

<?= view('templates/footer') ?>
