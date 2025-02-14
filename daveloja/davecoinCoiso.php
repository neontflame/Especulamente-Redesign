<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';

$fazer = $_GET['fazer'];

$valorExtra = $_GET['dois'] ?? 1;

if ($fazer == "add") {
	mudar_usuario($usuario->id, ['davecoins' => $usuario->davecoins + $valorExtra]);
}

if ($fazer == "get") {
	echo $usuario->davecoins;
}
?>
