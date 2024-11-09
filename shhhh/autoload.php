<?php
include 'config.php';
include 'login_coisos.php';
include 'db.php';

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
