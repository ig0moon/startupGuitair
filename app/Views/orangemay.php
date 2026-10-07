<?= view('templates/header', ['pageTitle' => 'GuitAIr - Maio Laranja']) ?>

<br>

<div class="pageconscient">
    <h3><span class="conscient">Maio Laranja</span></h3>
    <br>

    <img src="<?= base_url('img/orangemay.png') ?>" alt="Maio Laranja">

    <br>
    <br>

    <p>A campanha Maio Laranja tem como principal objetivo conscientizar a sociedade sobre o combate ao abuso e à exploração 
        sexual de crianças e adolescentes. Durante todo o mês de maio, são realizadas ações educativas, palestras e campanhas 
        informativas para incentivar a proteção da infância e fortalecer a importância da denúncia.

        A data reforça que crianças e adolescentes devem crescer em um ambiente seguro, com respeito, cuidado e proteção. A 
        participação da família, da escola e da sociedade é fundamental para identificar sinais de violência e garantir apoio às vítimas.

        Denunciar é um ato de responsabilidade e proteção. Casos suspeitos podem ser comunicados de forma anônima pelo Disque 100. 
        Juntos, podemos ajudar a combater esse tipo de violência e garantir um futuro mais seguro para todos.</p>

    <br>
    <br>

    <div class="formconsc">
        <form method="POST" action="<?= base_url('maio-laranja') ?>">
            <?= csrf_field() ?>
            <label>Nome:</label><br>
            <input class="texto" type="text" name="nome" required><br>
            <br>

            <label>Email:</label><br>
            <input class="texto" type="email" name="email" required><br>
            <br>

            <label>Idade:</label><br>
            <input class="texto" type="number" name="idade" min="1" max="120"><br>
            <br>

            <label>Você conhece a campanha Maio Laranja?</label><br>
            <br>

            <label><input type="radio" name="campanha" value="sim" checked> Sim</label>
            <label style="margin-left: 15px;"><input type="radio" name="campanha" value="nao"> Não</label>
            <br><br>

            <label>Mensagem:</label><br>
            <textarea name="mensagem"></textarea><br><br>

            <button class="botao" type="submit">Enviar</button>
        </form>
    </div>

    <br>
    <br>
    <br>
    <br>
</div>

<br>

<?= view('templates/footer') ?>
