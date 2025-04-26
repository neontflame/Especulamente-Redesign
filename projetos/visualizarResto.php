<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
?>
<?php
if (!isset($_GET['pasta'])) {
  erro_404();
}
$pasta = $_GET['pasta'];
$path = $_GET['path'] ?? "";
if (str_ends_with($path, '/')) {
  $path = substr($path, 0, -1);
}
if ($path == "") {
  redirect("/~" . $pasta . "/index.html");
}

$dskjfds = pfp($usuario);

$cabecalho = isset($usuario) ?
  <<<HTML
  <div class="ESPECULAMENTE_masthead">
    <div class="insideMasthead">
      <div class="link">
        <a href="/"
          ><img
            src="/static/resto/header/headerLogo.png"
            onmouseover="this.src='/static/resto/header/headerLogoHover.png';"
            onmouseout="this.src='/static/resto/header/headerLogo.png';"
        /></a>
        <a href="" style="padding-bottom: 12px">site anterior</a>
        <a href="" style="padding-bottom: 12px">proximo site</a>
      </div>
      <div class="twoink">
        <a href="/usuarios/{$usuario->username}"
          ><img src="{$dskjfds}" width="24" height="24"
        /></a>
        <a href="/usuarios/{$usuario->username}" style="padding-bottom: 14px">{$usuario->username}</a>
      </div>
    </div>
  </div>
  <style>
    /* CSS layout */
    body {
      margin: auto;
      padding: 0px;
    }

    .ESPECULAMENTE_masthead {
      position: absolute;
      width: 100%;
      background-image: url("/static/resto/header/headerBagulhoEspecifico.png");
      height: 24px;
    }

    .ESPECULAMENTE_masthead .insideMasthead {
      width: 633px;
      margin: auto;
    }

    .ESPECULAMENTE_masthead .insideMasthead .link {
      float: left;
      display: inline;
    }

    .ESPECULAMENTE_masthead .insideMasthead .link a {
      vertical-align: middle;
      color: #ffffff;
      font-family: "Verdana";
      font-weight: bold;
      font-size: 12px;
      margin-left: 32px;
      text-decoration: none;
    }

    .ESPECULAMENTE_masthead .insideMasthead .twoink {
      float: right;
      display: inline;
    }

    .ESPECULAMENTE_masthead .insideMasthead .twoink a {
      vertical-align: middle;
      color: #ffffff;
      font-family: "Verdana";
      font-weight: bold;
      font-size: 12px;
      margin-left: 8px;
      text-decoration: none;
    }
  </style>
  HTML : "";

$projeto = projeto_requestARQUIVOSDEVDDator($pasta);
if ($projeto == null) {
  erro_404();
}

$full_path = $_SERVER['DOCUMENT_ROOT'] . '/static/projetos/' . $projeto->id . $path;
if (!file_exists($full_path) || is_dir($full_path)) {
  $four04path = $_SERVER['DOCUMENT_ROOT'] . '/static/projetos/' . $projeto->id . '/404.html';
  if (file_exists($four04path)) {
    header("HTTP/1.0 404 Not Found");
    $full_path = $four04path;
  } else {
    erro_404();
  }
}
if (str_ends_with($full_path, '.html') || str_ends_with($full_path, '.htm') || str_ends_with($full_path, '.xhtml')) {
  header('Content-Type: ' . 'text/html; charset=UTF-8');
  $contents = file_get_contents($full_path);
  // Veja onde está o "<body>" e coloque html la
  preg_match('/<body[^>]*>(.*)<\/body>/si', $contents, $matches);
  if (isset($matches[1])) {
    $body = $matches[1];
    $contents = str_replace($body, $cabecalho . (isset($usuario) ? "<div style=\"padding-top: 24px;\">" : "<div>") . $body . "</div>", $contents);
  } else {
    $contents = $cabecalho . (isset($usuario) ? "<div style=\"padding-top: 24px;\">" : "<div>") .  $contents . "</div>";
  }
  echo $contents;
} else {
  header('Content-Type: ' . getFileMimeType($full_path));
  readfile($_SERVER['DOCUMENT_ROOT'] . '/static/projetos/' . $projeto->id . $path);
}

function getFileMimeType($file)
{
  if (function_exists('finfo_file')) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $type = finfo_file($finfo, $file);
    finfo_close($finfo);
  } else {
    require_once 'mime.php';
    $type = mime_content_type($file);
  }

  if (!$type || in_array($type, array('application/octet-stream', 'text/plain'))) {
    require_once 'mime.php';
    $exifImageType = exif_imagetype($file);
    if ($exifImageType !== false) {
      $type = image_type_to_mime_type($exifImageType);
    }
  }

  return $type;
}
?>