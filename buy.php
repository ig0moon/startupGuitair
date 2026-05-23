<?php
$pageTitle = 'GuitAIr - Produto';
include 'inc/header.inc.php'; 
?>

<script type="text/javascript" src="js/jquery-4.0.0.min.js"></script>
<script type="text/javascript" src="js/buy.js"></script>

<br>

<div class="buyprod">
    <form class="buyform">
        <fieldset class="buy">
            <legend>Compre aqui seu produto GuitAIr!</legend>
        </fieldset>

        <fieldset>
            <legend>Preencha suas informações</legend>

            <label>Nome:</label><br>
            <input type="text" name="nome" id="nm" autocomplete="off" placeholder="Digite seu nome e sobrenome..." required ><br>
            <br>

            <label>E-mail:</label><br>
            <input type="mail" name="email" id="em" autocomplete="off" placeholder="Digite um e-mail válido..." required><br>
            <br>

            <label>Sexo:</label><br>
                <select id="sx" name="sexo">
                    <option disabled selected>Escolha:</option>
                    <option value="masc">Masculino</option>
                    <option value="fem">Feminino</option>
                    <option value="sxother">Outro:</option>
                </select>
        </fieldset>
    </form>
</div>





<?php
include 'inc/footer.inc.php';
?>