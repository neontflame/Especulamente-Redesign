<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
?>

<?php
// isso aqui e uma api Ok !
$tipos = array('.png', '.bmp', '.jpg', 'jpeg', '.gif', '.mp4');

if (!isset($_GET['id'])) {
	erro_404();
}
$id = $_GET['id'];

$projeto = projeto_requestIDator($id);
// nao explodir hostinger

header("Content-Type: text/plain; charset=utf-8");

$arquivos = explode('\n', $projeto->arquivos);
$arquivos_de_vdd = explode('\n', $projeto->arquivos_de_vdd);

if ($_GET['modo'] == "internal") {
	foreach ($arquivos as $i => $archivo) {
		$arquivo = strtolower($archivo);
		if (in_array(substr($arquivo, -4), $tipos)) {
			echo $archivo . "\n";
		}
	}
}

if ($_GET['modo'] == "outer") {
	foreach ($arquivos_de_vdd as $i => $archivo) {
		$arquivo = strtolower($archivo);
		if (in_array(substr($arquivo, -4), $tipos)) {
			echo $archivo . "\n";
		}
	}
}
?>