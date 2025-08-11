<?php
include $_SERVER['DOCUMENT_ROOT'] . "/shhhh/autoload.php";
include $_SERVER['DOCUMENT_ROOT'] . "/shhhh/feedUtils.php";

if (!isset($_GET['username'])) {
	erro_404();
}
$username = $_GET['username'];
$perfil = usuario_requestinator($username);

$coisos = [];

$pages = coisos_tudo($coisos, 'projetos', 1, '', ' WHERE id_criador = ' . $perfil->id, 1000000000000000, 'GREATEST(COALESCE(dataBump, 0), data) DESC, id DESC');

// usando um codigo ja feito pq sim
$rss = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rss></rss>');
$rss->addAttribute('version', '2.0');

// Cria o elemento <channel> dentro de <rss>
$canal = $rss->addChild('channel');
// Adiciona sub-elementos ao elemento <channel>
$canal->addChild('title', 'Feed RSS de ' . $perfil->username . ' <> Usuário do PORTAL ESPECULAMENTE');
$canal->addChild('link', 'http://especulamente.com.br/usuarios/' . $perfil->username);
$canal->addChild('description', $perfil->bio);
$canal->addChild('language', 'pt-BR');

$Parsedown = new Parsedown();
renderarProjetosMasDessaVezNoRss($coisos, $canal);

header("content-type: application/rss+xml; charset=utf-8");

// Entrega o conteúdo do RSS completo:
echo $rss->asXML();
exit;
?>