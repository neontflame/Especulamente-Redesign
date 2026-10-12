<?php
error_reporting(E_ERROR);
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';

function printErros(){
	global $erro;
	
	if (count($erro) > 0) {
		echo('erro');
		foreach ($erro as $err) {
			echo "\n";
			echo $err;
		}
	}
}

if (!isset($usuario)) {
	array_push($erro, 'Você não tá logado ô cabeça dmjkgfdh');
	printErros();
	die();
}

$erro = [];
$tipo = $_GET['tipo'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
	if (isset($_POST['tipo'])) {
		$tipo = $_POST['tipo'];

		$nome = $_POST['nome'];
		$descricao = $_POST['descricao'];

		if (strlen($nome) < 3) {
			array_push($erro, "O nome do projeto é muito curto.");
		}

		$tiposBons = (($tipo == 'md') ? ['png', 'bmp', 'jpg', 'jpeg', 'gif', 'mp4', 'ogg', 'mp3', 'wav', 'avi', 'wmv', 'mkv', 'swf'] : (($tipo == 'bg') ? ['png', 'bmp', 'jpg', 'jpeg', 'gif', 'mp4', 'ogg', ''] : []));

		if ($tipo == 'dl' || $tipo == 'md' || $tipo == 'bg') {
			$arquivos = $_FILES['arquivos'] ?? null;
			$thumb = $_FILES['thumb'] ?? null;
	
			if (count($erro) == 0) {
				$projeto = criar_projeto($usuario->id, $nome, $descricao, $tipo, $arquivos, null, $thumb, $tiposBons);
				if (is_string($projeto)) {
					array_push($erro, substr($projeto, 2));
				}
			}
		} else if ($tipo == 'jg') {
			$arquivoJogavel = $_FILES['arquivoJogavel'];
			$thumb = $_FILES['thumb'];
			$arquivos = $_FILES['arquivos'] ?? null;

			if (count($erro) == 0) {
				$projeto = criar_projeto($usuario->id, $nome, $descricao, $tipo, $arquivos, $arquivoJogavel, $thumb, []);
				if (is_string($projeto)) {
					array_push($erro, substr($projeto, 2));
				}
			}
		} else if ($tipo == 'rt') {
			$pasta = $_POST['pasta'];
			$thumb = $_FILES['thumb'];

			$checadorDeCoiso = $db->prepare("SELECT * FROM projetos WHERE arquivos_de_vdd = ?");
			$checadorDeCoiso->bindParam(1, $pasta);
			$checadorDeCoiso->execute();

			if ($checadorDeCoiso->rowCount() != 0) {
				array_push($erro, "Cadê a originalidade? Esse nome de pasta JÁ existe.");
			}

			if (strlen($pasta) < 3) {
				array_push($erro, "O nome da pasta é muito curto.");
			}

			if (!preg_match('/^[a-zA-Z0-9_-]+$/', $pasta)) {
				array_push($erro, "NÃO use acentos, nem espaços, nem caracteres especiais.");
			}

			printErros();
			
			if (count($erro) == 0) {
				$projeto = criar_projeto($usuario->id, $nome, $descricao, $tipo, $pasta, null, $thumb, []);
				if (is_string($projeto)) {
					array_push($erro, $projeto);
				}
			}
		} else {
			array_push($erro, "Tipo de projeto inválido.");
		}
		
		// codigo debativelmente com alma porque eu fiquei com preguiça de colocar na funçao
		$coolUnlist = $_POST['unlist'];
		
		if (isset($coolUnlist)) {
			if ($coolUnlist == 'on') {
				$rows = $db->prepare("UPDATE projetos SET naolist = 1 WHERE id = ?");
				$rows->bindParam(1, $projeto->id);
				$rows->execute();
			}
		}
		
		printErros();
		
		if (count($erro) == 0) {
			if ($coolUnlist != 'on' || !isset($coolUnlist)) {
				if ($tipo == 'md') { fazer_bounty(4); }
				if ($tipo == 'dl') { fazer_bounty(5); }
				if ($tipo == 'bg') { fazer_bounty(6); }
				if ($tipo == 'jg') { fazer_bounty(7); }
				if ($tipo == 'rt') { fazer_bounty(8); }
			}
			echo($projeto->id);
		}
	}
}