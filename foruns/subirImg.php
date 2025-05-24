<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
login_obrigatorio($usuario);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (isset($_FILES['image'])) {
		$arquivo = $_FILES['image'];

		$rtn = subir_arquivo($arquivo, '/static/forumImg/', null, null, null, ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'PNG', 'JPG', 'JPEG', 'GIF', 'BMP'], 1024 * 1024 * 5);

		if (!str_starts_with($rtn, '§')) {
			echo '/static/forumImg/' . $rtn;
		} else {
			echo $rtn;
		}
	} else {
		// qq tu quer q eu faça bro
	}
}
