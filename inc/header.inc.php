<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=help,home,info,lists,person,shopping_cart"/>
    <title>GuitAIr</title>
</head>

<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="./img/guitair.png" alt="Logo da Startup"></a>
        </div>

        <div class="navbar">
            <ul>
                <li><button onclick="document.location='index.php'"><span class="material-symbols-outlined">home</span></button></li>
                <li><button onclick="document.location='prod.php'"><span class="material-symbols-outlined">lists</span></button></li>
                <li><button onclick="document.location='about.php'"><span class="material-symbols-outlined">info</span></button></li>
                <li><button onclick="document.location='help.php'"><span class="material-symbols-outlined">help</span></button></li>
                <li><button onclick="document.location='acc.php'"><span class="material-symbols-outlined">person</span></button></li>
                <li><button onclick="document.location='cart.php'"><span class="material-symbols-outlined">shopping_cart</span></button></li>
            </ul>
        </div>
    </header>

<div onclick="subirTela()" class="scrlbtn"></div>
<script type="text/javascript" src="./js/scrbtn.js"></script>