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
