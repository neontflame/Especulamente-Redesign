<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
login_obrigatorio($usuario);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
	if (isset($_POST['comentario'])) {
		$respondido = $_POST['respondido'] ?? -1;
		$categoria = $_POST['categoria'];
		$sujeito = $_POST['sujeito'];
		$comentario = $_POST['comentario'];

		if ($respondido == -1) {
			if ($sujeito == null && $comentario == '') {
				$_SESSION['erroPost'] = 'Comeram seu post?';
				redirect('/foruns/postar/');
			}

			if (strlen($sujeito) < 3) {
				$_SESSION['erroPost'] = 'Seu sujeito é muito curto!!';
				redirect('/foruns/postar/');
			}
		}

		$rows = $db->prepare("INSERT INTO forum_posts (id, id_postador, id_resposta, id_categoria, sujeito, conteudo, data) VALUES (NULL, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP())");
		$rows->bindParam(1, $usuario->id);
		$rows->bindParam(2, $respondido);
		$rows->bindParam(3, $categoria);
		$rows->bindParam(4, $sujeito);
		$rows->bindParam(5, $comentario);
		$rows->execute();

		$id_com = $db->lastInsertId();

		// MENSAGEM HANDLER WOAH
		$oCoiso = mensagem_mencao($comentario, $respondido, $id_com);
		
		$forumpost = forumpost_requestIDator($id_com);

		if ($forumpost->id_resposta != -1) {
			fazer_bounty(9);
			
			//bump!
			$rows = $db->prepare("UPDATE forum_posts SET dataBump = CURRENT_TIMESTAMP() WHERE id = ?");
			$rows->bindParam(1, $forumpost->id_resposta);
			$rows->execute();

			$quote = responde_clickers($comentario, "/foruns/{$forumpost->id_categoria}/{$forumpost->id_resposta}");

			if ($oCoiso == 1) {
				criar_mensagem(
					forumpost_requestIDator($forumpost->id_resposta)->id_postador,
					<<<HTML
						<a href="/usuarios/{$usuario->username}" class="usuario">{$usuario->username}</a>
						comentou no seu tópico
						<a href="/foruns/{$forumpost->id_categoria}/{$forumpost->id_resposta}#post_{$id_com}">{$forumpost->sujeito}</a>!
						
						<blockquote>
							{$quote}
						</blockquote>
						HTML,
					'resposta'
				);
			}
		} else {
			fazer_bounty(10);
			redirect('/foruns/' . $forumpost->id_categoria . '/' . $id_com);
		}
	} else {
		// qq tu quer q eu faça bro
	}
}

// funçao janky mas que seja
function mensagem_mencao($texto, $id, $id_com)
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
		$forumpost = forumpost_requestIDator($id);
		$nome = usuario_requestinator($nomius)->id;
		$quote = responde_clickers($texto, "/foruns/{$forumpost->id_categoria}/{$id}");

		if (forumpost_requestIDator($id)->id_resposta != -1) {
			$forumpost = forumpost_requestIDator(forumpost_requestIDator($id)->id_resposta);
		}
		
		criar_mensagem(
			$nome,
			<<<HTML
				<a href="/usuarios/{$usuario->username}" class="usuario">{$usuario->username}</a>
				mencionou você em
				<a href="/foruns/{$forumpost->id_categoria}/{$id}#post_{$id_com}">{$forumpost->sujeito}</a>!
				<blockquote>
					{$quote}
				</blockquote>
				HTML,
			'menciona'
		);
	}
	
	if (array_key_exists(usuario_requestIDator(forumpost_requestIDator($id)->id_postador)->username, $nomesarray)) {
		return 0;
	} else {
		return 1;
	}
}
