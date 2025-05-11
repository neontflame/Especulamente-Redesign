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
		
		$id_com = $db->lastInsertId();
		
		// MENSAGEM HANDLER WOAH
		mensagem_mencao($comentario, $tipo, $id, $id_com);
		
		if ($tipo == 'projeto') {
			$projeto = projeto_requestIDator($id);
			echo $respondido;
			
			criar_mensagem($projeto->id_criador,
				'<a href="/usuarios/'. $usuario->username . '" class="usuario">' . $usuario->username . '</a>
				comentou em seu projeto
				<a href="/projetos/' . $id . '#comentario_'. $id_com .'">' . $projeto->nome . '</a>!
				
				<blockquote>
				"' . htmlspecialchars($comentario) . '"
				</blockquote>', 'comentario');
			if ($respondido != 0) {
				$comentarioOG = comentario_requestIDator($respondido);
				
				if ($comentarioOG->id_comentador != $projeto->id_criador) {
					criar_mensagem($comentarioOG->id_comentador, 
						'<a href="/usuarios/' . $usuario->username . '" class="usuario">'. $usuario->username .'</a>
						respondeu seu comentário em
						<a href="/projetos/' . $id . '#comentario_'. $id_com .'">' . $projeto->nome . '</a>!
						
						<blockquote>
						"'. htmlspecialchars($comentario) .'"
						</blockquote>', 'resposta');
				}
			}
		}
		if ($tipo == 'perfil') {
			$perfil = usuario_requestIDator($id);
			echo $respondido;
			
			criar_mensagem($perfil->id,
				'<a href="/usuarios/'. $usuario->username . '" class="usuario">' . $usuario->username . '</a>
				comentou no
				<a href="/usuarios/' . $perfil->username . '#comentario_'. $id_com .'">seu perfil</a>!
				
				<blockquote>
				"' . htmlspecialchars($comentario) . '"
				</blockquote>', 'comentario');
			if ($respondido != 0) {
				$comentarioOG = comentario_requestIDator($respondido);
				
				if ($comentarioOG->id_comentador != $perfil->id) {
					criar_mensagem($comentarioOG->id_comentador, 
						'<a href="/usuarios/' . $usuario->username . '" class="usuario">'. $usuario->username .'</a>
						respondeu seu comentário no perfil de 
						<a href="/usuarios/' . $perfil->username . '#comentario_'. $id_com .'">' . $perfil->username . '</a>!
						
						<blockquote>
						"'. htmlspecialchars($comentario) .'"
						</blockquote>', 'resposta');
				}
			}
		}
		
	} else {
		// qq tu quer q eu faça bro
	}
}

// funçao janky mas que seja
function mensagem_mencao($texto, $tipo, $id, $id_com) {
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

			criar_mensagem($nome,
				'<a href="/usuarios/'. $usuario->username . '" class="usuario">' . $usuario->username . '</a>
				mencionou você em
				<a href="/projetos/' . $id . '#comentario_'. $id_com .'">' . $projeto->nome . '</a>!
				<blockquote>"' . htmlspecialchars($texto) . '"</blockquote>',
				'menciona');
		} elseif ($tipo === 'perfil') {
			$perfil = usuario_requestIDator($id);
			$nome = usuario_requestinator($nomius)->id;

			criar_mensagem($nome,
				'<a href="/usuarios/'. $usuario->username . '" class="usuario">' . $usuario->username . '</a>
				mencionou você no perfil de 
				<a href="/usuarios/' . $perfil->username . '#comentario_'. $id_com .'">' . $perfil->username . '</a>!
				<blockquote>"' . htmlspecialchars($texto) . '"</blockquote>',
				'menciona');
		}
	}

}
