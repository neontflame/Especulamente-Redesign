<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
	if (isset($_POST['comentario'])) {
		// posts apenas yeah !
		mudar_forumpost($_POST['id'], ['conteudo' => $_POST['comentario']]);
	} else {
		// qq tu quer q eu faça bro
	}
}