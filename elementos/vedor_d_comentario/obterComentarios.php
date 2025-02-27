<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';

$tipo = $_GET['tipo'];
$id = $_GET['id'];

vedor_d_comentario($tipo, $id, false, $usuario);
