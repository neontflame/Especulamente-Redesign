<?php
include $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
  if (isset($_POST['tipo'])) {
    $tipo = $_POST['tipo'];
    $id = $_POST['id'];
    $reacao = $_POST['reacao'];

    if ($reacao != "mitada" && $reacao != "sojada") {
      die();
    }

    $count = reagir($usuario->id, $id, $tipo, $reacao);
    if ($count == -1) {
      $count = desreagir($usuario->id, $id, $tipo, $reacao);
      echo $count;
    } else {
      echo $count;
    }
  } else {
    // qq tu quer q eu faça bro
  }
}
