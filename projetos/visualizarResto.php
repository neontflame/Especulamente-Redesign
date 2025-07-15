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

$projeto = projeto_requestARQUIVOSDEVDDator($pasta);

if ($projeto == null) {
  erro_404();
}

$links = ['', ''];
$estilosDeCoiso = ['color: #b1d9ff;', 'color: #b1d9ff;'];

// link apos
$stmt = $db->prepare("SELECT * FROM projetos WHERE tipo = 'rt' AND id > ? ORDER BY id ASC LIMIT 1");
$stmt->bindParam(1, $projeto->id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_OBJ);
if ($row != null) {
	$links[1] = 'href="/~' . $row->arquivos_de_vdd . '/"';
	$estilosDeCoiso[1] = '';
}

// link antes
$stmt = $db->prepare("SELECT * FROM projetos WHERE tipo = 'rt' AND id < ? ORDER BY id DESC LIMIT 1");
$stmt->bindParam(1, $projeto->id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_OBJ);
if ($row != null) {
	$links[0] = 'href="/~' . $row->arquivos_de_vdd . '/"';
	$estilosDeCoiso[0] = '';
}

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
        <a {$links[0]} style="padding-bottom: 12px; {$estilosDeCoiso[0]}">site anterior</a>
        <a {$links[1]} style="padding-bottom: 12px; {$estilosDeCoiso[1]}">proximo site</a>
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
    $contents = str_replace($body, $cabecalho . (isset($usuario) ? "<div id=\"ESPECULAMENTE_site\" style=\"padding-top: 24px;\">" : "<div>") . $body . "</div>", $contents);
  } else {
    $contents = $cabecalho . (isset($usuario) ? "<div id=\"ESPECULAMENTE_site\" style=\"padding-top: 24px;\">" : "<div>") .  $contents . "</div>";
  }
  echo $contents;
} else {
  header('Content-Type: ' . getFileMimeType($full_path));
  readfile($_SERVER['DOCUMENT_ROOT'] . '/static/projetos/' . $projeto->id . $path);
}
?>