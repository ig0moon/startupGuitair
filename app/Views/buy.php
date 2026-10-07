<?= view('templates/header', ['pageTitle' => 'GuitAIr - Compra']) ?>

<script type="text/javascript" src="<?= base_url('js/jquery-4.0.0.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('js/buy.js') ?>"></script>

<br>

<div class="buyprod">
    <form class="buyform" method="POST" action="<?= base_url('comprar') ?>">
        <?= csrf_field() ?>
        <fieldset class="buy">
            <legend>Compre aqui seu produto GuitAIr!</legend>
            <?php if (!empty($modelo)): ?>
                <div style="padding: 12px; margin: 10px 0; background: rgba(244, 128, 36, 0.15); border-left: 4px solid #f48024; border-radius: 4px;">
                    <strong>Instrumento Selecionado:</strong> <?= esc(ucfirst($modelo)) ?>
                    <input type="hidden" name="modelo" value="<?= esc($modelo) ?>">
                </div>
            <?php endif; ?>
        </fieldset>

        <fieldset>
            <legend>Preencha suas informações</legend>

            <label for="nm">Nome:</label><br>
            <input type="text" name="nome" id="nm" autocomplete="off" placeholder="Digite seu nome e sobrenome..." required><br>
            <br>

            <label for="em">E-mail:</label><br>
            <input type="email" name="email" id="em" autocomplete="off" placeholder="Digite um e-mail válido..." required><br>
            <br>

            <label for="sx">Sexo:</label><br>
            <select id="sx" name="sexo">
                <option disabled selected>Escolha:</option>
                <option value="masc">Masculino</option>
                <option value="fem">Feminino</option>
                <option value="sxother">Outro</option>
            </select>
            <br><br>

            <div style="text-align: center; margin-top: 15px;">
                <button type="submit" class="botao" style="padding: 10px 25px; cursor: pointer;">Finalizar Pedido</button>
            </div>
        </fieldset>
    </form>
</div>

<?= view('templates/footer') ?>
