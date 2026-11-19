<?= view('templates/header', ['pageTitle' => $pageTitle ?? 'GuitAIr Admin - Gestão de Categorias']) ?>

<div class="container">

    <!-- Admin Header -->
    <div class="admin-header">
        <div>
            <div class="badge badge-cyan" style="margin-bottom: 8px;">ADMINISTRAÇÃO</div>
            <h1 class="titleAI" style="text-align: left; margin: 0;">GESTÃO DE <span class="gradientCyan">CATEGORIAS</span></h1>
        </div>

        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
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
        <a href="<?= base_url('admin/categorias') ?>" class="admin-tab active">
            <span class="material-symbols-outlined">category</span>
            Categorias
        </a>
        <a href="<?= base_url('admin/produtos') ?>" class="admin-tab">
            <span class="material-symbols-outlined">inventory_2</span>
            Gestão de Produtos
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
                <span class="material-symbols-outlined">category</span>
            </div>
            <div style="font-family: var(--font-code); font-size: 1.8rem; font-weight: 700; color: var(--brand-cyan);">
                <?= count($categories) ?>
            </div>
            <h3 style="font-size: 1.05rem; margin-top: 4px;">Categorias Cadastradas</h3>
        </div>

        <div class="feature-box">
            <div class="feature-icon">
                <span class="material-symbols-outlined">inventory_2</span>
            </div>
            <div style="font-family: var(--font-code); font-size: 1.8rem; font-weight: 700; color: var(--brand-primary);">
                <?= (int)$totalProducts ?>
            </div>
            <h3 style="font-size: 1.05rem; margin-top: 4px;">Produtos Vinculados</h3>
        </div>
    </div>

    <div class="glass-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; border-bottom: 1px solid var(--border-subtle); padding-bottom: 18px;">
            <div>
                <h2 style="font-size: 1.35rem; display: flex; align-items: center; gap: 10px; margin: 0;">
                    <span class="material-symbols-outlined" style="color: var(--brand-primary);">category</span>
                    Categorias de Instrumentos
                </h2>
                <p style="color: var(--text-secondary); font-size: 0.88rem; margin: 4px 0 0 0;">
                    Cada produto pertence a uma categoria. Uma categoria só pode ser excluída quando não tiver produtos.
                </p>
            </div>

            <button type="button" class="btn btn-primary" onclick="openCategoryModal()" style="padding: 10px 24px; font-weight: 700; box-shadow: 0 4px 20px rgba(255, 107, 43, 0.45);">
                <span class="material-symbols-outlined" style="font-size: 20px;">add_circle</span>
                Nova Categoria
            </button>
        </div>

        <?php if (!empty($categories)): ?>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>NOME</th>
                            <th>SLUG</th>
                            <th>DESCRIÇÃO</th>
                            <th>PRODUTOS</th>
                            <th style="text-align: right;">AÇÕES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $c): ?>
                            <tr>
                                <td>
                                    <span style="font-family: var(--font-code); font-weight: 700; color: var(--text-muted); background: rgba(255, 255, 255, 0.05); padding: 4px 8px; border-radius: 6px; font-size: 0.8rem;">
                                        #<?= esc($c['id']) ?>
                                    </span>
                                </td>
                                <td style="font-weight: 700; color: #fff;"><?= esc($c['name']) ?></td>
                                <td style="font-family: var(--font-code); color: var(--text-secondary); font-size: 0.85rem;"><?= esc($c['slug']) ?></td>
                                <td style="color: var(--text-secondary); font-size: 0.85rem; max-width: 320px;"><?= esc($c['description'] ?? '') ?: '—' ?></td>
                                <td>
                                    <a href="<?= base_url('admin/produtos?categoria=' . urlencode($c['slug'])) ?>" class="role-badge" style="background: rgba(6, 182, 212, 0.12); color: var(--brand-cyan); border-color: rgba(6, 182, 212, 0.3); text-decoration: none;" title="Ver produtos desta categoria">
                                        <?= (int)$c['product_count'] ?> produto(s)
                                    </a>
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: inline-flex; align-items: center; gap: 6px;">
                                        <button type="button" class="btn btn-secondary btn-sm"
                                                onclick='openCategoryModal(<?= json_encode($c, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_UNESCAPED_UNICODE) ?>)'
                                                style="color: var(--brand-cyan); border-color: rgba(6, 182, 212, 0.45); padding: 6px 12px; font-size: 0.8rem;">
                                            <span class="material-symbols-outlined" style="font-size: 16px;">edit</span>
                                            Editar
                                        </button>

                                        <form method="POST" action="<?= base_url('admin/categorias/excluir') ?>" onsubmit="return confirm('Excluir a categoria &quot;<?= esc($c['name'], 'attr') ?>&quot;?');" style="display: inline-block;">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="category_id" value="<?= esc($c['id']) ?>">
                                            <button type="submit" class="btn btn-secondary btn-sm" style="color: #f87171; border-color: rgba(239, 68, 68, 0.35); padding: 6px 10px;" title="Excluir categoria">
                                                <span class="material-symbols-outlined" style="font-size: 16px;">delete</span>
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
            <div style="text-align: center; padding: 50px 20px; color: var(--text-secondary);">
                <span class="material-symbols-outlined" style="font-size: 52px; color: var(--text-muted); margin-bottom: 12px;">category</span>
                <p style="font-size: 1.05rem; margin-bottom: 16px;">Nenhuma categoria cadastrada.</p>
                <button type="button" class="btn btn-primary" onclick="openCategoryModal()">
                    <span class="material-symbols-outlined">add_circle</span>
                    Cadastrar Primeira Categoria
                </button>
            </div>
        <?php endif; ?>
    </div>

</div>

<!-- MODAL: cadastrar / editar categoria -->
<div id="categoryModalBackdrop" style="position: fixed; inset: 0; background: rgba(5, 7, 14, 0.82); backdrop-filter: blur(10px); z-index: 9999; display: none; align-items: center; justify-content: center; padding: 20px;" onclick="if (event.target === this) closeCategoryModal()">
    <div style="background: #0f121d; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 20px; width: 100%; max-width: 560px; overflow: hidden; box-shadow: 0 25px 70px rgba(0, 0, 0, 0.8);">
        <div style="padding: 20px 28px; border-bottom: 1px solid var(--border-subtle); display: flex; justify-content: space-between; align-items: center;">
            <h3 id="categoryModalTitle" style="font-size: 1.3rem; margin: 0; display: flex; align-items: center; gap: 10px;">Nova Categoria</h3>
            <button type="button" onclick="closeCategoryModal()" style="background: rgba(255, 255, 255, 0.05); border: 1px solid var(--border-subtle); border-radius: 50%; width: 38px; height: 38px; cursor: pointer; color: var(--text-muted);">
                <span class="material-symbols-outlined" style="font-size: 20px;">close</span>
            </button>
        </div>

        <form method="POST" id="categoryForm" action="<?= base_url('admin/categorias/salvar') ?>" style="margin: 0;">
            <?= csrf_field() ?>
            <input type="hidden" name="category_id" id="catId" value="">

            <div style="padding: 24px 28px;">
                <div class="form-group">
                    <label class="form-label" for="catName">Nome *</label>
                    <input type="text" name="name" id="catName" placeholder="Ex: Teclados Inteligentes" required maxlength="100">
                </div>
                <div class="form-group" style="margin-top: 16px;">
                    <label class="form-label" for="catSlug">Slug (usado na URL)</label>
                    <input type="text" name="slug" id="catSlug" placeholder="gerado a partir do nome se ficar vazio" maxlength="100">
                </div>
                <div class="form-group" style="margin-top: 16px;">
                    <label class="form-label" for="catDescription">Descrição</label>
                    <textarea name="description" id="catDescription" rows="3" maxlength="255" style="width: 100%; border-radius: var(--radius-md); background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-subtle); color: #fff; padding: 12px 16px; font-family: inherit; resize: vertical;"></textarea>
                </div>
            </div>

            <div style="padding: 16px 28px; border-top: 1px solid var(--border-subtle); display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" class="btn btn-secondary" onclick="closeCategoryModal()">Cancelar</button>
                <button type="submit" class="btn btn-primary" style="padding: 12px 28px; font-weight: 700;">
                    <span class="material-symbols-outlined">save</span>
                    Salvar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const categoryUrls = {
    create: "<?= base_url('admin/categorias/salvar') ?>",
    update: "<?= base_url('admin/categorias/atualizar') ?>"
};

// Sem argumento abre em modo "criar"; com a categoria abre em modo "editar"
function openCategoryModal(category) {
    const editing = !!category;
    document.getElementById('categoryForm').action = editing ? categoryUrls.update : categoryUrls.create;
    document.getElementById('categoryModalTitle').textContent = editing ? 'Editar Categoria #' + category.id : 'Nova Categoria';
    document.getElementById('catId').value = editing ? category.id : '';
    document.getElementById('catName').value = editing ? category.name : '';
    document.getElementById('catSlug').value = editing ? category.slug : '';
    document.getElementById('catDescription').value = editing ? (category.description || '') : '';

    document.getElementById('categoryModalBackdrop').style.display = 'flex';
    document.body.style.overflow = 'hidden';
    setTimeout(() => document.getElementById('catName').focus(), 100);
}

function closeCategoryModal() {
    document.getElementById('categoryModalBackdrop').style.display = 'none';
    document.body.style.overflow = 'auto';
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeCategoryModal(); });
</script>

<?= view('templates/footer') ?>
