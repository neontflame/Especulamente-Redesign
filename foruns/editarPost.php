<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
login_obrigatorio($usuario);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
	if (isset($_POST['comentario'])) {
		// posts apenas yeah !
		mudar_forumpost($_POST['id'], ['conteudo' => $_POST['comentario']]);
		
		if (isset($_POST['sujeito']) && strlen($_POST['sujeito']) > 3) {
			mudar_forumpost($_POST['id'], ['sujeito' => $_POST['sujeito']]);
		}
	} else {
		// qq tu quer q eu faça bro
	}
}
