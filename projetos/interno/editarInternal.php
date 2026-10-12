<?php
error_reporting(E_ERROR);
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';

$erroArray = [];
$sucessoArray = [];

$projeto_e_meu = false;

$browserVeio = isset($_GET['browserVeio']);

function printErros(){
	global $erroArray;
	
	if (count($erroArray) > 0) {
		echo('erro');
		foreach ($erroArray as $err) {
			echo "\n";
			echo $err;
		}
		die();
	}
}

function printSucessos(){
	global $sucessoArray;
	
	if (count($sucessoArray) > 0) {
		echo('sucesso');
		foreach ($sucessoArray as $suc) {
			echo "\n";
			echo $suc;
		}
		die();
	}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
	$tipo = $_POST['tipo'];
	$id = $_POST['id'];

	$projeto = projeto_requestIDator($id);
	if (isset($usuario)) {
		$projeto_e_meu = $projeto->id_criador == $usuario->id;

		if (!$projeto_e_meu) {
			echo 'Bro esse projeto nao e teu';
			die();
		}
	}
	
	$nome = $_POST['nome'];
	$descricao = $_POST['descricao'];
	
	$arquivos = $_FILES['arquivos'] ?? [];
	$arquivoVivel = $_FILES['arquivoJogavel'] ?? [];
	$removerArquivoVivel = $_POST['removerArquivoJogavel'] ?? null;
	
	$remover = $_POST['remover'] ?? [];
	$ordem = $_POST['ordem'] ?? 0;

	$thumb = $_FILES['thumb'] ?? [];
	$removerThumb = $_POST['removerThumb'] ?? null;

	if (strlen($nome) < 3) {
		array_push($erroArray, "O nome do projeto é muito curto.");
	}

	$tiposBons = ($tipo == 'md' ? ['png', 'bmp', 'jpg', 'jpeg', 'gif', 'mp4', 'ogg', 'mp3', 'wav', 'avi', 'wmv', 'mkv', 'swf'] : []);

	if (isset($_POST['unlist'])) {
		if ($_POST['unlist'] == 'on') {
			$rows = $db->prepare("UPDATE projetos SET naolist = 1 WHERE id = ?");
			$rows->bindParam(1, $id);
			$rows->execute();
		}
	} else {
		$rows = $db->prepare("UPDATE projetos SET naolist = 0 WHERE id = ?");
		$rows->bindParam(1, $id);
		$rows->execute();
	}
		
	$projeto_rtn = editar_projeto($usuario->id, $id, $nome, $descricao, $arquivos, $remover, $ordem, $arquivoVivel, $removerArquivoVivel, $thumb, $removerThumb, $tiposBons);
	if (is_string($projeto_rtn)) {
		array_push($erroArray, $projeto_rtn);
	}
		
	if (count($erroArray) == 0) {
		$projeto = $projeto_rtn;
		$sucessoArray = ["Projeto editado com sucesso! :]"];
	}
	
	printErros();
	printSucessos();
	
	if ($browserVeio) { header('Location: /projetos/' . $projeto->id . '/editar?volta=' . $_GET['volta']); }
}
