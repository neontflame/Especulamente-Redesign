<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
	if (isset($_POST['id'])) {
		$id = $_POST['id'];

		$count = indicar($usuario->id, $id);
		if ($count == -1) {
			$count = desindicar($usuario->id, $id);
			echo $count;
		} else {
			/* 
			// depois eu faço a bounty #Lol
			if (verificar_completeness_da_bounty(2, $usuario ->id) == 0) {
				fazer_bounty(2);
				echo $count . '§'; //§-based detecçao de bounty
			} else {
				echo $count;
			}
			*/
			echo $count;
		}
	} else {
		// qq tu quer q eu faça bro
	}
}
