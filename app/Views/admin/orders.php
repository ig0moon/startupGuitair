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
            <div class="table-responsive" style="margin-bottom: 0;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>PEDIDO</th>
                            <th>CLIENTE</th>
                            <th>DATA</th>
                            <th>PAGAMENTO</th>
                            <th>TOTAL</th>
                            <th>ITENS</th>
                            <th>STATUS & ATUALIZAÇÃO</th>
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
                                    <?= payment_method_badge($ord['payment_method']) ?>
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
                                    <div class="status-cell-aligned">
                                        <div class="current-status-wrapper">
                                            <?= order_status_badge($ord['status']) ?>
                                        </div>
                                        
                                        <form method="POST" action="<?= base_url('admin/alterar-status-pedido') ?>" class="status-form-aligned">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="order_id" value="<?= esc($ord['id']) ?>">
                                            <input type="hidden" name="status" class="dropdown-hidden-status" value="<?= esc($ord['status']) ?>">
                                            
                                            <!-- Custom Div Dropdown for Status -->
                                            <div class="custom-div-dropdown" data-order-id="<?= esc($ord['id']) ?>">
                                                <div class="custom-div-trigger" tabindex="0" role="button" aria-haspopup="listbox">
                                                    <span class="trigger-dot dot-<?= esc($ord['status']) ?>"></span>
                                                    <span class="trigger-label"><?= esc(order_status_label($ord['status'])) ?></span>
                                                    <span class="material-symbols-outlined trigger-chevron">expand_more</span>
                                                </div>
                                                <div class="custom-div-menu" role="listbox">
                                                    <div class="custom-div-option <?= ($ord['status'] === 'pending') ? 'selected' : '' ?>" data-val="pending" data-label="Pendente">
                                                        <span class="option-dot dot-pending"></span>
                                                        <span>Pendente</span>
                                                    </div>
                                                    <div class="custom-div-option <?= ($ord['status'] === 'paid') ? 'selected' : '' ?>" data-val="paid" data-label="Pago">
                                                        <span class="option-dot dot-paid"></span>
                                                        <span>Pago</span>
                                                    </div>
                                                    <div class="custom-div-option <?= ($ord['status'] === 'shipped') ? 'selected' : '' ?>" data-val="shipped" data-label="Enviado">
                                                        <span class="option-dot dot-shipped"></span>
                                                        <span>Enviado</span>
                                                    </div>
                                                    <div class="custom-div-option <?= ($ord['status'] === 'completed') ? 'selected' : '' ?>" data-val="completed" data-label="Concluído">
                                                        <span class="option-dot dot-completed"></span>
                                                        <span>Concluído</span>
                                                    </div>
                                                    <div class="custom-div-option <?= ($ord['status'] === 'cancelled') ? 'selected' : '' ?>" data-val="cancelled" data-label="Cancelado">
                                                        <span class="option-dot dot-cancelled"></span>
                                                        <span>Cancelado</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-secondary btn-sm btn-status-save" title="Salvar alteração no MySQL">
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
