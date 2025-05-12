<?php
session_start();

/** @var object|null $usuario */
$usuario = null;

if (isset($_SESSION['id'])) {
  global $usuario;
  $usuario = usuario_requestIDator($_SESSION['id']);
}

function fazer_logout()
{
  session_unset();
}

function login_obrigatorio($usuario)
{
  if (!isset($usuario)) {
    http_response_code(403);
    include $_SERVER['DOCUMENT_ROOT'] . "/403.php";
    die();
  }
}
