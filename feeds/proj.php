<?php
include $_SERVER['DOCUMENT_ROOT'] . "/shhhh/autoload.php";
include $_SERVER['DOCUMENT_ROOT'] . "/shhhh/feedUtils.php";

$query = $_GET['q'] ?? '';
$tipoQuery = '';
$tipo = $_GET['tipo'] ?? '';

// if (isset($usuario)) {
// 		$naolistcoiso = "(naolist = 0 OR (naolist = 1 AND id_criador = " . $usuario->id . "))";
// } else {
$naolistcoiso = "naolist = 0";
// }

// codigo com alma .
// to do: exorcizar pq Olha isso . Que porra e essa
if ($tipo != '') {
	$tipoQuery = ($query != '' ? " AND " : " WHERE ") . $naolistcoiso . " AND tipo = " . $db->quote($tipo);
} else {
	$tipoQuery = ($query != '' ? " AND " : " WHERE ") . $naolistcoiso;
}

$coisos = [];
$pages = coisos_tudo($coisos, 'projetos', 1, $query, $tipoQuery, 1000000000000000, 'GREATEST(COALESCE(dataBump, 0), data) DESC, id DESC');

// usando um codigo ja feito pq sim
$rss = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rss></rss>');
$rss->addAttribute('version', '2.0');

// Cria o elemento <channel> dentro de <rss>
$canal = $rss->addChild('channel');
// Adiciona sub-elementos ao elemento <channel>
$canal->addChild('title', 'Feed de ' . nomePorTipo($tipo) . ' <> PORTAL ESPECULAMENTE');
$canal->addChild('link', 'http://especulamente.com.br/' . pagetitlePorTipo($tipo) . '/');
$canal->addChild('description', descPorTipo($tipo));
$canal->addChild('language', 'pt-BR');

$Parsedown = new Parsedown();
renderarProjetosMasDessaVezNoRss($coisos, $canal);

header("content-type: application/rss+xml; charset=utf-8");

// Entrega o conteúdo do RSS completo:
echo $rss->asXML();
exit;
?>