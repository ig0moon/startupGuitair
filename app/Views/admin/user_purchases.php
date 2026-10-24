<?= view('templates/header', ['pageTitle' => 'GuitAIr Admin - Produtos Comprados por Usuário']) ?>

<div class="container">

    <!-- Admin Header & Tabs -->
    <div class="admin-header">
        <div>
            <div class="badge badge-cyan" style="margin-bottom: 8px;">RELATÓRIO DE COMPRAS</div>
            <h1 class="titleAI" style="text-align: left; margin: 0;">PRODUTOS COMPRADOS <span class="gradientCyan">POR USUÁRIO</span></h1>
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
        <a href="<?= base_url('admin/compras-usuarios') ?>" class="admin-tab active">
            <span class="material-symbols-outlined">shopping_basket</span>
            Produtos Comprados por Usuário
        </a>
        <a href="<?= base_url('admin/pedidos') ?>" class="admin-tab">
            <span class="material-symbols-outlined">receipt_long</span>
            Todos os Pedidos
        </a>
    </div>

    <!-- User Search Filter Bar -->
    <div class="glass-card" style="padding: 20px 24px; margin-bottom: 28px;">
        <form method="GET" action="<?= base_url('admin/compras-usuarios') ?>" class="admin-filter-form" style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
            <div style="font-weight: 600; font-size: 0.95rem; display: flex; align-items: center; gap: 6px; color: var(--text-primary); white-space: nowrap;">
                <span class="material-symbols-outlined" style="color: var(--brand-cyan);">search</span>
                Pesquisar Cliente:
            </div>
            
            <div class="search-input-wrapper" style="position: relative; flex: 1; min-width: 260px; max-width: 480px;">
                <input type="text" 
                       name="busca" 
                       id="filterSearchInput" 
                       value="<?= esc($searchQuery ?? '') ?>" 
                       placeholder="Digite o nome, e-mail ou número do pedido..." 
                       style="width: 100%; padding: 10px 16px 10px 42px; background: #0c0e16; border: 1px solid var(--border-subtle); border-radius: var(--radius-full); color: #fff; font-size: 0.92rem; transition: all var(--transition-base);"
                       autocomplete="off">
                <span class="material-symbols-outlined" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 19px; color: var(--text-muted); pointer-events: none;">person_search</span>
            </div>

            <button type="submit" class="btn btn-primary btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
                <span class="material-symbols-outlined" style="font-size: 18px;">search</span>
                Pesquisar
            </button>

            <?php if (!empty($searchQuery)): ?>
                <a href="<?= base_url('admin/compras-usuarios') ?>" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
                    <span class="material-symbols-outlined" style="font-size: 16px;">close</span>
                    Limpar
                </a>
            <?php endif; ?>

            <div style="margin-left: auto; color: var(--text-muted); font-size: 0.88rem;">
                Total de itens encontrados: <strong style="color: #fff;"><?= $totalRecords ?></strong>
            </div>
        </form>
    </div>

    <!-- User Purchases Groups -->
    <?php if (!empty($groupedByUser)): ?>
        <?php foreach ($groupedByUser as $group): ?>
            <div class="user-purchase-card">
                <!-- Group Header -->
                <div class="user-purchase-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-subtle); padding-bottom: 18px; margin-bottom: 20px; flex-wrap: wrap; gap: 16px;">
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div class="user-avatar-circle">
                            <?= esc(strtoupper(substr($group['user_name'], 0, 1))) ?>
                        </div>
                        <div>
                            <div style="font-size: 1.2rem; font-weight: 800; color: #fff; display: flex; align-items: center; gap: 8px;">
                                <?= esc($group['user_name']) ?>
                                <span style="font-size: 0.75rem; font-weight: 600; color: var(--text-muted); background: rgba(255,255,255,0.06); padding: 2px 8px; border-radius: 4px;">ID #<?= esc($group['user_id']) ?></span>
                            </div>
                            <div style="color: var(--text-secondary); font-size: 0.88rem; margin-top: 2px; display: flex; align-items: center; gap: 6px;">
                                <span class="material-symbols-outlined" style="font-size: 16px; color: var(--text-muted);">mail</span>
                                <?= esc($group['user_email']) ?>
                            </div>
                        </div>
                    </div>

                    <div class="total-spent-box" style="text-align: right;">
                        <span style="color: var(--text-muted); font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">Total Investido:</span>
                        <div style="font-family: var(--font-code); font-size: 1.35rem; font-weight: 800; color: var(--brand-emerald); text-shadow: 0 0 20px rgba(16, 185, 129, 0.3);">
                            R$ <?= number_format($group['total_spent'], 2, ',', '.') ?>
                        </div>
                    </div>
                </div>

                <!-- Products Table for this user -->
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>PRODUTO / INSTRUMENTO</th>
                                <th>NÚMERO DO PEDIDO</th>
                                <th>DATA DA COMPRA</th>
                                <th>VALOR UNITÁRIO</th>
                                <th style="text-align: center;">QTD</th>
                                <th>SUBTOTAL</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($group['items'] as $item): ?>
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 14px;">
                                            <div style="width: 44px; height: 44px; background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-subtle); border-radius: 8px; padding: 4px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                <img src="<?= base_url($item['product_image'] ?? 'img/violao.png') ?>" alt="" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                            </div>
                                            <div>
                                                <strong style="color: #fff; font-size: 0.95rem; display: block;"><?= esc($item['product_name']) ?></strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="order-tag">
                                            #<?= esc($item['order_number']) ?>
                                        </span>
                                    </td>
                                    <td style="color: var(--text-secondary); font-size: 0.88rem;">
                                        <?= date('d/m/Y H:i', strtotime($item['order_date'])) ?>
                                    </td>
                                    <td style="font-family: var(--font-code); font-weight: 600; color: var(--text-secondary);">
                                        R$ <?= number_format($item['price'], 2, ',', '.') ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <span style="display: inline-block; padding: 3px 10px; background: rgba(255, 255, 255, 0.06); border-radius: 6px; font-family: var(--font-code); font-weight: 700; font-size: 0.85rem;">
                                            <?= esc($item['quantity']) ?>x
                                        </span>
                                    </td>
                                    <td style="font-family: var(--font-code); font-weight: 800; color: var(--brand-emerald); font-size: 1rem;">
                                        R$ <?= number_format($item['subtotal'], 2, ',', '.') ?>
                                    </td>
                                    <td>
                                        <?= order_status_badge($item['order_status']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="glass-card" style="text-align: center; padding: 60px 20px;">
            <span class="material-symbols-outlined" style="font-size: 48px; color: var(--text-muted); margin-bottom: 12px;">inventory_2</span>
            <h3>Nenhuma compra encontrada para os filtros selecionados.</h3>
            <p style="color: var(--text-secondary); margin-top: 6px;">Assim que um pedido for finalizado na loja, os itens aparecerão detalhados aqui em tempo real.</p>
        </div>
    <?php endif; ?>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('filterSearchInput');
    const userCards = document.querySelectorAll('.user-purchase-card');
    
    if (searchInput && userCards.length > 0) {
        searchInput.addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase().trim();
            userCards.forEach(card => {
                const text = card.textContent.toLowerCase();
                if (term === '' || text.includes(term)) {
                    card.style.display = '';
                    card.style.opacity = '1';
                } else {
                    card.style.display = 'none';
                    card.style.opacity = '0';
                }
            });
        });
    }
});
</script>

<?= view('templates/footer') ?>
