<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
?>

<?php
if (!isset($_GET['id'])) {
  erro_404();
}
$id = $_GET['id'];

$projeto = projeto_requestIDator($id);
// EXPLODIR HOSTINGER (novamente)
$arquivos = explode('\n', $projeto->arquivos);
$arquivos_de_vdd = explode('\n', $projeto->arquivos_de_vdd);

// e aqui que se fazem zips 
// pense na fabrica de chocolate do willy wonka mas com zips ao inves de chocolate
$zipname = $projeto->nome . '.zip';
$zip = new ZipArchive;
$zip->open($zipname, ZipArchive::CREATE);
foreach ($arquivos as $i => $arquivo) {
  $zip->addFile($_SERVER['DOCUMENT_ROOT'] . '/static/projetos/' . $projeto->id . '/' . $arquivo, '' . $arquivos_de_vdd[$i]);
}
$zip->close();

ob_clean();
ob_end_flush(); // isso aqui arruma o zip - nao sei como funciona so achei no stackoverflow
header("Cache-Control: no-cache, must-revalidate");
header('Content-Type: application/zip');
header('Content-disposition: attachment; filename=' . $zipname);
header('Content-Length: ' . filesize($zipname));
readfile($zipname);

unlink($zipname);
?>