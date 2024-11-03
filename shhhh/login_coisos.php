<?php
session_start();

$usuario;

if (isset($_SESSION['id'])) {
  global $usuario;
  $usuario = [
    'id' => $_SESSION['id'],
    'username' => $_SESSION['username'],
  ];
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
