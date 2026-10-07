<?= view('templates/header', ['pageTitle' => 'GuitAIr - Minha Conta']) ?>

<div class="container" style="max-width: 600px; margin: 60px auto; padding: 30px; background-color: #1a1a24; border: 1px solid #2a2a3a; border-radius: 12px;">
    <h3 class="titleAI">MINHA <span class="gradientAI">CONTA</span></h3>
    <br><br>

    <form method="POST" action="<?= base_url('conta') ?>">
        <?= csrf_field() ?>
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 500;">E-mail ou Usuário:</label>
            <input type="text" name="usuario" placeholder="seuemail@exemplo.com" required style="width: 100%; box-sizing: border-box; padding: 12px; border-radius: 6px; border: 1px solid #3a3a4a; background: #0d0d12; color: #fff;">
        </div>

        <div style="margin-bottom: 25px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Senha:</label>
            <input type="password" name="senha" placeholder="••••••••" required style="width: 100%; box-sizing: border-box; padding: 12px; border-radius: 6px; border: 1px solid #3a3a4a; background: #0d0d12; color: #fff;">
        </div>

        <div style="text-align: center;">
            <button type="submit" class="botao" style="padding: 12px 35px; cursor: pointer; border-radius: 6px;">Entrar</button>
        </div>
    </form>
</div>

<?= view('templates/footer') ?>
