<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';

$id = $_GET['id'];
$item = daveitem_requestIDator($id);

$rows = $db->prepare("INSERT INTO inventario (id, id_usuario, id_item) VALUES (NULL, ?, ?)");
$rows->bindParam(1, $usuario->id);
$rows->bindParam(2, $id);
$rows->execute();

mudar_usuario($usuario->id, ['davecoins' => $usuario->davecoins - $item->daveprice]);

header("Location: /daveloja");
