<?php
$pageTitle = 'GuitAIr - Home';
include 'inc/header.inc.php'; 
?>

<div class="container">

    <div class="banner">
        <img src="./img/banner_guitair.png" alt="Banner GuitAIr">
        <img src="./img/banner_guitair2.png" alt="Banner GuitAIr">
    </div>

    <br>

    <h3 class="titleAI">BEM-VINDO AO GUIT<span class="gradientAI">AI</span>R</h3>

    <br>

    <div class="div-inst">
        <form class="instrumentos" method="POST" action="buy.php">
            <fieldset class="modelo">
                <legend>CONHEÇA NOSSOS PRODUTOS</legend>
                <input class="chkimg" type="radio" name="modelo" value="amarelo" id="amarelo" checked>
                <label for="amarelo">
                    <img src="img/violao.png" alt="Violão">
                    <div class="texto-instr">
                        <h1>GuitAIr</h1>
                        <p>Instrumento versátil e acessível, ideal para quem está começando ou busca praticidade 
                            no dia a dia. O violão oferece um som encorpado e natural, dispensando o uso de amplificadores. 
                            Perfeito para estilos como MPB, sertanejo, pop e acústico em geral, é uma excelente escolha tanto 
                            para estudo quanto para apresentações intimistas.</p>

                            <br>
                            <br>
                            
                            <div class="compr-prod">
                                <input type="submit" name="comprar" value="Comprar" id="produto">
                            </div>
                    </div>
                </label>

                <input class="chkimg" type="radio" name="modelo" value="azul" id="azul">
                <label for="azul">
                    <img src="img/guitarra.png" alt="Guitarra">
                    <div class="texto-instr">
                        <h1>Eletric GuitAIr</h1>
                        <p>Projetada para quem busca potência e variedade sonora, a guitarra é um instrumento elétrico que permite 
                            explorar diversos estilos musicais, como rock, metal, blues e jazz. Combinada com amplificadores e 
                            efeitos, oferece uma ampla gama de timbres, sendo ideal para músicos que desejam personalizar seu som 
                            e se destacar em performances.</p>

                            <br>
                            <br>
                            
                            <div class="compr-prod">
                                <input type="submit" name="comprar" value="Comprar" id="produto">
                            </div>
                    </div>
                </label>

                <input class="chkimg" type="radio" name="modelo" value="vermelho" id="vermelho">
                <label for="vermelho">
                    <img src="img/bateria.png" alt="Bateria">
                    <div class="texto-instr">
                        <h1>DrumIA</h1>
                        <p>Instrumento essencial para marcação de ritmo e energia musical, a bateria é composta por diversos elementos 
                            que permitem criar batidas complexas e dinâmicas. Presente em praticamente todos os estilos musicais, é 
                            ideal para quem gosta de intensidade e coordenação. Pode ser encontrada em versões acústicas ou eletrônicas, 
                            atendendo diferentes necessidades e ambientes.</p>
                            
                            <br>
                            <br>
                            
                            <div class="compr-prod">
                                <input type="submit" name="comprar" value="Comprar" id="produto">
                            </div>
                    </div>
                </label>
            </fieldset>
        </form>
    </div>

    <br>
    <br>
    <br>
    <br>
    <br>

    <div class="container-content">

    </div>

</div>

<?php
include 'inc/footer.inc.php';
?>