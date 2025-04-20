<?php
include $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
include $_SERVER['DOCUMENT_ROOT'] . '/shhhh/config.php';
include $_SERVER['DOCUMENT_ROOT'] . '/shhhh/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/shhhh/login_coisos.php';
include $_SERVER['DOCUMENT_ROOT'] . '/elementos/vedor_d_comentario/vdc.php';
include $_SERVER['DOCUMENT_ROOT'] . '/elementos/reajor_d_reagida/rdr.php';

function redirect($location)
{
  header("Location: " . $location);
  die();
}

function erro_404()
{
  include '../404.php';
  die();
}

function erro_403()
{
  include '../403.php';
  die();
}
