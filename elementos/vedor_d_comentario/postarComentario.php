<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
	if (isset($_POST['comentario'])) {
		$tipo = $_POST['tipo'];
		$id = $_POST['id'];
		$comentario = $_POST['comentario'];
		$fio = $_POST['fio'] ?? 0;
		$respondido = $_POST['respondido'] ?? 0;

		$rows = $db->prepare("INSERT INTO comentarios (id, id_comentador, id_coisa, tipo_de_coisa, texto, fio, data) VALUES (NULL, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP())");
		$rows->bindParam(1, $usuario->id);
		$rows->bindParam(2, $id);
		$rows->bindParam(3, $tipo);
		$rows->bindParam(4, $comentario);
		$rows->bindParam(5, $fio);
		$rows->execute();
		
		if (verificar_completeness_da_bounty(3, $usuario->id) == 0) {
			fazer_bounty(3);
			echo('yeah');
		}

		$id_com = $db->lastInsertId();

		// MENSAGEM HANDLER WOAH
		$mandavel = true;
		mensagem_mencao($comentario, $tipo, $id, $id_com);

		if ($tipo == 'projeto') {
			$projeto = projeto_requestIDator($id);
			// echo $respondido;
			if ($respondido != 0) {
				$comentarioOG = comentario_requestIDator($respondido);
				$quote = responde_clickers($comentario, "/projetos/{$id}");

				criar_mensagem(
					$comentarioOG->id_comentador,
					<<<HTML
					<a href="/usuarios/{$usuario->username}" class="usuario">{$usuario->username}</a>
					respondeu seu comentário em
					<a href="/projetos/{$id}#comentario_{$id_com}">{$projeto->nome}</a>!
					
					<blockquote>
						{$quote}
					</blockquote>
					HTML,
					'resposta'
				);
				if ($comentarioOG->id_comentador == $projeto->id_criador) {
					$mandavel = false;
				}
			}

			if ($mandavel) {
				$quote = responde_clickers($comentario, "/projetos/{$id}");

				criar_mensagem(
					$projeto->id_criador,
					<<<HTML
					<a href="/usuarios/{$usuario->username}" class="usuario">{$usuario->username}</a>
					comentou em seu projeto
					<a href="/projetos/{$id}#comentario_{$id_com}">{$projeto->nome}</a>!
					
					<blockquote>
						{$quote}
					</blockquote>
					HTML,
					'comentario'
				);
			}
		}
		if ($tipo == 'perfil') {
			$perfil = usuario_requestIDator($id);
			// echo $respondido;

			if ($respondido != 0) {
				$comentarioOG = comentario_requestIDator($respondido);
				$quote = responde_clickers($comentario, "/usuarios/{$perfil->username}");

				criar_mensagem(
					$comentarioOG->id_comentador,
					<<<HTML
					<a href="/usuarios/{$usuario->username}" class="usuario">{$usuario->username}</a>
					respondeu seu comentário no perfil de 
					<a href="/usuarios/{$perfil->username}#comentario_{$id_com}">{$perfil->username}</a>!
					
					<blockquote>
						{$quote}
					</blockquote>
					HTML,
					'resposta'
				);

				if ($comentarioOG->id_comentador == $perfil->id) {
					$mandavel = false;
				}
			}

			if ($mandavel) {
				$quote = responde_clickers($comentario, "/usuarios/{$perfil->username}");

				criar_mensagem(
					$perfil->id,
					<<<HTML
					<a href="/usuarios/{$usuario->username}" class="usuario">{$usuario->username}</a>
					comentou no
					<a href="/usuarios/{$perfil->username}#comentario_{$id_com}">seu perfil</a>!
					
					<blockquote>
						{$quote}
					</blockquote>
					HTML,
					'comentario'
				);
			}
		}
	} else {
		// qq tu quer q eu faça bro
	}
}

// funçao janky mas que seja
function mensagem_mencao($texto, $tipo, $id, $id_com)
{
	global $usuario;
	preg_match_all('/@([a-zA-Z0-9_.]+)/', $texto, $matches);

	$nomesarray = [];

	foreach ($matches[1] as $match) {
		$match = strtolower(trim($match));
		if (isset($nomesarray[$match])) {
			$nomesarray[$match]++;
		} else {
			$nomesarray[$match] = 1;
		}
	}

	foreach ($nomesarray as $nomius => $quant) {
		if ($tipo === 'projeto') {
			$projeto = projeto_requestIDator($id);
			$nome = usuario_requestinator($nomius)->id;
			$quote = responde_clickers($texto, "/projetos/{$id}");

			criar_mensagem(
				$nome,
				<<<HTML
				<a href="/usuarios/{$usuario->username}" class="usuario">{$usuario->username}</a>
				mencionou você em
				<a href="/projetos/{$id}#comentario_{$id_com}">{$projeto->nome}</a>!
				<blockquote>
					{$quote}
				</blockquote>
				HTML,
				'menciona'
			);
		} elseif ($tipo === 'perfil') {
			$perfil = usuario_requestIDator($id);
			$nome = usuario_requestinator($nomius)->id;
			$quote = responde_clickers($texto, "/usuarios/{$perfil->username}");

			criar_mensagem(
				$nome,
				<<<HTML
				<a href="/usuarios/{$usuario->username}" class="usuario">{$usuario->username}</a>
				mencionou você no perfil de 
				<a href="/usuarios/{$perfil->username}#comentario_{$id_com}">{$perfil->username}</a>!
				<blockquote>
					{$quote}
				</blockquote>
				HTML,
				'menciona'
			);
		}
	}
}
