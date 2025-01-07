<?php
include $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
?>

<?php
// isso aqui e uma api Ok !
$tipos = array('.png', '.bmp', '.jpg', 'jpeg', '.gif', '.PNG', '.BMP', '.JPG', 'JPEG', '.GIF');

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
	foreach ($arquivos as $i => $arquivo) {
		if (in_array(substr($arquivo, -4), $tipos)) {
			echo $arquivo . "\n";
		}
	}
}

if ($_GET['modo'] == "outer") {
	foreach ($arquivos_de_vdd as $i => $arquivo) {
		if (in_array(substr($arquivo, -4), $tipos)) {
			echo $arquivo . "\n";
		}
	}
}
?>