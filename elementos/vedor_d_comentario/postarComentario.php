<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
	if (isset($_POST['comentario'])) {
		$tipo = $_POST['tipo'];
		$id = $_POST['id'];
		$comentario = $_POST['comentario'];
		$fio = $_POST['fio'] ?? 0;

		$rows = $db->prepare("INSERT INTO comentarios (id, id_comentador, id_coisa, tipo_de_coisa, texto, fio, data) VALUES (NULL, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP())");
		$rows->bindParam(1, $usuario->id);
		$rows->bindParam(2, $id);
		$rows->bindParam(3, $tipo);
		$rows->bindParam(4, $comentario);
		$rows->bindParam(5, $fio);
		$rows->execute();
	} else {
		// qq tu quer q eu faça bro
	}
}
