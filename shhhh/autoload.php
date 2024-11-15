<?php
include 'config.php';
include 'db.php';
include 'login_coisos.php';

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
