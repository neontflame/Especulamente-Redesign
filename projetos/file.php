<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
if (!isset($_GET['id'])) {
  erro_404();
}
$id = $_GET['id'];
if (!isset($_GET['filename'])) {
  erro_404();
}
$filename = $_GET['filename'];

$projeto = projeto_requestIDator($id);
if ($projeto == null) {
  erro_404();
}

// EXPLODIR HOSTINGER
$arquivos = explode('\n', $projeto->arquivos);
$arquivos_de_vdd = explode('\n', $projeto->arquivos_de_vdd);
$arquivo_vivel = $projeto->arquivo_vivel == '' ? null : explode('\n', $projeto->arquivo_vivel);

$relacao_livel_ilivel = array();
foreach ($arquivos as $i => $arquivo) {
  $relacao_livel_ilivel[$arquivos_de_vdd[$i]] = $arquivo;
}
if ($arquivo_vivel != null) {
  $relacao_livel_ilivel[$arquivo_vivel[0]] = $projeto->arquivo_vivel;
}

$path = $relacao_livel_ilivel[$filename] ?? null;
if ($path == null) {
  erro_404();
}

$full_path = $_SERVER['DOCUMENT_ROOT'] . '/static/projetos/' . $projeto->id . '/' . $path;
header('Content-Type: ' . getFileMimeType($full_path));
readfile($full_path);
