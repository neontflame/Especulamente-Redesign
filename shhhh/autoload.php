<?php
include 'config.php';
include 'db.php';
include 'login_coisos.php';
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
  if(!isset($_SESSION)) { session_start(); }
  die();
}

function erro_403()
{
  include '../403.php';
  if(!isset($_SESSION)) { session_start(); }
  die();
}
