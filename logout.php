<?php
require_once 'assets/php/functions.php';

iniciarSessaoSegura();
$_SESSION = [];
session_destroy();

redirecionar('inicio.php');