<?php
$db = new PDO("mysql:host=" . $config['DB_HOST'] . ";dbname=" . $config['DB_NAME'], $config['DB_USER'], $config['DB_PASS']);

function remover_pasta_inteira($dir)
{
	if (!is_dir($dir)) {
		return false;
	}

	$files = array_diff(scandir($dir), array('.', '..'));

	foreach ($files as $file) {

		(is_dir("$dir/$file")) ? remover_pasta_inteira("$dir/$file") : unlink("$dir/$file");
	}

	return rmdir($dir);
}

function mandar_email($para, $assunto, $mensagem)
{
	global $config;

	$headers[] = 'From: ' . $config['EMAIL_FROM'];
	$headers[] = 'Reply-To: ' . $config['EMAIL_FROM'];
	$headers[] = 'MIME-Version: 1.0';
	$headers[] = 'Content-type: text/html; charset=utf-8';

	return mail($para, $assunto, $mensagem, implode("\r\n", $headers));
}

// Por username
function usuario_requestinator($username)
{
	global $db;

	$rows = $db->prepare("SELECT * FROM usuarios WHERE username = ?");
	$rows->bindParam(1, $username);
	$rows->execute();
	$user = $rows->fetch(PDO::FETCH_OBJ);

	if ($user == false) {
		return null;
	}

	return $user;
}

// Por id
function usuario_requestIDator($id)
{
	global $db;

	$rows = $db->prepare("SELECT * FROM usuarios WHERE id = ?");
	$rows->bindParam(1, $id);
	$rows->execute();
	$user = $rows->fetch(PDO::FETCH_OBJ);

	if ($user == false) {
		return null;
	}

	return $user;
}

// Porjtoej por id
function projeto_requestIDator($id)
{
	global $db;

	$rows = $db->prepare("SELECT * FROM projetos WHERE id = ?");
	$rows->bindParam(1, $id);
	$rows->execute();
	$proj = $rows->fetch(PDO::FETCH_OBJ);

	if ($proj == false) {
		return null;
	}

	return $proj;
}

// Porjtoej por arquivosdevdd
function projeto_requestARQUIVOSDEVDDator($pasta)
{
	global $db;

	$rows = $db->prepare("SELECT * FROM projetos WHERE arquivos_de_vdd = ?");
	$rows->bindParam(1, $pasta);
	$rows->execute();
	$proj = $rows->fetch(PDO::FETCH_OBJ);

	if ($proj == false) {
		return null;
	}

	return $proj;
}

// cometario por id
function comentario_requestIDator($id)
{
	global $db;

	$rows = $db->prepare("SELECT * FROM comentarios WHERE id = ?");
	$rows->bindParam(1, $id);
	$rows->execute();
	$comm = $rows->fetch(PDO::FETCH_OBJ);

	if ($comm == false) {
		return null;
	}

	return $comm;
}

// Cagatorio por id
function categoria_requestIDator($id)
{
	global $db;

	$rows = $db->prepare("SELECT * FROM forum_categorias WHERE id = ?");
	$rows->bindParam(1, $id);
	$rows->execute();
	$proj = $rows->fetch(PDO::FETCH_OBJ);

	if ($proj == false) {
		return null;
	}

	return $proj;
}

// Forposte por id
function forumpost_requestIDator($id)
{
	global $db;

	$rows = $db->prepare("SELECT * FROM forum_posts WHERE id = ?");
	$rows->bindParam(1, $id);
	$rows->execute();
	$post = $rows->fetch(PDO::FETCH_OBJ);

	if ($post == false) {
		return null;
	}

	return $post;
}

// Colecio por id
function colecao_requestIDator($id)
{
	global $db;

	$rows = $db->prepare("SELECT * FROM colecoes WHERE id = ?");
	$rows->bindParam(1, $id);
	$rows->execute();
	$colecao = $rows->fetch(PDO::FETCH_OBJ);

	if ($colecao == false) {
		return null;
	}

	return $colecao;
}

// Retorna o número de páginas (agora tweaked pra ter suporte pra DaveItens)
function coisos_tudo(&$array, $table, $page = 1, $searchy = '', $queryAdicional = '', $perPage = 10, $sorting = 'id DESC')
{
	global $db;

	$search = $db->quote('%' . $searchy . '%');

	if ($searchy != '') {
		if ($table == 'projetos') {
			$searchQuery = " WHERE (nome LIKE " . $search . " OR descricao LIKE " . $search . " OR arquivos_de_vdd LIKE "  . $search . ") ";
		} else {
			$searchQuery = " WHERE (nome LIKE " . $search . " OR descricao LIKE " . $search . ") ";
		}
	} else {
		$searchQuery = "";
	}

	$rows = $db->prepare("SELECT COUNT(*) as count FROM " . $table . $searchQuery . $queryAdicional);
	$rows->execute();
	$count = $rows->fetch(PDO::FETCH_OBJ)->count;

	$pages = ceil($count / $perPage);
	$offset = ($page - 1) * $perPage;

	$rows = $db->prepare("SELECT * FROM " . $table . $searchQuery . $queryAdicional . " ORDER BY " . $sorting . " LIMIT ? OFFSET ?");
	$rows->bindParam(1, $perPage, PDO::PARAM_INT);
	$rows->bindParam(2, $offset, PDO::PARAM_INT);
	$rows->execute();

	while ($row = $rows->fetch(PDO::FETCH_OBJ)) {
		array_push($array, $row);
	}
	return $pages;
}

// CONVITE COISOS
function obter_convite($codigo)
{
	global $db;

	$codigo = substr($codigo, 0, 255);

	$rows = $db->prepare("SELECT * FROM convites WHERE codigo = ?");
	$rows->bindParam(1, $codigo);
	$rows->execute();

	$convite = $rows->fetch(PDO::FETCH_OBJ);

	if ($convite == false) {
		return null;
	}

	return $convite;
}

function deletar_convite($codigo)
{
	global $db;

	$codigo = substr($codigo, 0, 255);

	$rows = $db->prepare("DELETE FROM convites WHERE codigo = ?");
	$rows->bindParam(1, $codigo);
	$rows->execute();
}

function criar_convite($codigo, $criado_por)
{
	global $db;

	$rows = $db->prepare("INSERT INTO convites (codigo, criado_por) VALUES (?, ?)");
	$rows->bindParam(1, $codigo);
	$rows->bindParam(2, $criado_por);
	$rows->execute();
}

function obter_convites_criados_por($criado_por)
{
	global $db;

	$rows = $db->prepare("SELECT * FROM convites WHERE criado_por = ?");
	$rows->bindParam(1, $criado_por);
	$rows->execute();

	$convites = [];

	while ($row = $rows->fetch(PDO::FETCH_OBJ)) {
		array_push($convites, $row);
	}

	return $convites;
}

// Essa função espera apenas entradas corretas!! Checagem é realizada no frontend.
function criar_usuario($username, $email, $senha, $convite)
{
	global $db;

	$rows = $db->prepare("INSERT INTO usuarios (username, password_hash, email) VALUES (?, ?, ?)");
	$rows->bindParam(1, $username);
	$hashword = password_hash($senha, PASSWORD_DEFAULT);
	$rows->bindParam(2, $hashword);
	$rows->bindParam(3, $email);
	$rows->execute();

	$rows = $db->prepare("SELECT id FROM usuarios WHERE username = ?");
	$rows->bindParam(1, $username);
	$rows->execute();
	$row = $rows->fetch(PDO::FETCH_OBJ);

	$last_id = $row->id;

	$row = $rows->fetch(PDO::FETCH_OBJ);
	$rows = $db->prepare("UPDATE convites SET usado_por = ? WHERE codigo = ?");
	$rows->bindParam(1, $last_id);
	$rows->bindParam(2, $convite);
	$rows->execute();
}

// RECUPERAÇÃO DE SENHA COISOS
function enviar_recuperacao($usuario)
{
	global $db;
	global $config;

	$codigo = bin2hex(random_bytes(16));

	$rows = $db->prepare("INSERT INTO reccodigo (codigo, criado_por) VALUES (?, ?)");
	$rows->bindParam(1, $codigo);
	$rows->bindParam(2, $usuario->id);
	$rows->execute();

	$message = <<<HTML
	<html>
	<head>
		<title>Recuperação de senha</title>
	</head>
	<body>
		<h1>Recuperação de senha</h1>
		<p>Opa boa tarde/noite/dia!! Você pediu uma recuperação de senha? Se sim clica nesse link aí abaixo:</p>
		<p><a href="{$config['URL']}/recuperar.php?codigo={$codigo}">Recuperar minha senha!</a></p>
		<p>Se você não solicitou essa recuperação, ignore esse email.</p>
		<p>Atenciosamente, Espy!! <:]></p>
	</body>
	</html>
	HTML;

	mandar_email($usuario->email, "Recuperação de senha", $message);

	return $codigo;
}

function obter_recuperacao($codigo)
{
	global $db;

	$codigo = substr($codigo, 0, 255);

	$rows = $db->prepare("SELECT * FROM reccodigo WHERE codigo = ?");
	$rows->bindParam(1, $codigo);
	$rows->execute();

	$convite = $rows->fetch(PDO::FETCH_OBJ);

	if ($convite == false) {
		return null;
	}

	return $convite;
}

function deletar_recuperacao($codigo)
{
	global $db;

	$codigo = substr($codigo, 0, 255);

	$rows = $db->prepare("DELETE FROM reccodigo WHERE codigo = ?");
	$rows->bindParam(1, $codigo);
	$rows->execute();
}

// MENSAGEM COISOS YEAAAAAAHHHHH !!!!
function criar_mensagem($receptor, $html, $icone, $davecoins = 0)
{
	global $db;

	// pego do login coisos
	$usuario = null;

	if (isset($_SESSION['id'])) {
		$usuario = usuario_requestIDator($_SESSION['id']);
	}

	if ($receptor != $usuario->id) {
		$rows = $db->prepare("INSERT INTO mensagens (receptor, html, icone, davecoins) VALUES (?, ?, ?, ?)");
		$rows->bindParam(1, $receptor);
		$rows->bindParam(2, $html);
		$rows->bindParam(3, $icone);
		$rows->bindParam(4, $davecoins);
		$rows->execute();
	}
}

function ler_mensagens($receptor)
{
	global $db;

	$rows = $db->prepare("UPDATE mensagens SET lido='1' WHERE receptor = ?");
	$rows->bindParam(1, $receptor);
	$rows->execute();
}

// mensagem por id
function mensagem_requestIDator($id)
{
	global $db;

	$rows = $db->prepare("SELECT * FROM mensagens WHERE id = ?");
	$rows->bindParam(1, $id);
	$rows->execute();
	$msg = $rows->fetch(PDO::FETCH_OBJ);

	if ($msg == false) {
		return null;
	}

	return $msg;
}

function quantReacoes($id, $reacao)
{
	global $db;

	$rows = $db->prepare("SELECT COUNT(*) as count FROM reacoes WHERE id_reator = ? AND tipo_de_reacao = ?");
	$rows->bindParam(1, $id);
	$rows->bindParam(2, $reacao);
	$rows->execute();
	$count = $rows->fetch(PDO::FETCH_OBJ)->count;

	return $count;
}

function quantPosts($id)
{
	global $db;

	$rows = $db->prepare("SELECT COUNT(*) as count FROM forum_posts WHERE id_postador = ?");
	$rows->bindParam(1, $id);
	$rows->execute();
	$count = $rows->fetch(PDO::FETCH_OBJ)->count;

	return $count;
}

function pfp($user)
{
	if (empty($user->pfp)) {
		return '/static/pfp_padrao.png';
	}
	return '/static/pfps/' . $user->pfp;
}

function banner($user)
{
	if (empty($user->banner)) {
		return '/static/banner_padrao.png';
	}
	return '/static/banners/' . $user->banner;
}

// Vai ter q ir aqui mesmo
function humanFileSize($size)
{
	if ($size >= 1073741824) {
		$fileSize = round($size / 1024 / 1024 / 1024, 1) . 'GB';
	} elseif ($size >= 1048576) {
		$fileSize = round($size / 1024 / 1024, 1) . 'MB';
	} elseif ($size >= 1024) {
		$fileSize = round($size / 1024, 1) . 'KB';
	} else {
		$fileSize = $size . ' bytes';
	}
	return $fileSize;
}

function subir_arquivo($file, $pasta, $tabela, $id, $coluna, $extensoes_permitidas, $max_FILE_Sisz)
{
	global $db;

	if (!isset($file) || $file['size'] == 0) {
		return "§Não tem arquivo?!";
	}

	$livel = humanFileSize($max_FILE_Sisz);

	if ($file['size'] > $max_FILE_Sisz) {
		return "§Arquivo muito grande! O máximo é $livel.";
	}

	$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
	if (count($extensoes_permitidas) > 0 && !in_array(strtolower($extension), $extensoes_permitidas)) {
		return "§Extensão não permitida!";
	}

	// Corrige a pasta
	if (substr($pasta, -1) != '/') {
		$pasta .= '/';
	}
	if (substr($pasta, 0, 1) != '/') {
		$pasta = '/' . $pasta;
	}

	// DELETAR aqruivo antigo
	if ($tabela != null && $id != null && $coluna != null) {
		$rows = $db->prepare("SELECT $coluna FROM $tabela WHERE id = ?");
		$rows->bindParam(1, $id);
		$rows->execute();
		$old_file = $rows->fetch(PDO::FETCH_OBJ)->$coluna;
		if (!empty($old_file) && file_exists($_SERVER['DOCUMENT_ROOT'] . $pasta . $old_file)) {
			unlink($_SERVER['DOCUMENT_ROOT'] . $pasta . $old_file);
		}
	}
	// so pra ter certeza
	if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $pasta)) {
		mkdir($_SERVER['DOCUMENT_ROOT'] . $pasta);
	}
	// Sube arquivo novo
	$filename = uniqid() . '.' . $extension;
	$file_path = $_SERVER['DOCUMENT_ROOT'] . $pasta . $filename;
	move_uploaded_file($file['tmp_name'], $file_path);

	if ($tabela != null || $id != null || $coluna != null) {
		$rows = $db->prepare("UPDATE $tabela SET $coluna = ? WHERE id = ?");
		$rows->bindParam(1, $filename);
		$rows->bindParam(2, $id);
		$rows->execute();
	}

	return $filename;
}

function subir_arquivoses($files, $pasta, $tabela, $id, $coluna, $extensoes_permitidas, $max_FILE_Sisz, $max_FILE_cont, $deletar_tudo = false)
{
	global $db;

	if (!isset($files) || count($files['size']) == 0) {
		return "§Não tem arquivo?!";
	}

	if (count($files['size']) > $max_FILE_cont) {
		return "§Arquivos demais! O número máximo de arquivos permitidos é $max_FILE_cont.";
	}

	$livel = humanFileSize($max_FILE_Sisz);

	$filenames = [];
	for ($i = 0; $i < count($files['size']); $i++) {

		if ($files['size'][$i] > $max_FILE_Sisz) {
			return "§Arquivo muito grande! O tamanho máximo é $livel.";
		}

		$extension = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
		if (count($extensoes_permitidas) > 0 && !in_array(strtolower($extension), $extensoes_permitidas)) {
			return "§Extensão não permitida!";
		}

		// Corrige a pasta
		if (substr($pasta, -1) != '/') {
			$pasta .= '/';
		}
		if (substr($pasta, 0, 1) != '/') {
			$pasta = '/' . $pasta;
		}

		// Sube arquivo novo
		$filename = uniqid() . '.' . $extension;
		$file_path = $_SERVER['DOCUMENT_ROOT'] . $pasta . $filename;
		move_uploaded_file($files['tmp_name'][$i], $file_path);

		array_push($filenames, $filename);
	}

	if ($deletar_tudo == true) {
		$rows = $db->prepare("SELECT $coluna FROM $tabela WHERE id = ?");
		$rows->bindParam(1, $id);
		$rows->execute();
		$old_files = $rows->fetch(PDO::FETCH_OBJ)->$coluna;
		if (!empty($old_files)) {
			$old_files = explode('\n', $old_files);
			foreach ($old_files as $old_file) {
				unlink($_SERVER['DOCUMENT_ROOT'] . $pasta . $old_file);
			}
		}
	}

	$relacao_livel_ilivel = [];
	for ($i = 0; $i < count($files['name']); $i++) {
		$relacao_livel_ilivel[$files['name'][$i]] = $filenames[$i];
	}

	$filenames = implode('\n', $filenames);

	$rows = $db->prepare("UPDATE $tabela SET $coluna = ? WHERE id = ?");
	$rows->bindParam(1, $filenames);
	$rows->bindParam(2, $id);
	$rows->execute();

	return $relacao_livel_ilivel;
}

// Campos é uma array [campo => valor]
function mudar_usuario($id, $campos)
{
	global $db;

	$query = "UPDATE usuarios SET ";
	$i = 0;
	foreach ($campos as $campo => $valor) {
		$query .= $campo . " = ?";
		if ($i < count($campos) - 1) {
			$query .= ", ";
		}
		$i++;
	}
	$query .= " WHERE id = ?";

	$rows = $db->prepare($query);

	$i = 1;
	foreach ($campos as $campo => $valor) {
		$rows->bindParam($i, $valor);
		$i++;
	}
	$rows->bindParam($i, $id);
	$rows->execute();
}

// Campo2 é uma array [campo => valor]
function mudar_projeto($id, $campos)
{
	global $db;

	$query = "UPDATE projetos SET ";
	$i = 0;
	foreach ($campos as $campo => $valor) {
		$query .= $campo . " = ?";
		if ($i < count($campos) - 1) {
			$query .= ", ";
		}
		$i++;
	}
	$query .= " WHERE id = ?";

	$rows = $db->prepare($query);

	$i = 1;
	foreach ($campos as $campo => $valor) {
		$rows->bindParam($i, $valor);
		$i++;
	}
	$rows->bindParam($i, $id);
	$rows->execute();
}

// Campos 3 uma array [campo => valor]
function mudar_forumpost($id, $campos)
{
	global $db;

	$query = "UPDATE forum_posts SET ";
	$i = 0;
	foreach ($campos as $campo => $valor) {
		$query .= $campo . " = ?";
		if ($i < count($campos) - 1) {
			$query .= ", ";
		}
		$i++;
	}
	$query .= " WHERE id = ?";

	$rows = $db->prepare($query);

	$i = 1;
	foreach ($campos as $campo => $valor) {
		$rows->bindParam($i, $valor);
		$i++;
	}
	$rows->bindParam($i, $id);
	$rows->execute();
}

// C4mpos é um4 4rr4y [c4mpo => v4lor]
function mudar_colecao($id, $campos)
{
	global $db;

	$query = "UPDATE colecoes SET ";
	$i = 0;
	foreach ($campos as $campo => $valor) {
		$query .= $campo . " = ?";
		if ($i < count($campos) - 1) {
			$query .= ", ";
		}
		$i++;
	}
	$query .= " WHERE id = ?";

	$rows = $db->prepare($query);

	$i = 1;
	foreach ($campos as $campo => $valor) {
		$rows->bindParam($i, $valor);
		$i++;
	}
	$rows->bindParam($i, $id);
	$rows->execute();
}
// essas 4 funçoes poderiam ser uma unica funçao mas eu to com PREGUIÇA entao eu vou fazer biquinho e bater o pe

function reagir($id_reator, $id_reagido, $tipo_de_reagido, $tipo_de_reacao)
{
	global $db;

	if ($tipo_de_reagido != 'perfil' && $tipo_de_reagido != 'projeto' && $tipo_de_reagido != 'forum') {
		return -5;
	}

	if ($tipo_de_reacao != 'mitada' && $tipo_de_reacao != 'sojada') {
		return -4;
	}

	$existe = usuario_requestIDator($id_reator);
	if (!$existe) {
		return -3;
	}

	if ($tipo_de_reagido == 'perfil') {
		$existe = usuario_requestIDator($id_reagido);
	} else if ($tipo_de_reagido == 'forum') {
		$existe = forumpost_requestIDator($id_reagido);
	} else if ($tipo_de_reagido == 'projeto') {
		$existe = projeto_requestIDator($id_reagido);
	}

	if (!$existe) {
		return -2;
	}

	if (ja_reagiu($id_reator, $id_reagido, $tipo_de_reagido, $tipo_de_reacao)) {
		return -1;
	}

	$rows = $db->prepare("INSERT INTO reacoes (tipo_de_reacao, id_reator, tipo_de_reagido, id_reagido) VALUES (?, ?, ?, ?)");
	$rows->bindParam(1, $tipo_de_reacao);
	$rows->bindParam(2, $id_reator);
	$rows->bindParam(3, $tipo_de_reagido);
	$rows->bindParam(4, $id_reagido);
	$rows->execute();

	$count = $db->prepare("SELECT COUNT(*) as count FROM reacoes WHERE tipo_de_reacao = ? AND tipo_de_reagido = ? AND id_reagido = ?");
	$count->bindParam(1, $tipo_de_reacao);
	$count->bindParam(2, $tipo_de_reagido);
	$count->bindParam(3, $id_reagido);
	$count->execute();
	$count = $count->fetch(PDO::FETCH_OBJ)->count;

	$alteracao = [];

	if ($tipo_de_reacao == 'mitada') {
		$alteracao['mitadas'] = $count;
	} else if ($tipo_de_reacao == 'sojada') {
		$alteracao['sojadas'] = $count;
	}

	switch ($tipo_de_reagido) {
		case 'perfil':
			mudar_usuario($id_reagido, $alteracao);
			break;
		case 'projeto':
			mudar_projeto($id_reagido, $alteracao);
			break;
		case 'forum':
			mudar_forumpost($id_reagido, $alteracao);
			break;
	}

	return $count;
}

function ja_reagiu($id_reator, $id_reagido, $tipo_de_reagido, $tipo_de_reacao)
{
	global $db;

	$ja_mitou = $db->prepare("SELECT id FROM reacoes WHERE tipo_de_reacao = ? AND id_reator = ? AND tipo_de_reagido = ? AND id_reagido = ?");
	$ja_mitou->bindParam(1, $tipo_de_reacao);
	$ja_mitou->bindParam(2, $id_reator);
	$ja_mitou->bindParam(3, $tipo_de_reagido);
	$ja_mitou->bindParam(4, $id_reagido);
	$ja_mitou->execute();

	return $ja_mitou->fetch(PDO::FETCH_OBJ) ? true : false;
}

function desreagir($id_reator, $id_reagido, $tipo_de_reagido, $tipo_de_reacao)
{
	global $db;

	if ($tipo_de_reagido != 'perfil' && $tipo_de_reagido != 'projeto' && $tipo_de_reagido != 'forum') {
		return -5;
	}

	if ($tipo_de_reacao != 'mitada' && $tipo_de_reacao != 'sojada') {
		return -4;
	}

	$existe = usuario_requestIDator($id_reator);
	if (!$existe) {
		return -3;
	}

	if ($tipo_de_reagido == 'perfil') {
		$existe = usuario_requestIDator($id_reagido);
	} else if ($tipo_de_reagido == 'forum') {
		$existe = forumpost_requestIDator($id_reagido);
	} else if ($tipo_de_reagido == 'projeto') {
		$existe = projeto_requestIDator($id_reagido);
	}

	if (!$existe) {
		return -2;
	}

	if (!ja_reagiu($id_reator, $id_reagido, $tipo_de_reagido, $tipo_de_reacao)) {
		return -1;
	}

	$rows = $db->prepare("DELETE FROM reacoes WHERE tipo_de_reacao = ? AND id_reator = ? AND tipo_de_reagido = ? AND id_reagido = ?");
	$rows->bindParam(1, $tipo_de_reacao);
	$rows->bindParam(2, $id_reator);
	$rows->bindParam(3, $tipo_de_reagido);
	$rows->bindParam(4, $id_reagido);
	$rows->execute();

	$count = $db->prepare("SELECT COUNT(*) as count FROM reacoes WHERE tipo_de_reacao = ? AND tipo_de_reagido = ? AND id_reagido = ?");
	$count->bindParam(1, $tipo_de_reacao);
	$count->bindParam(2, $tipo_de_reagido);
	$count->bindParam(3, $id_reagido);
	$count->execute();
	$count = $count->fetch(PDO::FETCH_OBJ)->count;

	$alteracao = [];

	if ($tipo_de_reacao == 'mitada') {
		$alteracao['mitadas'] = $count;
	} else if ($tipo_de_reacao == 'sojada') {
		$alteracao['sojadas'] = $count;
	}

	switch ($tipo_de_reagido) {
		case 'perfil':
			mudar_usuario($id_reagido, $alteracao);
			break;
		case 'projeto':
			mudar_projeto($id_reagido, $alteracao);
			break;
		case 'forum':
			mudar_forumpost($id_reagido, $alteracao);
			break;
	}

	return $count;
}

// Arquivo vivel == o arquivo do jogo q roda no navegador
// SE o tipo for rt, $arquivos é == a $pasta
function criar_projeto($id_criador, $nome, $descricao, $tipo, $arquivos, $arquivoVivel, $thumb, $extensoes_permitidas = [])
{
	global $db;

	// EXPLODIR HOSTINGER
	// se for rt ele coloca o nome da pasta em $arquivos_de_vdd
	$arquivos_de_vdd = ($arquivos != null && !is_string($arquivos)) ? implode('\n', $arquivos['name']) : (is_string($arquivos) ? $arquivos : '');

	if ($arquivos == null && $arquivoVivel == null && ($tipo != 'rt' && $tipo != 'bg')) {
		return "§Comeram seus arquivos?";
	}

	$rows = $db->prepare("INSERT INTO projetos (id_criador, nome, descricao, tipo, arquivos_de_vdd) VALUES (?, ?, ?, ?, ?)");
	$rows->bindParam(1, $id_criador);
	$rows->bindParam(2, $nome);
	$rows->bindParam(3, $descricao);
	$rows->bindParam(4, $tipo);
	$rows->bindParam(5, $arquivos_de_vdd);
	$rows->execute();

	$id = $db->lastInsertId();

	mkdir($_SERVER['DOCUMENT_ROOT'] . '/static/projetos/' . $id);
	mkdir($_SERVER['DOCUMENT_ROOT'] . '/static/projetos/' . $id . '/thumb');

	if ($tipo != 'rt') {
		if ($arquivos != null && $arquivos['size'] > 0) {
			$rtn = subir_arquivoses($arquivos, '/static/projetos/' . $id, "projetos", $id, "arquivos", $extensoes_permitidas, 1024 * 1024 * 1024, 50);
			if (is_string($rtn)) {
				return $rtn;
			}
		}

		if ($arquivoVivel != null && $arquivoVivel['size'] > 0) {
			$rtn = subir_arquivo_vivel($arquivoVivel, $id, $id_criador);
			if (is_string($rtn)) {
				return $rtn;
			}
		}
	} else {
		file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/static/projetos/' . $id . '/index.html', '<html><head><title>Meu site foda</title></head><body><h1>Coloque HTML aqui!! :]</h1></body></html>');
	}

	if ($thumb != null && $thumb['size'] > 0) {
		$rtn = subir_arquivo($thumb, '/static/projetos/' . $id . '/thumb', "projetos", $id, "thumbnail", ["png", "gif", "jpg", "jpeg", "bmp"], 1024 * 1024 * 10); //nao tem como uma imagem ser maior que 10 mb
		if (is_string($rtn) && str_starts_with($rtn, "§")) {
			return $rtn;
		}
	}

	return projeto_requestIDator($id);
}

function subir_arquivo_vivel($arquivoVivel, $id, $id_criador)
{
	global $db;

	$arquivoVivelLivelEIlivel = null;
	if ($arquivoVivel != null) {
		$arquivoVivelLivelEIlivel = $arquivoVivel['name'] . '\n';
	}

	if (str_ends_with($arquivoVivel['name'], ".zip")) {
		$zip = new ZipArchive;
		$res = $zip->open($arquivoVivel['tmp_name']);
		if ($res === TRUE) {
			// Check if index.html inside
			$index = false;
			for ($i = 0; $i < $zip->numFiles; $i++) {
				$filename = $zip->getNameIndex($i);
				if (str_ends_with($filename, "index.html")) {
					$index = true;
					break;
				}
			}
			if ($index == false) {
				return "§Arquivo .zip não contém index.html!";
			}

			$zip->extractTo($_SERVER['DOCUMENT_ROOT'] . '/static/projetos/' . $id . '/jogo/');
			$zip->close();
		}
	}
	$rtn = subir_arquivo($arquivoVivel, '/static/projetos/' . $id, null, null, null, ["swf", "zip", "html", "sb", "sb2", "sb3"], 1024 * 1024 * 1024);
	if (is_string($rtn) && str_starts_with($rtn, "§")) {
		return $rtn;
	}
	$arquivoVivelLivelEIlivel .= $rtn;
	$rows = $db->prepare("UPDATE projetos SET arquivo_vivel = ? WHERE id_criador = ? AND id = ?");
	$rows->bindParam(1, $arquivoVivelLivelEIlivel);
	$rows->bindParam(2, $id_criador);
	$rows->bindParam(3, $id);
	$rows->execute();
}

// Arquivo vivel == o arquivo do jogo q roda no navegador
function editar_projeto($id_criador, $id_projeto, $nome, $descricao, $arquivos_novos, $remover, $ordem, $arquivoVivel, $removerArquivoVivel, $thumb, $removerThumb, $extensoes_permitidas = [])
{
	global $db;

	// -1: Checar se eu posso ter um super salsicha sandwich ei scoob (nome muito curto)
	$rows = $db->prepare("SELECT id_criador, tipo FROM projetos WHERE id = ?");
	$rows->bindParam(1, $id_projeto);
	$rows->execute();
	$row = $rows->fetch(PDO::FETCH_OBJ);
	if ($row->id_criador != $id_criador) {
		return "§Sua edição é: <em>inválida edição!</em>";
	}

	$tipo = $row->tipo;

	// <ZERO></ZERO>: Alterar nome e descrição
	$rows = $db->prepare("UPDATE projetos SET nome = ?, descricao = ? WHERE id = ?");
	$rows->bindParam(1, $nome);
	$rows->bindParam(2, $descricao);
	$rows->bindParam(3, $id_projeto);
	$rows->execute();

	if ($tipo != 'rt') {
		// <PRIMEIRO></PRIMEIRO>: Eu quero um super salsicha sandwich
		// Ei Scoob, eu acho que o <SEGUNDO></SEGUNDO> está <TERCEIRO></TERCEIRO>!
		// AKA: Obter nomes reais dos arquivos
		$relacao_livel_ilivel = [];
		$rows = $db->prepare("SELECT arquivos_de_vdd, arquivos FROM projetos WHERE id = ?");
		$rows->bindParam(1, $id_projeto);
		$rows->execute();
		$row = $rows->fetch(PDO::FETCH_OBJ);

		$arquivos_de_vdd = explode('\n', $row->arquivos_de_vdd);
		$arquivos = explode('\n', $row->arquivos);

		for ($i = 0; $i < count($arquivos); $i++) {
			$relacao_livel_ilivel[$arquivos_de_vdd[$i]] = $arquivos[$i];
		}

		// <SEGUNDA></SEGUNDA>: Remover arquivos
		foreach ($remover as $removido) {
			unlink($_SERVER['DOCUMENT_ROOT'] . '/static/projetos/' . $id_projeto . '/' . $relacao_livel_ilivel[$removido]);
			unset($relacao_livel_ilivel[$removido]);
		}

		$arquivos = [];
		$arquivos_de_vdd = [];
		foreach ($relacao_livel_ilivel as $livel => $ilivel) {
			array_push($arquivos, $ilivel);
			array_push($arquivos_de_vdd, $livel);
		}
		$arquivos = implode('\n', $arquivos);
		$arquivos_de_vdd = implode('\n', $arquivos_de_vdd);

		$rows = $db->prepare("UPDATE projetos SET arquivos = ?, arquivos_de_vdd = ? WHERE id = ?");
		$rows->bindParam(1, $arquivos);
		$rows->bindParam(2, $arquivos_de_vdd);
		$rows->bindParam(3, $id_projeto);
		$rows->execute();

		// TERÇ<A></A>: Adicionar arquivos novos
		if (count($arquivos_novos) > 0) {
			$rtn = subir_arquivoses($arquivos_novos, '/static/projetos/' . $id_projeto, "projetos", $id_projeto, "arquivos", $extensoes_permitidas, 1024 * 1024 * 1024, 50);
			if (is_string($rtn) && str_starts_with($rtn, "§")) {
				return substr($rtn, 1);
			}

			$relacao_livel_ilivel = array_merge($relacao_livel_ilivel, $rtn);
		}

		// <qwuarta></qwuarta>: Reordenar arquivos
		// EXPLODIR HOSTINGER ----- hostinger: hey :( dont do that
		$ordem = $ordem ? explode('\n', $ordem) : [];
		$arquivos = [];
		$arquivos_de_vdd = [];
		foreach ($ordem as $arquivo) {
			array_push($arquivos, $relacao_livel_ilivel[$arquivo]);
			array_push($arquivos_de_vdd, $arquivo);
		}
		$arquivos = implode('\n', $arquivos);
		$arquivos_de_vdd = implode('\n', $arquivos_de_vdd);

		$rows = $db->prepare("UPDATE projetos SET arquivos = ?, arquivos_de_vdd = ? WHERE id = ?");
		$rows->bindParam(1, $arquivos);
		$rows->bindParam(2, $arquivos_de_vdd);
		$rows->bindParam(3, $id_projeto);
		$rows->execute();

		// <AGORA></AGORA>: arquivo vivel.
		$rows = $db->prepare("SELECT arquivo_vivel FROM projetos WHERE id = ?");
		$rows->bindParam(1, $id_projeto);
		$rows->execute();
		$row = $rows->fetch(PDO::FETCH_OBJ);
		$arquivoVivelLivelEIlivel = $row->arquivo_vivel;
		$arquivoVivelIlivel = $arquivoVivelLivelEIlivel == "" ? "" : explode('\n', $arquivoVivelLivelEIlivel)[1];

		if ($removerArquivoVivel) {
			if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/static/projetos/' . $id_projeto . '/' . $arquivoVivelIlivel)) {
				unlink($_SERVER['DOCUMENT_ROOT'] . '/static/projetos/' . $id_projeto . '/' . $arquivoVivelIlivel);
			}
			remover_pasta_inteira($_SERVER['DOCUMENT_ROOT'] . '/static/projetos/' . $id_projeto . '/jogo');
			$arquivoVivelLivelEIlivel = "";
			$rows = $db->prepare("UPDATE projetos SET arquivo_vivel = '' WHERE id_criador = ? AND id = ?");
			$rows->bindParam(1, $id_criador);
			$rows->bindParam(2, $id_projeto);
			$rows->execute();
		} else {
			if ($arquivoVivel != null && $arquivoVivel['size'] > 0) {
				$rtn = subir_arquivo_vivel($arquivoVivel, $id_projeto, $id_criador);
				if (is_string($rtn) && str_starts_with($rtn, "§")) {
					return substr($rtn, 1);
				}
			}
		}
	}

	// <AGORA DENOVO></AGORA DENOVO>: thumbnailery
	// nao tem copilot no notepad++ entao nao da pra fazer o <c></c>oiso
	$rows = $db->prepare("SELECT thumbnail FROM projetos WHERE id = ?");
	$rows->bindParam(1, $id_projeto);
	$rows->execute();
	$row = $rows->fetch(PDO::FETCH_OBJ);
	$thumbestNail = $row->thumbnail;

	if ($removerThumb) {
		if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/static/projetos/' . $id_projeto . '/thumb/' . $thumbestNail)) {
			unlink($_SERVER['DOCUMENT_ROOT'] . '/static/projetos/' . $id_projeto . '/thumb/' . $thumbestNail);
		}
		$rows = $db->prepare("UPDATE projetos SET thumbnail = '' WHERE id_criador = ? AND id = ?");
		$rows->bindParam(1, $id_criador);
		$rows->bindParam(2, $id_projeto);
		$rows->execute();
	} else {
		if ($thumb != null && $thumb['size'] > 0) {
			$rtn = subir_arquivo($thumb, '/static/projetos/' . $id_projeto . '/thumb', "projetos", $id_projeto, "thumbnail", ["png", "gif", "jpg", "jpeg", "bmp"], 1024 * 1024 * 10); //nao tem como uma imagem ser maior que 10 mb
			if (is_string($rtn) && str_starts_with($rtn, "§")) {
				return substr($rtn, 1);
			}
		}
	}

	$rows = $db->prepare("UPDATE projetos SET dataBump = CURRENT_TIMESTAMP WHERE id = ?");
	$rows->bindParam(1, $id_projeto);
	$rows->execute();

	return projeto_requestIDator($id_projeto);
}

function deletar_projeto($id_criador, $id_projeto)
{
	global $db;
	
	$projetoADeletar = projeto_requestIDator($id_projeto);
	
	// -1: Checar se eu posso ter um super salsicha sandwich ei scoob (nome muito curto)
	$rows = $db->prepare("SELECT id_criador FROM projetos WHERE id = ? AND id_criador = ?");
	$rows->bindParam(1, $id_projeto);
	$rows->bindParam(2, $id_criador);
	$rows->execute();
	$row = $rows->fetch(PDO::FETCH_OBJ);
	if ($row == false) {
		return "§Sua deleção é: <em>inválida deleção!</em>";
	}

	$rows = $db->prepare("DELETE FROM projetos WHERE id = ? AND id_criador = ?");
	$rows->bindParam(1, $id_projeto);
	$rows->bindParam(2, $id_criador);
	$rows->execute();

	$rows = $db->prepare("DELETE FROM reacoes WHERE tipo_de_reagido = 'projeto' AND id_reagido = ?");
	$rows->bindParam(1, $id_projeto);
	$rows->execute();

	$rows = $db->prepare("DELETE FROM comentarios WHERE tipo_de_coisa = 'projeto' AND id_coisa = ?");
	$rows->bindParam(1, $id_projeto);
	$rows->execute();

	remover_pasta_inteira($_SERVER['DOCUMENT_ROOT'] . '/static/projetos/' . $id_projeto);
	
	if ($projetoADeletar->tipo == 'jg' || $projetoADeletar->tipo == 'rt') {
		mudar_usuario($id_criador, ['davecoins' => usuario_requestIDator($id_criador)->davecoins - 25]);
	} else {
		mudar_usuario($id_criador, ['davecoins' => usuario_requestIDator($id_criador)->davecoins - 10]);
	}
}

function comentario_requestinator($tipo, $id)
{
	global $db;

	$rows = $db->prepare("SELECT * FROM comentarios WHERE tipo_de_coisa = ? AND id_coisa = ? AND fio = 0 ORDER BY data DESC");
	$rows->bindParam(1, $tipo);
	$rows->bindParam(2, $id);
	$rows->execute();

	$comentarios = [];

	while ($row = $rows->fetch(PDO::FETCH_OBJ)) {
		array_push($comentarios, $row);
	}

	return $comentarios;
}

function respostas_requestinator($id_topico)
{
	global $db;

	$rows = $db->prepare("SELECT * FROM comentarios WHERE fio = ? ORDER BY data ASC");
	$rows->bindParam(1, $id_topico);
	$rows->execute();

	$comentarios = [];

	while ($row = $rows->fetch(PDO::FETCH_OBJ)) {
		array_push($comentarios, $row);
	}

	return $comentarios;
}

// BOUNTY FUNÇOES
function obter_bounties()
{
	global $db;

	$rows = $db->prepare("SELECT * FROM bounties");
	$rows->execute();

	$bounties = [];

	while ($row = $rows->fetch(PDO::FETCH_OBJ)) {
		array_push($bounties, $row);
	}

	return $bounties;
}

function obter_bounty($id_bounty)
{
	global $db;

	$rows = $db->prepare("SELECT * FROM bounties WHERE id = ?");
	$rows->bindParam(1, $id_bounty);
	$rows->execute();

	$row = $rows->fetch(PDO::FETCH_OBJ);
	
	return $row;
}

function verificar_completeness_da_bounty($id_bounty, $id_usuario)
{
	global $db;

	$rows = $db->prepare("SELECT * FROM bounties_completos WHERE id_bounty = ? AND id_usuario = ? AND data = CURRENT_DATE");
	$rows->bindParam(1, $id_bounty);
	$rows->bindParam(2, $id_usuario);
	$rows->execute();
	
	$row = $rows->fetch(PDO::FETCH_OBJ);
	
	if ($row != false) {
		return ($row->reinvindicada + 1);
	}
	return 0;
}

function fazer_bounty($id_bounty, $reinvindicar = true)
{
	global $db;
	global $usuario;

	$rows = $db->prepare("SELECT * FROM bounties_completos WHERE id_bounty = ? AND id_usuario = ?");
	$rows->bindParam(1, $id_bounty);
	$rows->bindParam(2, $usuario->id);
	$rows->execute();
	
	$row = $rows->fetch(PDO::FETCH_OBJ);
	
	if ($row != false) {
		if ($row->data != date("Y-m-d", time())) {
			$rows = $db->prepare("UPDATE bounties_completos SET data = CURRENT_DATE, reinvindicada = 0 WHERE id_bounty = ? AND id_usuario = ?");
			$rows->bindParam(1, $id_bounty);
			$rows->bindParam(2, $usuario->id);
			$rows->execute();
			
			if ($reinvindicar) {
				reinvindicar_bounty($id_bounty);
			}
			
			return 1;
		}
	} else {
		$rows = $db->prepare("INSERT INTO bounties_completos (id_bounty, id_usuario, data) VALUES (?, ?, CURRENT_DATE)");
		$rows->bindParam(1, $id_bounty);
		$rows->bindParam(2, $usuario->id);
		$rows->execute();
		
		if ($reinvindicar) {
			reinvindicar_bounty($id_bounty);
		}
		
		return 0;
	}
	
	return -1;
}


function reinvindicar_bounty($id_bounty) {
	global $db;
	global $usuario;
	
	$rows = $db->prepare("SELECT * FROM bounties_completos WHERE id_bounty = ? AND id_usuario = ? AND data = CURRENT_DATE AND reinvindicada = FALSE");
	$rows->bindParam(1, $id_bounty);
	$rows->bindParam(2, $usuario->id);
	$rows->execute();
	
	$row = $rows->fetch(PDO::FETCH_OBJ);
	$bounty = obter_bounty($id_bounty);
	
	if ($row != false) {
		$rank = obter_rank($usuario->davecoins);
		$davescoinNovas = ($bounty->davecoins == 0 ? $rank["diada"] : $bounty->davecoins);
		
		mudar_usuario($usuario->id, ['davecoins' => $usuario->davecoins + $davescoinNovas]);
		
		$rows = $db->prepare("UPDATE bounties_completos SET reinvindicada = TRUE WHERE id_bounty = ? AND id_usuario = ? AND data = CURRENT_DATE");
		$rows->bindParam(1, $id_bounty);
		$rows->bindParam(2, $usuario->id);
		$rows->execute();
	}
}

// subtrai data ya mané
function velhificar_data($datetime)
{
	$date = date_create($datetime);
	// horario de brasilia, 2008
	date_sub($date, date_interval_create_from_date_string("17 years + 3 hours"));
	return date_format($date, "d/m/Y") . " às " . date_format($date, "H:i");
}

// coisos de filetype
// eu ia fazer a funçao pra isso mas eu achei uma nota no manual de php de filesize que fazia o que eu queria so que bem melhor
// agradeço-lhe rommel de rommelsantor dot com
function human_filesize($filename, $fileCoiso, $decimals = 2)
{
	$bytes = filesize($_SERVER['DOCUMENT_ROOT'] . $fileCoiso . '/' . $filename);
	$sz = 'BKMGTP';
	$factor = floor((strlen($bytes) - 1) / 3);
	return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . @$sz[$factor] . 'B';
}

function the_filetype($filename, $fileCoiso)
{
	return strtolower(pathinfo($_SERVER['DOCUMENT_ROOT'] . $fileCoiso . '/' . $filename, PATHINFO_EXTENSION));
}

function the_filetype_image($filename, $fileCoiso)
{
	$filetipos = [
		'rtf' => 'richText',
		'txt' => 'text',
		'png' => 'img',
		'jpg' => 'img',
		'jpeg' => 'img',
		'gif' => 'img',
		// 'webp' => 'img',
		'mp4' => 'vid',
		'avi' => 'vid',
		'mkv' => 'vid',
		'wmv' => 'vid',
		// 'webm' => 'vid',
		'ogg' => 'audio',
		'mp3' => 'audio',
		'wav' => 'audio',
		'flac' => 'audio',
		'wma' => 'audio',
		'aac' => 'audio',
		'aiff' => 'audio',
		'zip' => 'zip',
		'rar' => 'zip',
		'gz' => 'zip',
		'tar' => 'zip',
		'7z' => 'zip',
		'exe' => 'executavel',
		'doc' => 'word',
		'docx' => 'word',
		'ppt' => 'ppt',
		'pptx' => 'ppt',
		'fla' => 'flash',
		'swf' => 'shockwave',
		'sb' => 'scratch',
		'sb2' => 'scratch2',
		'sb3' => 'scratch3',
		'capx' => 'construct2',
		'caproj' => 'construct2',
		'gm5' => 'gamemaker',
		'gm6' => 'gamemaker',
		'gm7' => 'gamemaker',
		'gm8' => 'gamemaker',
		'gm81' => 'gamemaker',
		'gmk' => 'gamemaker',
		'gmx' => 'gamemaker',
		'fbx' => 'mesh',
		'obj' => 'mesh',
		'dae' => 'mesh',
		'stl' => 'mesh',
		'blend' => 'blender',
		'c4d' => 'c4d',
		'pac' => 'brawlbox',
		'pcs' => 'brawlbox',
		'brstm' => 'brawlbox',
		'brsar' => 'brawlbox',
	];

	return $filetipos[the_filetype($filename, $fileCoiso)] ?? 'arquivo';
}

function obter_rank($davecoins_atuais)
{
	global $db;

	$rows = $db->prepare("SELECT * FROM daveniveis ORDER BY davecoins_proximo ASC");
	$rows->execute();

	$last_row = false;

	while ($row = $rows->fetch(PDO::FETCH_OBJ)) {
		if ($davecoins_atuais < $row->davecoins_proximo) {
			break;
		}
		$last_row = $row;
	}

	// quantia anteror de davcoin = Qa
	// quantia davecoin proxima = Qp
	// quantia atual davecoin = Qd

	// 376 * ((Qd - Qa) / (Qp - Qa))

	return [
		"imagem" => $row ? $row->imagem : $last_row->imagem,
		"nome" => $row ? $row->nome : $last_row->nome,
		"davecoins_proximo" => $row ? $row->davecoins_proximo : $last_row->davecoins_proximo,
		"diada" => $row ? $row->diada : $last_row->diada,
		"barrinha_width" => 376 * (!$row ? 1 : (($davecoins_atuais - ($last_row ? $last_row->davecoins_proximo : 0)) / (($row ? $row->davecoins_proximo : $last_row->davecoins_proximo) - ($last_row ? $last_row->davecoins_proximo : 0))))
	];
}

// funçoes de colecao
function colecao_curacios($id)
{
	$autores = [];
	$autoresMasChique = [];
	
	global $db;
	// pega do autor da coleçao
	array_push($autores, colecao_requestIDator($id)->criador);
	
	// pega dos curadores
	$rows = $db->prepare("SELECT * FROM colecoes_curadores WHERE id_colecao = ?");
	$rows->bindParam(1, $id);
	$rows->execute();
	
	while ($colecaoaut = $rows->fetch(PDO::FETCH_OBJ)) {
		array_push($autoresMasChique, $colecaoaut);
	}
	
	foreach ($autoresMasChique as $cara) {
		array_push($autores, $cara->id_curador);
	}

	return $autores;
}

function colecao_projetos($id)
{
	$projetos = [];
	$projetosMasChique = [];
	
	global $db;
	
	$rows = $db->prepare("SELECT * FROM colecoes_projetos WHERE id_colecao = ?");
	$rows->bindParam(1, $id);
	$rows->execute();
	
	while ($colecaoproj = $rows->fetch(PDO::FETCH_OBJ)) {
		array_push($projetosMasChique, $colecaoproj);
	}
	
	foreach ($projetosMasChique as $prj) {
		array_push($projetos, $prj->id_projeto);
	}

	return $projetos;
}

function projeto_colecoes($id)
{
	$colecoes = [];
	$colecoesMasChique = [];
	
	global $db;
	
	$rows = $db->prepare("SELECT * FROM colecoes_projetos WHERE id_projeto = ?");
	$rows->bindParam(1, $id);
	$rows->execute();
	
	while ($colecaoproj = $rows->fetch(PDO::FETCH_OBJ)) {
		array_push($colecoesMasChique, $colecaoproj);
	}
	
	foreach ($colecoesMasChique as $col) {
		array_push($colecoes, $col->id_colecao);
	}

	return $colecoes;
}