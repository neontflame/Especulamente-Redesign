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

function subir_arquivo($file, $pasta, $tabela, $id, $coluna, $extensoes_permitidas, $max_FILE_Sisz)
{
  global $db;

  if (!isset($file) || $file['size'] == 0) {
    return "§Não tem arquivo?!";
  }

  if ($file['size'] > $max_FILE_Sisz) {
    return "§Arquivo muito grande!";
  }

  $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
  if (!in_array($extension, $extensoes_permitidas)) {
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
  $arquivos = implode('\n', $arquivos);

  $rows = $db->prepare("INSERT INTO projetos (id_criador, nome, descricao, tipo, arquivos) VALUES (?, ?, ?, ?, ?)");
  $rows->bindParam(1, $id_criador);
  $rows->bindParam(2, $nome);
  $rows->bindParam(3, $descricao);
  $rows->bindParam(4, $tipo);
  $rows->bindParam(5, $arquivos);
  $rows->execute();

  $id = $db->lastInsertId();

  mkdir($_SERVER['DOCUMENT_ROOT'] . '/static/projetos/' . $id);

  return projeto_requestIDator($id);
}
