<?php
require_once "../_com/__RequireOnceComunes.php";
require_once "_Sesion.php";

entrarSiSesionIniciada();
?>

<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="main.css"><link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="SesionFormularioEstilos.css">
    <script src="../admin/ProductosGestion.js" ></script>
</head>
<body>
<div class="main">
    <div class="container a-container" id="a-container">
        <form class="form" id="a-form" method="get" action="UsuarioCrear.php">
            <h2 class="form_title title">Crear una cuenta</h2>
            <input name="inpNombreUsuario" class="form__input" type="text" placeholder="Nombre">
            <input name="inpApellidosUsuario" class="form__input" type="text" placeholder="Apellidos">
            <input name="inpIdentificadorUsuario" class="form__input" type="text" placeholder="Identificador">
            <input name="inpContrasennaUsuario" class="form__input" type="password" placeholder="Password">
            <input name="btnRegistroUsuario" type="submit" value="Registrarse" class="form__button button">
        </form>
    </div>
    <div class="container b-container" id="b-container">
        <form class="form" id="b-form" method="post" action="SesionComprobar.php">
            <h2 class="form_title title">Iniciar Sesión</h2>
            <input class="form__input" type="text" name="identificador" placeholder="Identificador">
            <input class="form__input" type="password" name="contrasenna" placeholder="Contraseña">
            <h3>Recuerdáme</h3>
            <input type='checkbox' name='recuerdame'>
            <input type='submit' class="form__button button" value='Iniciar sesión'>
        </form>
    </div>
    <div class="switch" id="switch-cnt">
        <div class="switch__circle"></div>
        <div class="switch__circle switch__circle--t"></div>
        <div class="switch__container" id="switch-c1">
            <h2 class="switch__title title">¡Bienvenido!</h2>
            <button class="switch__button button switch-btn">Iniciar sesión</button>
        </div>
        <div class="switch__container is-hidden" id="switch-c2">
            <h2 class="switch__title title">¿Sin cuenta?</h2>
            <p class="switch__description description">Pon tus datos y se parte de nostros</p>
            <button class="switch__button button switch-btn">Registrarse</button>
        </div>
    </div>
</div>


</body>
</html>
<script src="https://cpwebassets.codepen.io/assets/common/stopExecutionOnTimeout-1b93190375e9ccc259df3a57c1abc0e64599724ae30d7ea4c6877eb615f89387.js"></script>


<script id="rendered-js" >
    let switchCtn = document.querySelector("#switch-cnt");
    let switchC1 = document.querySelector("#switch-c1");
    let switchC2 = document.querySelector("#switch-c2");
    let switchCircle = document.querySelectorAll(".switch__circle");
    let switchBtn = document.querySelectorAll(".switch-btn");
    let aContainer = document.querySelector("#a-container");
    let bContainer = document.querySelector("#b-container");
    let allButtons = document.querySelectorAll(".submit");

    let getButtons = e => e.preventDefault();

    let changeForm = e => {

        switchCtn.classList.add("is-gx");
        setTimeout(function () {
            switchCtn.classList.remove("is-gx");
        }, 1500);

        switchCtn.classList.toggle("is-txr");
        switchCircle[0].classList.toggle("is-txr");
        switchCircle[1].classList.toggle("is-txr");

        switchC1.classList.toggle("is-hidden");
        switchC2.classList.toggle("is-hidden");
        aContainer.classList.toggle("is-txl");
        bContainer.classList.toggle("is-txl");
        bContainer.classList.toggle("is-z200");
    };

    let mainF = e => {
        for (var i = 0; i < allButtons.length; i++) {if (window.CP.shouldStopExecution(0)) break;
            allButtons[i].addEventListener("click", getButtons);}window.CP.exitedLoop(0);
        for (var i = 0; i < switchBtn.length; i++) {if (window.CP.shouldStopExecution(1)) break;
            switchBtn[i].addEventListener("click", changeForm);}window.CP.exitedLoop(1);
    };

    window.addEventListener("load", mainF);
    //# sourceURL=pen.js
</script>



</body>

</html>