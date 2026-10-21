<?= view('templates/header', ['pageTitle' => 'GuitAIr Admin - Todos os Pedidos']) ?>

<div class="container">

    <!-- Admin Header & Tabs -->
    <div class="admin-header">
        <div>
            <div class="badge badge-cyan" style="margin-bottom: 8px;">GESTÃO DE PEDIDOS</div>
            <h1 class="titleAI" style="text-align: left; margin: 0;">PEDIDOS <span class="gradientCyan">REALIZADOS</span></h1>
        </div>

        <div style="display: flex; gap: 10px;">
            <a href="<?= base_url('admin') ?>" class="btn btn-secondary btn-sm">
                <span class="material-symbols-outlined">arrow_back</span>
                Voltar ao Painel
            </a>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="admin-nav-tabs">
        <a href="<?= base_url('admin') ?>" class="admin-tab">
            <span class="material-symbols-outlined">manage_accounts</span>
            Gestão de Usuários & Acessos
        </a>
        <a href="<?= base_url('admin/compras-usuarios') ?>" class="admin-tab">
            <span class="material-symbols-outlined">shopping_basket</span>
            Produtos Comprados por Usuário
        </a>
        <a href="<?= base_url('admin/pedidos') ?>" class="admin-tab active">
            <span class="material-symbols-outlined">receipt_long</span>
            Todos os Pedidos
        </a>
    </div>

    <!-- Orders Table with Query Builder Status Changer -->
    <div class="glass-card">
        <h2 style="font-size: 1.3rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <span class="material-symbols-outlined" style="color: var(--brand-primary);">receipt_long</span>
            Todos os Pedidos da Loja (MySQL)
        </h2>

        <?php if (!empty($orders)): ?>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>PEDIDO</th>
                            <th>CLIENTE</th>
                            <th>DATA / HORA</th>
                            <th>PAGAMENTO</th>
                            <th>TOTAL</th>
                            <th>ITENS</th>
                            <th>STATUS ATUAL & ATUALIZAÇÃO (QUERY BUILDER)</th>
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
                                <td>
                                    <div style="font-weight: 700; color: #fff;">
                                        <?= esc($ord['customer_name']) ?>
                                    </div>
                                    <div style="color: var(--text-muted); font-size: 0.82rem; margin-top: 2px;">
                                        <?= esc($ord['customer_email']) ?>
                                    </div>
                                </td>
                                <td style="color: var(--text-secondary); font-size: 0.88rem;">
                                    <?= date('d/m/Y H:i', strtotime($ord['created_at'])) ?>
                                </td>
                                <td>
                                    <span class="payment-badge payment-<?= strtolower(esc($ord['payment_method'])) ?>">
                                        <?= esc($ord['payment_method']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span style="font-family: var(--font-code); font-weight: 800; color: var(--brand-emerald); font-size: 1.05rem;">
                                        R$ <?= number_format($ord['total'], 2, ',', '.') ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="display: flex; flex-direction: column; gap: 4px; max-width: 280px;">
                                        <?php foreach ($ord['items'] ?? [] as $it): ?>
                                            <span style="display: inline-block; padding: 2px 8px; background: rgba(255, 255, 255, 0.05); border-radius: 4px; font-size: 0.8rem; color: var(--text-secondary);">
                                                <?= esc($it['product_name']) ?> <strong style="color: #fff;">(x<?= esc($it['quantity']) ?>)</strong>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                                        <span class="status-badge status-<?= esc($ord['status']) ?>">
                                            <?= esc(ucfirst($ord['status'])) ?>
                                        </span>
                                        <form method="POST" action="<?= base_url('admin/alterar-status-pedido') ?>" style="display: inline-flex; align-items: center; gap: 6px;">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="order_id" value="<?= esc($ord['id']) ?>">
                                            <select name="status" style="padding: 5px 10px; font-size: 0.82rem; border-radius: var(--radius-sm); background: #0c0e16; border: 1px solid var(--border-subtle); color: #fff;">
                                                <option value="pending" <?= ($ord['status'] === 'pending') ? 'selected' : '' ?>>Pendente</option>
                                                <option value="paid" <?= ($ord['status'] === 'paid') ? 'selected' : '' ?>>Pago</option>
                                                <option value="shipped" <?= ($ord['status'] === 'shipped') ? 'selected' : '' ?>>Enviado</option>
                                                <option value="completed" <?= ($ord['status'] === 'completed') ? 'selected' : '' ?>>Concluído</option>
                                                <option value="cancelled" <?= ($ord['status'] === 'cancelled') ? 'selected' : '' ?>>Cancelado</option>
                                            </select>
                                            <button type="submit" class="btn btn-secondary btn-sm" style="padding: 5px 12px; font-size: 0.8rem; border-color: rgba(6, 182, 212, 0.4); color: var(--brand-cyan);">
                                                Salvar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 40px; color: var(--text-muted);">
                <p>Nenhum pedido registrado no banco de dados.</p>
            </div>
        <?php endif; ?>
    </div>

</div>

<?= view('templates/footer') ?>
