<?php
include $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
?>

<?php
if (!isset($_GET['id'])) {
  erro_404();
}
$id = $_GET['id'];

$projeto = projeto_requestIDator($id);
// nao explodir hostinger
if ($_GET['modo'] == "internal") {
echo($projeto->arquivos);
}

if ($_GET['modo'] == "outer") {
echo($projeto->arquivos_de_vdd);
}
?>