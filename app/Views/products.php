<?= view('templates/header', ['pageTitle' => 'GuitAIr - Produtos']) ?>

<div class="container" style="padding: 40px 20px; max-width: 1200px; margin: auto;">
    <h3 class="titleAI">NOSSOS PRODUTOS</h3>
    <br><br>

    <div class="div-inst">
        <form class="instrumentos" method="POST" action="<?= base_url('comprar') ?>">
            <?= csrf_field() ?>
            <fieldset class="modelo">
                <legend>SELECIONE UM MODELO</legend>
                <input class="chkimg" type="radio" name="modelo" value="amarelo" id="amarelo" checked>
                <label for="amarelo">
                    <img src="<?= base_url('img/violao.png') ?>" alt="Violão">
                    <div class="texto-instr">
                        <h1>GuitAIr Violão</h1>
                        <p>Instrumento acústico inteligente, ideal para quem busca versatilidade, afinação assistida e feedback em tempo real com auxílio da IA.</p>
                        <br><br>
                        <div class="compr-prod">
                            <input type="submit" name="comprar" value="Comprar" id="produto">
                        </div>
                    </div>
                </label>

                <input class="chkimg" type="radio" name="modelo" value="azul" id="azul">
                <label for="azul">
                    <img src="<?= base_url('img/guitarra.png') ?>" alt="Guitarra">
                    <div class="texto-instr">
                        <h1>Electric GuitAIr</h1>
                        <p>Guitarra inteligente com sintetizador embutido e conexão direta com DAW, oferecendo timbres ilimitados e acompanhamento automatizado.</p>
                        <br><br>
                        <div class="compr-prod">
                            <input type="submit" name="comprar" value="Comprar" id="produto">
                        </div>
                    </div>
                </label>

                <input class="chkimg" type="radio" name="modelo" value="vermelho" id="vermelho">
                <label for="vermelho">
                    <img src="<?= base_url('img/bateria.png') ?>" alt="Bateria">
                    <div class="texto-instr">
                        <h1>DrumIA</h1>
                        <p>Bateria eletrônica responsiva e dinâmica com auxílio de aprendizado rítmico acelerado por inteligência artificial.</p>
                        <br><br>
                        <div class="compr-prod">
                            <input type="submit" name="comprar" value="Comprar" id="produto">
                        </div>
                    </div>
                </label>
            </fieldset>
        </form>
    </div>
</div>

<?= view('templates/footer') ?>
