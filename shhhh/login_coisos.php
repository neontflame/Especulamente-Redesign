<?php
session_start();

$usuario;

if (isset($_SESSION['id'])) {
  $usuario = [
    'id' => $_SESSION['id'],
    'username' => $_SESSION['username'],
  ];
}

function fazer_logout()
{
  session_unset();
}
