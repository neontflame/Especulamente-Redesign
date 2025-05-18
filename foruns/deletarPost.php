<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
	if (isset($_POST['id'])) {
		$id = $_POST['id'];

		$rows = $db->prepare("SELECT id FROM comentarios WHERE id_comentador = ? AND id = ?");
		$rows->bindParam(1, $usuario->id);
		$rows->bindParam(2, $id);
		$rows->execute();
		if ($rows->rowCount() > 0) {
			$rows = $db->prepare("DELETE FROM comentarios WHERE id_comentador = ? AND id = ?");
			$rows->bindParam(1, $usuario->id);
			$rows->bindParam(2, $id);
			$rows->execute();

			$rows = $db->prepare("DELETE FROM comentarios WHERE fio = ?");
			$rows->bindParam(1, $id);
			$rows->execute();
		}
	} else {
		// qq tu quer q eu faça bro
	}
}
