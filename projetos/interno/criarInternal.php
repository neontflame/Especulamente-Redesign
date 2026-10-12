<?php
error_reporting(E_ERROR);
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';

$browserVeio = isset($_GET['browserVeio']);

function printErros(){
	global $erroArray;
	
	if (count($erroArray) > 0) {
		echo('erro');
		foreach ($erroArray as $err) {
			echo "\n";
			echo $err;
		}
	}
}

if (!isset($usuario)) {
	array_push($erroArray, 'Você não tá logado ô cabeça dmjkgfdh');
	printErros();
	die();
}

$erroArray = [];
$tipo = $_GET['tipo'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
	if (isset($_POST['tipo'])) {
		$tipo = $_POST['tipo'];

		$nome = $_POST['nome'];
		$descricao = $_POST['descricao'];

		if (strlen($nome) < 3) {
			array_push($erroArray, "O nome do projeto é muito curto.");
		}

		$tiposBons = (($tipo == 'md') ? ['png', 'bmp', 'jpg', 'jpeg', 'gif', 'mp4', 'ogg', 'mp3', 'wav', 'avi', 'wmv', 'mkv', 'swf'] : (($tipo == 'bg') ? ['png', 'bmp', 'jpg', 'jpeg', 'gif', 'mp4', 'ogg', ''] : []));

		if ($tipo == 'dl' || $tipo == 'md' || $tipo == 'bg') {
			$arquivos = $_FILES['arquivos'] ?? null;
			$thumb = $_FILES['thumb'] ?? null;
	
			if (count($erroArray) == 0) {
				$projeto = criar_projeto($usuario->id, $nome, $descricao, $tipo, $arquivos, null, $thumb, $tiposBons);
				if (is_string($projeto)) {
					array_push($erroArray, substr($projeto, 2));
				}
			}
		} else if ($tipo == 'jg') {
			$arquivoJogavel = $_FILES['arquivoJogavel'];
			$thumb = $_FILES['thumb'];
			$arquivos = $_FILES['arquivos'] ?? null;

			if (count($erroArray) == 0) {
				$projeto = criar_projeto($usuario->id, $nome, $descricao, $tipo, $arquivos, $arquivoJogavel, $thumb, []);
				if (is_string($projeto)) {
					array_push($erroArray, substr($projeto, 2));
				}
			}
		} else if ($tipo == 'rt') {
			$pasta = $_POST['pasta'];
			$thumb = $_FILES['thumb'];

			$checadorDeCoiso = $db->prepare("SELECT * FROM projetos WHERE arquivos_de_vdd = ?");
			$checadorDeCoiso->bindParam(1, $pasta);
			$checadorDeCoiso->execute();

			if ($checadorDeCoiso->rowCount() != 0) {
				array_push($erroArray, "Cadê a originalidade? Esse nome de pasta JÁ existe.");
			}

			if (strlen($pasta) < 3) {
				array_push($erroArray, "O nome da pasta é muito curto.");
			}

			if (!preg_match('/^[a-zA-Z0-9_-]+$/', $pasta)) {
				array_push($erroArray, "NÃO use acentos, nem espaços, nem caracteres especiais.");
			}

			printErros();
			
			if (count($erroArray) == 0) {
				$projeto = criar_projeto($usuario->id, $nome, $descricao, $tipo, $pasta, null, $thumb, []);
				if (is_string($projeto)) {
					array_push($erroArray, $projeto);
				}
			}
		} else {
			array_push($erroArray, "Tipo de projeto inválido.");
		}
		
		// codigo debativelmente com alma porque eu fiquei com preguiça de colocar na funçao
		if (isset($_POST['unlist'])) {
			if ($_POST['unlist'] == 'on') {
				$rows = $db->prepare("UPDATE projetos SET naolist = 1 WHERE id = ?");
				$rows->bindParam(1, $projeto->id);
				$rows->execute();
			}
		}
		
		printErros();
		
		if (count($erroArray) == 0) {
			if ($_POST['unlist'] != 'on' || !isset($_POST['unlist'])) {
				if ($tipo == 'md') { fazer_bounty(4); }
				if ($tipo == 'dl') { fazer_bounty(5); }
				if ($tipo == 'bg') { fazer_bounty(6); }
				if ($tipo == 'jg') { fazer_bounty(7); }
				if ($tipo == 'rt') { fazer_bounty(8); }
			}
			echo($projeto->id);
			if ($browserVeio) { header('Location: /projetos/' . $projeto->id); }
		}
	}
}