<?php

require_once 'Model/Exceptions/DimensoesIncompativeisException.php';
require_once 'Model/Exceptions/MatrizSingularException.php';
require_once 'Model/Exceptions/SistemaImpossivelException.php';
require_once 'Model/Exceptions/SistemaIndeterminadoException.php';

require_once 'Model/Matriz.php';
require_once 'Model/SistemaLinear.php';
require_once 'Controller/MatrizController.php';

use Controller\MatrizController;

$controller = new MatrizController();

$operacao = $_POST['operacao'] ?? 'somar';
$entradaA = $_POST['entradaA'] ?? "1 2\n3 4";
$entradaB = $_POST['entradaB'] ?? "5 6\n7 8";

$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = $controller->executar(
        $operacao,
        $entradaA,
        $entradaB
    );
}

require 'View/home.php';