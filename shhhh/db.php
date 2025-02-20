<?php
$db = new PDO("mysql:host=" . $config['DB_HOST'] . ";dbname=" . $config['DB_NAME'], $config['DB_USER'], $config['DB_PASS']);

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

// Daveitenc por id
function daveitem_requestIDator($id)
{
  global $db;

  $rows = $db->prepare("SELECT * FROM daveitens WHERE id = ?");
  $rows->bindParam(1, $id);
  $rows->execute();
  $dav = $rows->fetch(PDO::FETCH_OBJ);

  if ($dav == false) {
    return null;
  }

  return $dav;
}
// Retorna o número de páginas (agora tweaked pra ter suporte pra DaveItens)
function coisos_tudo(&$array, $table, $page = 1, $perPage = 10)
{
  global $db;

  $rows = $db->prepare("SELECT COUNT(*) as count FROM " . $table);
  $rows->execute();
  $count = $rows->fetch(PDO::FETCH_OBJ)->count;

  $pages = ceil($count / $perPage);
  $offset = ($page - 1) * $perPage;

  $rows = $db->prepare("SELECT * FROM " . $table . " ORDER BY id DESC LIMIT ? OFFSET ?");
  $rows->bindParam(1, $perPage, PDO::PARAM_INT);
  $rows->bindParam(2, $offset, PDO::PARAM_INT);
  $rows->execute();

  while ($row = $rows->fetch(PDO::FETCH_OBJ)) {
    array_push($array, $row);
  }
  return $pages;
}

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
  if (count($extensoes_permitidas) > 0 && !in_array($extension, $extensoes_permitidas)) {
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
  $rows = $db->prepare("SELECT $coluna FROM $tabela WHERE id = ?");
  $rows->bindParam(1, $id);
  $rows->execute();
  $old_file = $rows->fetch(PDO::FETCH_OBJ)->$coluna;
  if (!empty($old_file)) {
    unlink($_SERVER['DOCUMENT_ROOT'] . $pasta . $old_file);
  }

  // Sube arquivo novo
  $filename = uniqid() . '.' . $extension;
  $file_path = $_SERVER['DOCUMENT_ROOT'] . $pasta . $filename;
  move_uploaded_file($file['tmp_name'], $file_path);

  $rows = $db->prepare("UPDATE $tabela SET $coluna = ? WHERE id = ?");
  $rows->bindParam(1, $filename);
  $rows->bindParam(2, $id);
  $rows->execute();

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
    if (count($extensoes_permitidas) > 0 && !in_array($extension, $extensoes_permitidas)) {
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

// Campos é uma array [campo => valor]
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

function reagir($id_reator, $id_reagido, $tipo_de_reagido, $tipo_de_reacao)
{
  global $db;

  if ($tipo_de_reagido != 'perfil' && $tipo_de_reagido != 'projeto') {
    return -1;
  }

  if ($tipo_de_reacao != 'mitada' && $tipo_de_reacao != 'sojada') {
    return -1;
  }

  $existe = usuario_requestIDator($id_reator);
  if (!$existe) {
    return -1;
  }

  $existe = $tipo_de_reagido == 'perfil' ? usuario_requestIDator($id_reagido) : projeto_requestIDator($id_reagido);
  if (!$existe) {
    return -1;
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

  if ($tipo_de_reagido == 'perfil') {
    mudar_usuario($id_reagido, $alteracao);
  } else if ($tipo_de_reagido == 'projeto') {
    mudar_projeto($id_reagido, $alteracao);
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

  if ($tipo_de_reagido != 'perfil' && $tipo_de_reagido != 'projeto') {
    return -1;
  }

  if ($tipo_de_reacao != 'mitada' && $tipo_de_reacao != 'sojada') {
    return -1;
  }

  $existe = usuario_requestIDator($id_reator);
  if (!$existe) {
    return -1;
  }

  $existe = $tipo_de_reagido == 'perfil' ? usuario_requestIDator($id_reagido) : projeto_requestIDator($id_reagido);
  if (!$existe) {
    return -1;
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

  if ($tipo_de_reagido == 'perfil') {
    mudar_usuario($id_reagido, $alteracao);
  } else if ($tipo_de_reagido == 'projeto') {
    mudar_projeto($id_reagido, $alteracao);
  }

  return $count;
}

function criar_projeto($id_criador, $nome, $descricao, $tipo, $arquivos)
{
  global $db;

  // EXPLODIR HOSTINGER
  $arquivos_de_vdd = implode('\n', $arquivos['name']);

  $rows = $db->prepare("INSERT INTO projetos (id_criador, nome, descricao, tipo, arquivos_de_vdd) VALUES (?, ?, ?, ?, ?)");
  $rows->bindParam(1, $id_criador);
  $rows->bindParam(2, $nome);
  $rows->bindParam(3, $descricao);
  $rows->bindParam(4, $tipo);
  $rows->bindParam(5, $arquivos_de_vdd);
  $rows->execute();

  $id = $db->lastInsertId();

  mkdir($_SERVER['DOCUMENT_ROOT'] . '/static/projetos/' . $id);

  $rtn = subir_arquivoses($arquivos, '/static/projetos/' . $id, "projetos", $id, "arquivos", [], 1024 * 1024 * 1024, 50);
  if (is_string($rtn) && str_starts_with($rtn, "§")) {
    return substr($rtn, 1);
  }

  return projeto_requestIDator($id);
}

function editar_projeto($id_criador, $id_projeto, $nome, $descricao, $arquivos_novos, $remover, $ordem)
{
  global $db;

  // -1: Checar se eu posso ter um super salsicha sandwich ei scoob (nome muito curto)
  $rows = $db->prepare("SELECT id_criador FROM projetos WHERE id = ?");
  $rows->bindParam(1, $id_projeto);
  $rows->execute();
  $row = $rows->fetch(PDO::FETCH_OBJ);
  if ($row->id_criador != $id_criador) {
    return "§Sua edição é: <em>inválida edição!</em>";
  }

  // <ZERO></ZERO>: Alterar nome e descrição
  $rows = $db->prepare("UPDATE projetos SET nome = ?, descricao = ? WHERE id = ?");
  $rows->bindParam(1, $nome);
  $rows->bindParam(2, $descricao);
  $rows->bindParam(3, $id_projeto);
  $rows->execute();

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
    $rtn = subir_arquivoses($arquivos_novos, '/static/projetos/' . $id_projeto, "projetos", $id_projeto, "arquivos", [], 1024 * 1024 * 1024, 50);
    if (is_string($rtn) && str_starts_with($rtn, "§")) {
      return substr($rtn, 1);
    }

    $relacao_livel_ilivel = array_merge($relacao_livel_ilivel, $rtn);
  }

  // <qwuarta></qwuarta>: Reordenar arquivos
  // EXPLODIR HOSTINGER ----- hostinger: hey :( dont do that
  $ordem = explode('\n', $ordem);
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

  return projeto_requestIDator($id_projeto);
}

function deletar_projeto($id_criador, $id_projeto)
{
  global $db;

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
function human_filesize($filename, $fileCoiso, $decimals = 2) {
	$bytes = filesize($_SERVER['DOCUMENT_ROOT'] . $fileCoiso . '/' . $filename);
	$sz = 'BKMGTP';
	$factor = floor((strlen($bytes) - 1) / 3);
	return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . @$sz[$factor] . 'B';
}

function the_filetype($filename, $fileCoiso) {
	return strtolower(pathinfo($_SERVER['DOCUMENT_ROOT'] . $fileCoiso . '/' . $filename, PATHINFO_EXTENSION));
}

function the_filetype_image($filename, $fileCoiso) {
	$filetipos = [
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
	];

	return $filetipos[the_filetype($filename, $fileCoiso)] || 'text';
}