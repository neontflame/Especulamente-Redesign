<?php
include $_SERVER['DOCUMENT_ROOT'] . '/shhhh/login_coisos.php';
include $_SERVER['DOCUMENT_ROOT'] . '/shhhh/db.php';

function redirect($location)
{
  header("Location: " . $location);
  die();
}
