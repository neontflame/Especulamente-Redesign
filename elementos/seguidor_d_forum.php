<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
	if (isset($_POST['id'])) {
		$id = $_POST['id'];

		$count = seguir_forumpost($usuario->id, $id);
		if ($count == -1) {
			desseguir_forumpost($usuario->id, $id);
			echo 'yeah (Unf)';
		} else {
			echo 'yeah';
		}
	} else {
		// qq tu quer q eu faça bro
	}
}
