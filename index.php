<?php
include 'inc/header.inc.php'; 
?>

<div class="container">

    <div class="banner">
        <img src="./img/banner_guitair.png" alt="Banner GuitAIr">
        <img src="./img/banner_guitair2.png" alt="Banner GuitAIr">
    </div>

    <br>

    <h3 class="titleAI">BEM-VINDO AO GUIT<span class="gradientAI">AI</span>R</h3>


    <form class="instrumentos" method="POST" action="sub.php">
        <fieldset class="modelo">
            <legend>Conheça o GuitAIr</legend>
            <input class="chkimg" type="radio" name="modelo" value="preto" id="preto" checked>
            <label for="preto"><img src="img/violao.png" alt="Violão"></label>

            <input class="chkimg" type="radio" name="modelo" value="preto" id="azul" checked>
            <label for="azul"><img src="img/guitarra.png" alt="Guitarra"></label>

            <input class="chkimg" type="radio" name="modelo" value="preto" id="vermelho" checked>
            <label for="vermelho"><img src="img/bateria.png" alt="Bateria"></label>
        </fieldset>
    </form>

    <br>
    <br>

    <div class="container-content">

    </div>

</div>

<?php
include 'inc/footer.inc.php';
?>