<?= view('templates/header', ['pageTitle' => 'GuitAIr Admin - Gestão de Produtos']) ?>

<div class="container">

    <!-- Admin Header -->
    <div class="admin-header">
        <div>
            <div class="badge badge-cyan" style="margin-bottom: 8px;">ADMINISTRAÇÃO</div>
            <h1 class="titleAI" style="text-align: left; margin: 0;">GESTÃO DE <span class="gradientCyan">PRODUTOS</span></h1>
        </div>

        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="<?= base_url('produtos') ?>" target="_blank" class="btn btn-secondary btn-sm">
                <span class="material-symbols-outlined">storefront</span>
                Ver Catálogo na Loja
            </a>
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
        <a href="<?= base_url('admin/produtos') ?>" class="admin-tab active">
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
                <span class="material-symbols-outlined">inventory_2</span>
            </div>
            <div style="font-family: var(--font-code); font-size: 1.8rem; font-weight: 700; color: var(--brand-cyan);">
                <?= $totalProducts ?>
            </div>
            <h3 style="font-size: 1.05rem; margin-top: 4px;">Produtos no Catálogo</h3>
        </div>

        <div class="feature-box">
            <div class="feature-icon">
                <span class="material-symbols-outlined">warehouse</span>
            </div>
            <div style="font-family: var(--font-code); font-size: 1.8rem; font-weight: 700; color: var(--brand-primary);">
                <?= $totalStock ?>
            </div>
            <h3 style="font-size: 1.05rem; margin-top: 4px;">Unidades em Estoque</h3>
        </div>

        <div class="feature-box">
            <div class="feature-icon" style="color: var(--brand-emerald); background: rgba(16, 185, 129, 0.12); border-color: rgba(16, 185, 129, 0.3);">
                <span class="material-symbols-outlined">star</span>
            </div>
            <div style="font-family: var(--font-code); font-size: 1.8rem; font-weight: 700; color: var(--brand-emerald);">
                <?= $featuredCount ?>
            </div>
            <h3 style="font-size: 1.05rem; margin-top: 4px;">Itens em Destaque</h3>
        </div>
    </div>

    <!-- Tabela de Produtos Cadastrados com Botão de Adição no Topo -->
    <div class="glass-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; border-bottom: 1px solid var(--border-subtle); padding-bottom: 18px;">
            <div>
                <h2 style="font-size: 1.35rem; display: flex; align-items: center; gap: 10px; margin: 0;">
                    <span class="material-symbols-outlined" style="color: var(--brand-primary);">inventory</span>
                    Produtos Cadastrados no Catálogo
                </h2>
                <p style="color: var(--text-secondary); font-size: 0.88rem; margin: 4px 0 0 0;">
                    Gerencie o estoque, preços e opções dos instrumentos musicais disponíveis na loja.
                </p>
            </div>

            <!-- Botão de Abertura do Formulário de Adição -->
            <button type="button" class="btn btn-primary" onclick="openProductModal()" id="openAddProductBtn" style="padding: 10px 24px; font-weight: 700; box-shadow: 0 4px 20px rgba(255, 107, 43, 0.45);">
                <span class="material-symbols-outlined" style="font-size: 20px;">add_circle</span>
                Adicionar Produto
            </button>
        </div>

        <?php if (!empty($products)): ?>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th style="width: 70px;">FOTO</th>
                            <th>PRODUTO & DETALHES</th>
                            <th>CATEGORIA</th>
                            <th>PREÇO</th>
                            <th>ESTOQUE</th>
                            <th>SELO</th>
                            <th style="text-align: right;">AÇÕES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $p): ?>
                            <tr>
                                <td>
                                    <span style="font-family: var(--font-code); font-weight: 700; color: var(--text-muted); background: rgba(255, 255, 255, 0.05); padding: 4px 8px; border-radius: 6px; font-size: 0.8rem;">
                                        #<?= esc($p['id'] ?? '-') ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="width: 50px; height: 50px; border-radius: var(--radius-sm); background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-subtle); display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 4px;">
                                        <img src="<?= base_url(esc($p['image'])) ?>" alt="<?= esc($p['name']) ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div style="font-weight: 700; color: #fff; font-size: 0.95rem;">
                                            <?= esc($p['name']) ?>
                                        </div>
                                        <div style="color: var(--text-muted); font-size: 0.8rem; margin-top: 2px;">
                                            /produtos/<strong><?= esc($p['slug']) ?></strong>
                                        </div>
                                        <?php if (!empty($p['tagline'])): ?>
                                            <div style="color: var(--text-secondary); font-size: 0.78rem; margin-top: 4px; max-width: 380px; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">
                                                <?= esc($p['tagline']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="role-badge" style="background: rgba(6, 182, 212, 0.12); color: var(--brand-cyan); border-color: rgba(6, 182, 212, 0.3);">
                                        <?= esc(ucfirst($p['category'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="font-family: var(--font-code); font-weight: 700; color: var(--brand-emerald); font-size: 0.95rem;">
                                        R$ <?= number_format((float)$p['price'], 2, ',', '.') ?>
                                    </div>
                                    <?php if (!empty($p['old_price'])): ?>
                                        <div style="font-family: var(--font-code); color: var(--text-muted); font-size: 0.78rem; text-decoration: line-through;">
                                            R$ <?= number_format((float)$p['old_price'], 2, ',', '.') ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php 
                                    $stk = (int)($p['stock'] ?? 0); 
                                    $stkColor = $stk > 15 ? 'var(--brand-emerald)' : ($stk > 0 ? '#f59e0b' : '#ef4444');
                                    $stkBg = $stk > 15 ? 'rgba(16, 185, 129, 0.12)' : ($stk > 0 ? 'rgba(245, 158, 11, 0.12)' : 'rgba(239, 68, 68, 0.12)');
                                    ?>
                                    <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: var(--radius-full); font-size: 0.82rem; font-weight: 600; font-family: var(--font-code); color: <?= $stkColor ?>; background: <?= $stkBg ?>;">
                                        <span class="material-symbols-outlined" style="font-size: 14px;">warehouse</span>
                                        <?= $stk ?> un.
                                    </span>
                                </td>
                                <td>
                                    <?php if (!empty($p['badge'])): ?>
                                        <span class="badge badge-primary" style="font-size: 0.75rem; padding: 3px 10px;">
                                            <?= esc($p['badge']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span style="color: var(--text-muted); font-size: 0.8rem;">—</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: inline-flex; align-items: center; gap: 6px;">
                                        <a href="<?= base_url('produtos/' . esc($p['slug'])) ?>" target="_blank" class="btn btn-secondary btn-sm" title="Ver página do produto na loja" style="padding: 6px 12px; font-size: 0.8rem;">
                                            <span class="material-symbols-outlined" style="font-size: 16px;">visibility</span>
                                            Ver
                                        </a>

                                        <?php if (!empty($p['id'])): ?>
                                            <!-- Botão Editar Produto -->
                                            <button type="button" class="btn btn-secondary btn-sm" onclick='openEditProductModal(<?= json_encode($p, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>)' title="Editar este produto" style="color: var(--brand-cyan); border-color: rgba(6, 182, 212, 0.45); padding: 6px 12px; font-size: 0.8rem;">
                                                <span class="material-symbols-outlined" style="font-size: 16px;">edit</span>
                                                Editar
                                            </button>

                                            <!-- Botão Excluir Produto -->
                                            <form method="POST" action="<?= base_url('admin/produtos/excluir') ?>" onsubmit="return confirm('Tem certeza que deseja excluir o produto &quot;<?= esc($p['name']) ?>&quot; do catálogo?');" style="display: inline-block;">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="product_id" value="<?= esc($p['id']) ?>">
                                                <button type="submit" class="btn btn-secondary btn-sm" style="color: #f87171; border-color: rgba(239, 68, 68, 0.35); padding: 6px 10px;" title="Excluir produto">
                                                    <span class="material-symbols-outlined" style="font-size: 16px;">delete</span>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 50px 20px; color: var(--text-secondary);">
                <span class="material-symbols-outlined" style="font-size: 52px; color: var(--text-muted); margin-bottom: 12px;">inventory_2</span>
                <p style="font-size: 1.05rem; margin-bottom: 16px;">Nenhum produto cadastrado no catálogo.</p>
                <button type="button" class="btn btn-primary" onclick="openProductModal()">
                    <span class="material-symbols-outlined">add_circle</span>
                    Cadastrar Primeiro Produto
                </button>
            </div>
        <?php endif; ?>
    </div>

</div>

<!-- 1. MODAL GLASSMORPHISM: Formulário de Adição de Produto -->
<div id="productModalBackdrop" style="position: fixed; inset: 0; background: rgba(5, 7, 14, 0.82); backdrop-filter: blur(10px); z-index: 9999; display: none; align-items: center; justify-content: center; padding: 20px;" onclick="handleBackdropClick(event, 'productModalBackdrop', closeProductModal)">
    <div id="productModalBox" style="background: #0f121d; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 20px; width: 100%; max-width: 880px; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 25px 70px rgba(0, 0, 0, 0.8); animation: modalIn 0.25s cubic-bezier(0.16, 1, 0.3, 1);">
        
        <!-- Modal Header -->
        <div style="padding: 20px 28px; border-bottom: 1px solid var(--border-subtle); display: flex; justify-content: space-between; align-items: center; background: rgba(255, 255, 255, 0.02);">
            <div>
                <h3 style="font-size: 1.3rem; margin: 0; display: flex; align-items: center; gap: 10px;">
                    <span class="material-symbols-outlined" style="color: var(--brand-cyan);">add_circle</span>
                    Cadastrar Novo Instrumento / Produto
                </h3>
                <p style="color: var(--text-secondary); font-size: 0.85rem; margin: 4px 0 0 0;">
                    Preencha as características para disponibilizar o produto no catálogo da loja.
                </p>
            </div>
            <button type="button" onclick="closeProductModal()" class="btn-icon" style="background: rgba(255, 255, 255, 0.05); border: 1px solid var(--border-subtle); border-radius: 50%; width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--text-muted); transition: all 0.2s;" onmouseover="this.style.color='#fff'; this.style.borderColor='rgba(255,255,255,0.3)'" onmouseout="this.style.color='var(--text-muted)'; this.style.borderColor='var(--border-subtle)'">
                <span class="material-symbols-outlined" style="font-size: 20px;">close</span>
            </button>
        </div>

        <!-- Modal Body & Footer wrapped in Form -->
        <form method="POST" action="<?= base_url('admin/produtos/salvar') ?>" enctype="multipart/form-data" id="addProductForm" style="display: flex; flex-direction: column; flex: 1; overflow: hidden; margin: 0;">
            <?= csrf_field() ?>

            <!-- Modal Body (Scrollable Form) -->
            <div style="padding: 24px 28px; overflow-y: auto; flex: 1;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px;">
                    <!-- Nome do Produto -->
                    <div class="form-group">
                        <label class="form-label" for="prodName">Nome do Produto *</label>
                        <input type="text" name="name" id="prodName" placeholder="Ex: GuitAIr Master Synth v2" required oninput="generateSlugHelper(this.value)">
                    </div>

                    <!-- Categoria -->
                    <div class="form-group">
                        <label class="form-label" for="prodCategory">Categoria *</label>
                        <select name="category" id="prodCategory" required style="width: 100%; height: 48px; border-radius: var(--radius-md); background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-subtle); color: #fff; padding: 0 16px; font-family: inherit;">
                            <option value="guitarra" style="background: #111422; color: #fff;">Guitarra Elétrica</option>
                            <option value="violao" style="background: #111422; color: #fff;">Violão Acústico</option>
                            <option value="bateria" style="background: #111422; color: #fff;">Bateria Eletrônica</option>
                            <option value="acessorio" style="background: #111422; color: #fff;">Acessórios / Áudio</option>
                            <option value="outros" style="background: #111422; color: #fff;">Outros Instrumentos</option>
                        </select>
                    </div>

                    <!-- Slug / URL Amigável -->
                    <div class="form-group">
                        <label class="form-label" for="prodSlug">Slug (URL amigável)</label>
                        <input type="text" name="slug" id="prodSlug" placeholder="ex: guitair-master-synth-v2">
                        <small style="color: var(--text-muted); font-size: 0.78rem;">Se vazio, gerado automaticamente a partir do nome.</small>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 18px; margin-top: 16px;">
                    <!-- Preço Atual -->
                    <div class="form-group">
                        <label class="form-label" for="prodPrice">Preço (R$) *</label>
                        <input type="text" name="price" id="prodPrice" placeholder="Ex: 2.499,00" required>
                    </div>

                    <!-- Preço Anterior -->
                    <div class="form-group">
                        <label class="form-label" for="prodOldPrice">Preço Antigo / Riscado (R$)</label>
                        <input type="text" name="old_price" id="prodOldPrice" placeholder="Ex: 2.999,00">
                    </div>

                    <!-- Estoque -->
                    <div class="form-group">
                        <label class="form-label" for="prodStock">Estoque Inicial *</label>
                        <input type="number" name="stock" id="prodStock" min="0" value="25" required>
                    </div>

                    <!-- Badge / Selo -->
                    <div class="form-group">
                        <label class="form-label" for="prodBadge">Selo Promocional</label>
                        <input type="text" name="badge" id="prodBadge" placeholder="Ex: Lançamento, Mais Vendido">
                    </div>
                </div>

                <!-- Tagline -->
                <div class="form-group" style="margin-top: 16px;">
                    <label class="form-label" for="prodTagline">Frase de Efeito (Tagline)</label>
                    <input type="text" name="tagline" id="prodTagline" placeholder="Ex: Guitarra neural com simulação de 100+ amplificadores e latência ultrabaixa">
                </div>

                <!-- Imagem: Upload ou Preset -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px; margin-top: 16px; background: rgba(255, 255, 255, 0.02); padding: 18px; border-radius: var(--radius-md); border: 1px dashed var(--border-subtle);">
                    <div class="form-group" style="margin: 0;">
                        <label class="form-label" for="imageFile">
                            <span class="material-symbols-outlined" style="font-size: 18px; vertical-align: middle; color: var(--brand-cyan);">upload_file</span>
                            Upload de Nova Imagem (PNG, JPG, WEBP)
                        </label>
                        <input type="file" name="image_file" id="imageFile" accept="image/*" style="padding: 10px; background: rgba(0,0,0,0.3); border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); width: 100%;">
                    </div>

                    <div class="form-group" style="margin: 0;">
                        <label class="form-label" for="presetImage">
                            <span class="material-symbols-outlined" style="font-size: 18px; vertical-align: middle; color: var(--brand-primary);">photo_library</span>
                            Ou selecione uma Imagem Existente
                        </label>
                        <select name="preset_image" id="presetImage" style="width: 100%; height: 48px; border-radius: var(--radius-md); background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-subtle); color: #fff; padding: 0 16px;">
                            <option value="img/guitarra.png" style="background: #111422; color: #fff;">Electric GuitAIr Neural (img/guitarra.png)</option>
                            <option value="img/violao.png" style="background: #111422; color: #fff;">GuitAIr Acoustic Pro (img/violao.png)</option>
                            <option value="img/bateria.png" style="background: #111422; color: #fff;">DrumIA Smart Beats (img/bateria.png)</option>
                            <option value="img/guitair.png" style="background: #111422; color: #fff;">Logo GuitAIr Labs (img/guitair.png)</option>
                        </select>
                    </div>
                </div>

                <!-- Descrição Completa -->
                <div class="form-group" style="margin-top: 16px;">
                    <label class="form-label" for="prodDescription">Descrição Completa do Produto</label>
                    <textarea name="description" id="prodDescription" rows="3" placeholder="Detalhes de sonoridade, tecnologia inteligente embarcada, aplicação para palco ou estúdio..." style="width: 100%; border-radius: var(--radius-md); background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-subtle); color: #fff; padding: 12px 16px; font-family: inherit; font-size: 0.95rem; resize: vertical;"></textarea>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px; margin-top: 16px;">
                    <!-- Destaques / Features -->
                    <div class="form-group">
                        <label class="form-label" for="prodFeatures">Diferenciais / Recursos (1 por linha)</label>
                        <textarea name="features" id="prodFeatures" rows="4" placeholder="Processador Neural DSP Quad-Core Integrado&#10;Simulação de 120+ Amplificadores e Pedais&#10;Saída Direta USB-C com Áudio 24-bit/96kHz" style="width: 100%; border-radius: var(--radius-md); background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-subtle); color: #fff; padding: 12px 16px; font-family: inherit; font-size: 0.88rem; resize: vertical;"></textarea>
                    </div>

                    <!-- Specs Técnicos -->
                    <div class="form-group">
                        <label class="form-label" for="prodSpecs">Especificações Técnicas (Chave: Valor)</label>
                        <textarea name="specs" id="prodSpecs" rows="4" placeholder="Corpo: Alder Selecionado&#10;Escala: Rosewood 24 Trastes&#10;Latência: < 1.2ms&#10;Conexões: Bluetooth 5.3 / USB-C" style="width: 100%; border-radius: var(--radius-md); background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-subtle); color: #fff; padding: 12px 16px; font-family: inherit; font-size: 0.88rem; resize: vertical;"></textarea>
                    </div>
                </div>

                <!-- Destaque na Home Checkbox -->
                <div style="display: flex; align-items: center; gap: 12px; margin-top: 18px;">
                    <input type="checkbox" name="featured" id="prodFeatured" value="1" checked style="width: 18px; height: 18px; accent-color: var(--brand-primary); cursor: pointer;">
                    <label for="prodFeatured" style="color: var(--text-primary); font-size: 0.95rem; cursor: pointer; user-select: none;">
                        Destacar este produto na vitrine principal da página inicial
                    </label>
                </div>
            </div>

            <!-- Modal Footer -->
            <div style="padding: 16px 28px; border-top: 1px solid var(--border-subtle); display: flex; justify-content: flex-end; gap: 12px; background: rgba(255, 255, 255, 0.02);">
                <button type="button" class="btn btn-secondary" onclick="closeProductModal()">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-primary" style="padding: 12px 28px; font-weight: 700; box-shadow: 0 4px 18px rgba(255, 107, 43, 0.45);">
                    <span class="material-symbols-outlined">add_circle</span>
                    Cadastrar Produto
                </button>
            </div>
        </form>

    </div>
</div>

<!-- 2. MODAL GLASSMORPHISM: Formulário de EDIÇÃO de Produto -->
<div id="editProductModalBackdrop" style="position: fixed; inset: 0; background: rgba(5, 7, 14, 0.82); backdrop-filter: blur(10px); z-index: 9999; display: none; align-items: center; justify-content: center; padding: 20px;" onclick="handleBackdropClick(event, 'editProductModalBackdrop', closeEditProductModal)">
    <div id="editProductModalBox" style="background: #0f121d; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 20px; width: 100%; max-width: 880px; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 25px 70px rgba(0, 0, 0, 0.8); animation: modalIn 0.25s cubic-bezier(0.16, 1, 0.3, 1);">
        
        <!-- Modal Header -->
        <div style="padding: 20px 28px; border-bottom: 1px solid var(--border-subtle); display: flex; justify-content: space-between; align-items: center; background: rgba(255, 255, 255, 0.02);">
            <div>
                <h3 style="font-size: 1.3rem; margin: 0; display: flex; align-items: center; gap: 10px;">
                    <span class="material-symbols-outlined" style="color: var(--brand-cyan);">edit_note</span>
                    Editar Instrumento / Produto
                </h3>
                <p style="color: var(--text-secondary); font-size: 0.85rem; margin: 4px 0 0 0;">
                    Atualize os dados e configurações do produto <strong id="editModalHeaderTitle" style="color: #fff;"></strong>.
                </p>
            </div>
            <button type="button" onclick="closeEditProductModal()" class="btn-icon" style="background: rgba(255, 255, 255, 0.05); border: 1px solid var(--border-subtle); border-radius: 50%; width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--text-muted); transition: all 0.2s;" onmouseover="this.style.color='#fff'; this.style.borderColor='rgba(255,255,255,0.3)'" onmouseout="this.style.color='var(--text-muted)'; this.style.borderColor='var(--border-subtle)'">
                <span class="material-symbols-outlined" style="font-size: 20px;">close</span>
            </button>
        </div>

        <!-- Modal Body & Footer wrapped in Form -->
        <form method="POST" action="<?= base_url('admin/produtos/atualizar') ?>" enctype="multipart/form-data" id="editProductForm" style="display: flex; flex-direction: column; flex: 1; overflow: hidden; margin: 0;">
            <?= csrf_field() ?>
            <input type="hidden" name="product_id" id="editProdId">

            <!-- Modal Body (Scrollable Form) -->
            <div style="padding: 24px 28px; overflow-y: auto; flex: 1;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px;">
                    <!-- Nome do Produto -->
                    <div class="form-group">
                        <label class="form-label" for="editProdName">Nome do Produto *</label>
                        <input type="text" name="name" id="editProdName" required>
                    </div>

                    <!-- Categoria -->
                    <div class="form-group">
                        <label class="form-label" for="editProdCategory">Categoria *</label>
                        <select name="category" id="editProdCategory" required style="width: 100%; height: 48px; border-radius: var(--radius-md); background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-subtle); color: #fff; padding: 0 16px; font-family: inherit;">
                            <option value="guitarra" style="background: #111422; color: #fff;">Guitarra Elétrica</option>
                            <option value="violao" style="background: #111422; color: #fff;">Violão Acústico</option>
                            <option value="bateria" style="background: #111422; color: #fff;">Bateria Eletrônica</option>
                            <option value="acessorio" style="background: #111422; color: #fff;">Acessórios / Áudio</option>
                            <option value="outros" style="background: #111422; color: #fff;">Outros Instrumentos</option>
                        </select>
                    </div>

                    <!-- Slug / URL Amigável -->
                    <div class="form-group">
                        <label class="form-label" for="editProdSlug">Slug (URL amigável)</label>
                        <input type="text" name="slug" id="editProdSlug">
                        <small style="color: var(--text-muted); font-size: 0.78rem;">Identificador na URL do produto.</small>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 18px; margin-top: 16px;">
                    <!-- Preço Atual -->
                    <div class="form-group">
                        <label class="form-label" for="editProdPrice">Preço (R$) *</label>
                        <input type="text" name="price" id="editProdPrice" required>
                    </div>

                    <!-- Preço Anterior -->
                    <div class="form-group">
                        <label class="form-label" for="editProdOldPrice">Preço Antigo / Riscado (R$)</label>
                        <input type="text" name="old_price" id="editProdOldPrice">
                    </div>

                    <!-- Estoque -->
                    <div class="form-group">
                        <label class="form-label" for="editProdStock">Estoque (unidades) *</label>
                        <input type="number" name="stock" id="editProdStock" min="0" required>
                    </div>

                    <!-- Badge / Selo -->
                    <div class="form-group">
                        <label class="form-label" for="editProdBadge">Selo Promocional</label>
                        <input type="text" name="badge" id="editProdBadge">
                    </div>
                </div>

                <!-- Tagline -->
                <div class="form-group" style="margin-top: 16px;">
                    <label class="form-label" for="editProdTagline">Frase de Efeito (Tagline)</label>
                    <input type="text" name="tagline" id="editProdTagline">
                </div>

                <!-- Imagem Atual & Alteração -->
                <div style="margin-top: 16px; background: rgba(255, 255, 255, 0.02); padding: 18px; border-radius: var(--radius-md); border: 1px dashed var(--border-subtle);">
                    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px;">
                        <div style="width: 60px; height: 60px; border-radius: var(--radius-sm); background: rgba(0,0,0,0.4); border: 1px solid var(--border-subtle); display: flex; align-items: center; justify-content: center; padding: 4px; overflow: hidden; flex-shrink: 0;">
                            <img id="editCurrentImagePreview" src="" alt="Imagem atual" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                        </div>
                        <div>
                            <div style="font-weight: 600; color: #fff; font-size: 0.9rem;">Imagem Atual</div>
                            <div id="editCurrentImagePath" style="color: var(--text-muted); font-size: 0.8rem; font-family: var(--font-code);"></div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px;">
                        <div class="form-group" style="margin: 0;">
                            <label class="form-label" for="editImageFile">
                                <span class="material-symbols-outlined" style="font-size: 18px; vertical-align: middle; color: var(--brand-cyan);">upload_file</span>
                                Substituir por Nova Imagem
                            </label>
                            <input type="file" name="image_file" id="editImageFile" accept="image/*" style="padding: 10px; background: rgba(0,0,0,0.3); border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); width: 100%;">
                        </div>

                        <div class="form-group" style="margin: 0;">
                            <label class="form-label" for="editPresetImage">
                                <span class="material-symbols-outlined" style="font-size: 18px; vertical-align: middle; color: var(--brand-primary);">photo_library</span>
                                Ou Trocar para Imagem Padrão
                            </label>
                            <select name="preset_image" id="editPresetImage" style="width: 100%; height: 48px; border-radius: var(--radius-md); background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-subtle); color: #fff; padding: 0 16px;">
                                <option value="keep_current">Manter a imagem atual</option>
                                <option value="img/guitarra.png" style="background: #111422; color: #fff;">Electric GuitAIr Neural (img/guitarra.png)</option>
                                <option value="img/violao.png" style="background: #111422; color: #fff;">GuitAIr Acoustic Pro (img/violao.png)</option>
                                <option value="img/bateria.png" style="background: #111422; color: #fff;">DrumIA Smart Beats (img/bateria.png)</option>
                                <option value="img/guitair.png" style="background: #111422; color: #fff;">Logo GuitAIr Labs (img/guitair.png)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Descrição Completa -->
                <div class="form-group" style="margin-top: 16px;">
                    <label class="form-label" for="editProdDescription">Descrição Completa</label>
                    <textarea name="description" id="editProdDescription" rows="3" style="width: 100%; border-radius: var(--radius-md); background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-subtle); color: #fff; padding: 12px 16px; font-family: inherit; font-size: 0.95rem; resize: vertical;"></textarea>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px; margin-top: 16px;">
                    <!-- Destaques / Features -->
                    <div class="form-group">
                        <label class="form-label" for="editProdFeatures">Diferenciais / Recursos (1 por linha)</label>
                        <textarea name="features" id="editProdFeatures" rows="4" style="width: 100%; border-radius: var(--radius-md); background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-subtle); color: #fff; padding: 12px 16px; font-family: inherit; font-size: 0.88rem; resize: vertical;"></textarea>
                    </div>

                    <!-- Specs Técnicos -->
                    <div class="form-group">
                        <label class="form-label" for="editProdSpecs">Especificações Técnicas (Chave: Valor)</label>
                        <textarea name="specs" id="editProdSpecs" rows="4" style="width: 100%; border-radius: var(--radius-md); background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-subtle); color: #fff; padding: 12px 16px; font-family: inherit; font-size: 0.88rem; resize: vertical;"></textarea>
                    </div>
                </div>

                <!-- Destaque na Home Checkbox -->
                <div style="display: flex; align-items: center; gap: 12px; margin-top: 18px;">
                    <input type="checkbox" name="featured" id="editProdFeatured" value="1" style="width: 18px; height: 18px; accent-color: var(--brand-primary); cursor: pointer;">
                    <label for="editProdFeatured" style="color: var(--text-primary); font-size: 0.95rem; cursor: pointer; user-select: none;">
                        Destacar este produto na vitrine principal da página inicial
                    </label>
                </div>
            </div>

            <!-- Modal Footer -->
            <div style="padding: 16px 28px; border-top: 1px solid var(--border-subtle); display: flex; justify-content: flex-end; gap: 12px; background: rgba(255, 255, 255, 0.02);">
                <button type="button" class="btn btn-secondary" onclick="closeEditProductModal()">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-primary" style="padding: 12px 28px; font-weight: 700; box-shadow: 0 4px 18px rgba(255, 107, 43, 0.45);">
                    <span class="material-symbols-outlined">save</span>
                    Salvar Alterações
                </button>
            </div>
        </form>

    </div>
</div>

<style>
@keyframes modalIn {
    from {
        opacity: 0;
        transform: scale(0.96) translateY(-10px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}
</style>

<script>
const baseUrl = "<?= base_url() ?>/";

/* --- MODAL DE ADIÇÃO --- */
function openProductModal() {
    const modal = document.getElementById('productModalBackdrop');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    setTimeout(() => {
        const input = document.getElementById('prodName');
        if (input) input.focus();
    }, 100);
}

function closeProductModal() {
    const modal = document.getElementById('productModalBackdrop');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

/* --- MODAL DE EDIÇÃO --- */
function openEditProductModal(product) {
    if (!product) return;

    document.getElementById('editProdId').value = product.id || '';
    document.getElementById('editModalHeaderTitle').textContent = '#' + (product.id || '') + ' - ' + (product.name || '');
    document.getElementById('editProdName').value = product.name || '';
    document.getElementById('editProdCategory').value = product.category || 'guitarra';
    document.getElementById('editProdSlug').value = product.slug || '';
    
    // Formatação de Preço
    if (product.price !== undefined && product.price !== null) {
        document.getElementById('editProdPrice').value = parseFloat(product.price).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    } else {
        document.getElementById('editProdPrice').value = '';
    }

    if (product.old_price) {
        document.getElementById('editProdOldPrice').value = parseFloat(product.old_price).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    } else {
        document.getElementById('editProdOldPrice').value = '';
    }

    document.getElementById('editProdStock').value = product.stock !== undefined ? product.stock : 10;
    document.getElementById('editProdBadge').value = product.badge || '';
    document.getElementById('editProdTagline').value = product.tagline || '';
    document.getElementById('editProdDescription').value = product.description || '';

    // Imagem Atual Preview
    const currentImg = product.image ? (product.image.startsWith('http') ? product.image : baseUrl + product.image) : '';
    document.getElementById('editCurrentImagePreview').src = currentImg;
    document.getElementById('editCurrentImagePath').textContent = product.image || 'Sem imagem definida';
    document.getElementById('editPresetImage').value = 'keep_current';
    document.getElementById('editImageFile').value = '';

    // Features
    let featuresText = '';
    if (Array.isArray(product.features)) {
        featuresText = product.features.join('\n');
    } else if (typeof product.features === 'string') {
        try {
            const parsed = JSON.parse(product.features);
            featuresText = Array.isArray(parsed) ? parsed.join('\n') : product.features;
        } catch(e) {
            featuresText = product.features;
        }
    }
    document.getElementById('editProdFeatures').value = featuresText;

    // Specs
    let specsText = '';
    if (product.specs && typeof product.specs === 'object' && !Array.isArray(product.specs)) {
        specsText = Object.entries(product.specs).map(([k, v]) => k + ': ' + v).join('\n');
    } else if (typeof product.specs === 'string') {
        try {
            const parsed = JSON.parse(product.specs);
            specsText = Object.entries(parsed).map(([k, v]) => k + ': ' + v).join('\n');
        } catch(e) {
            specsText = product.specs;
        }
    }
    document.getElementById('editProdSpecs').value = specsText;

    // Featured Checkbox
    document.getElementById('editProdFeatured').checked = (product.featured == 1 || product.featured === true);

    // Abrir Modal
    const modal = document.getElementById('editProductModalBackdrop');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    setTimeout(() => {
        const input = document.getElementById('editProdName');
        if (input) input.focus();
    }, 100);
}

function closeEditProductModal() {
    const modal = document.getElementById('editProductModalBackdrop');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

/* --- CLIQUE FORA DO MODAL --- */
function handleBackdropClick(event, backdropId, closeCallback) {
    if (event.target.id === backdropId) {
        closeCallback();
    }
}

// Fechar qualquer modal ativo com tecla ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeProductModal();
        closeEditProductModal();
    }
});

// Helper de geração de slug ao digitar nome no formulário de adição
function generateSlugHelper(name) {
    const slugInput = document.getElementById('prodSlug');
    if (!slugInput.dataset.touched) {
        let slug = name.toLowerCase()
            .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
        slugInput.placeholder = slug || 'ex: guitair-master-synth-v2';
    }
}

document.getElementById('prodSlug').addEventListener('input', function() {
    this.dataset.touched = "true";
});

<?php if (session()->getFlashdata('error')): ?>
// Se houve erro de validação ao enviar novo produto, reabre o modal de adição
document.addEventListener('DOMContentLoaded', function() {
    openProductModal();
});
<?php endif; ?>
</script>

<?= view('templates/footer') ?>