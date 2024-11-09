<?php
include 'config.php';
include 'login_coisos.php';
include 'db.php';

function redirect($location)
{
  header("Location: " . $location);
  die();
}
