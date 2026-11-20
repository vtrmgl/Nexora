<?php
    require_once 'assets/php/functions.php';

    iniciarSessaoSegura();

    if (usuarioEstaLogado()) {
        redirecionar('perfil.php');
    }

    redirecionar('inicio.php');
?>