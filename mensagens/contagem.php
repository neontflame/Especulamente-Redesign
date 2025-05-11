<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';

	$rows = $db->prepare("SELECT COUNT(*) as count FROM mensagens WHERE lido = 0 AND (receptor = ". $usuario->id . " OR receptor = -1)");
	$rows->execute();
	$count = $rows->fetch(PDO::FETCH_OBJ)->count;
	
	echo $count;