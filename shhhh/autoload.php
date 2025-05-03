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
  include $_SERVER['DOCUMENT_ROOT'] . '/404.php';
  die();
}

function erro_403()
{
  include $_SERVER['DOCUMENT_ROOT'] . '/403.php';
  die();
}

function getFileMimeType($file)
{
  if (str_ends_with(strtolower($file), '.css')) {
    return 'text/css';
  }
  if (str_ends_with(strtolower($file), '.js')) {
    return 'application/javascript';
  }
  if (str_ends_with(strtolower($file), '.mjs')) {
    return 'application/javascript';
  }
  if (str_ends_with(strtolower($file), '.json')) {
    return 'application/json';
  }
  if (str_ends_with(strtolower($file), '.xml')) {
    return 'application/xml';
  }
  if (str_ends_with(strtolower($file), '.svg')) {
    return 'image/svg+xml';
  }
  if (str_ends_with(strtolower($file), '.php')) {
    return 'application/x-httpd-php';
  }

  if (function_exists('finfo_file')) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $type = finfo_file($finfo, $file);
    finfo_close($finfo);
  } else {
    require_once $_SERVER['DOCUMENT_ROOT'] . 'mime.php';
    $type = mime_content_type($file);
  }

  if (!$type || in_array($type, array('application/octet-stream', 'text/plain'))) {
    require_once $_SERVER['DOCUMENT_ROOT'] . 'mime.php';
    $exifImageType = exif_imagetype($file);
    if ($exifImageType !== false) {
      $type = image_type_to_mime_type($exifImageType);
    }
  }

  return $type;
}
