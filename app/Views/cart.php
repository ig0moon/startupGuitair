<?= view('templates/header', ['pageTitle' => 'GuitAIr - Carrinho de Compras']) ?>

<div class="container" style="max-width: 800px; margin: 60px auto; padding: 30px; text-align: center;">
    <h3 class="titleAI">SEU <span class="gradientAI">CARRINHO</span></h3>
    <br><br>

    <div style="background-color: #1a1a24; border: 1px solid #2a2a3a; border-radius: 12px; padding: 40px;">
        <span class="material-symbols-outlined" style="font-size: 64px; color: #f48024; margin-bottom: 20px;">shopping_cart</span>
        <h2 style="margin-bottom: 15px;">Seu carrinho está vazio no momento.</h2>
        <p style="color: #aaa; margin-bottom: 30px;">Explore nossos instrumentos inteligentes e inicie sua jornada musical com IA!</p>
        <a href="<?= base_url('produtos') ?>" style="display: inline-block; padding: 12px 30px; background: #f48024; color: white; text-decoration: none; border-radius: 6px; font-weight: bold;">Ver Produtos</a>
    </div>
</div>

<?= view('templates/footer') ?>
