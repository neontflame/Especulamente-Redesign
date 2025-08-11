<?php
include $_SERVER['DOCUMENT_ROOT'] . "/shhhh/autoload.php";
include $_SERVER['DOCUMENT_ROOT'] . "/shhhh/feedUtils.php";

if (!isset($_GET['id'])) {
	erro_404();
}
$id = $_GET['id'];
$ofix = categoria_requestIDator($id);

$coisos = [];

$pages = coisos_tudo($coisos, 'forum_posts', 1, '', ' WHERE id_categoria = ' . $id . ' AND id_resposta = -1', 1000000000000000, 'dataBump DESC');

// usando um codigo ja feito pq sim
$rss = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rss></rss>');
$rss->addAttribute('version', '2.0');

// Cria o elemento <channel> dentro de <rss>
$canal = $rss->addChild('channel');
// Adiciona sub-elementos ao elemento <channel>
$canal->addChild('title', "Feed RSS da categoria " . $ofix->nome . " <> Fóruns do PORTAL ESPECULAMENTE");
$canal->addChild('link', 'http://especulamente.com.br/foruns/' . $ofix->id);
$canal->addChild('description', $ofix->descricao);
$canal->addChild('language', 'pt-BR');

$Parsedown = new Parsedown();	

foreach ($coisos as $post) {
		// Cria outro elemento <item> dentro de <channel>
		$date = date_create($post->data);
		
		$item = $canal->addChild('item');

		$item->addChild('title', $post->sujeito);
		$item->addChild('link', $config['URL'] . '/foruns/' . $post->id_categoria . '/' . $post->id);
		
		$item->addChild('description', responde_clickers($post->conteudo)); 
		
		if (isset($post->dataBump)) {	$item->addChild('pubDate', $post->dataBump);	}
		else {	$item->addChild('pubDate', $post->data);	}
}

header("content-type: application/rss+xml; charset=utf-8");

// Entrega o conteúdo do RSS completo:
echo $rss->asXML();
exit;
?>