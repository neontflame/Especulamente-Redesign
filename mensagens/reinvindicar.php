<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';

$mensagem = $_GET['mensagem'];
$msg = mensagem_requestIDator($mensagem);

if ($usuario->id == $msg->receptor) {
	mudar_usuario($usuario->id, ['davecoins' => $usuario->davecoins + $msg->davecoins]);
	
	$rows = $db->prepare("UPDATE mensagens SET davecoins='0' WHERE receptor = ? AND id = ?");
	$rows->bindParam(1, $usuario->id);
	$rows->bindParam(2, $msg->id);
	$rows->execute();
}

redirect('/mensagens');
