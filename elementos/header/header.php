<?php
// copiado do 1604chan hihi
$banners = array();
$dir = $_SERVER['DOCUMENT_ROOT'] . "/elementos/header/headers/";
if ($handle = scandir($dir)) {
        foreach ($handle as $target) {
                if (!in_array($target, [".", ".."])) {
                        $banners[] = $target;
                }
        }
}

$banner = $banners[array_rand($banners)];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr" xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta content="pt-br" http-equiv="Content-Language" />
  <title><?= $titulo ?? "[PORTAL ESPECULAMENTE]" ?></title>
  <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
  <link href="/cssManeiro.css" rel="stylesheet" type="text/css" />
  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
</head>

<body>
  <div class="bodyPrincipal">
    <div class="top_nav">
      <!-- HEADER -->
      <div class="headerAwesome">
        <!-- LINKS DO TOPO DO HEADER-->
        <div class="coolOrganizationy">
          <div class="coolLinkery">
            <a href="/projetos/">PROJETOS</a>
            <a href="">JOGOS</a>
            <a href="">MÍDIA</a>
            <a href="">BLOGS</a>
            <a href="">"O resto..."</a>
          </div>
          <input type="text" id="search" name="search" placeholder="Pesquise algo lol" class="coolSearchBar" style="height: 18px; width: 201px" />
        </div>
        <!-- FIM DOS LINKS DO TOPO DO HEADER-->

        <div class="coolBannery">
          <a href="/">
            <?php if (str_ends_with($banner, '.swf')) : ?>
              <object width="633" height="110" style="display: inline-block">
                <param name="movie" value="/elementos/header/headers/<?= $banner ?>">
                <embed src="/elementos/header/headers/<?= $banner ?>" width="633" height="110">
                </embed>
              </object>
            <?php else : ?>
              <img alt="especula header" src="/elementos/header/headers/<?= $banner ?>" width="633" height="110" />
            <?php endif; ?>
          </a>
        </div>

        <!-- LINKS ABAIXO DO BANNER -->
        <div class="coolSubHeadery">
          <div class="coolLinkery">
            <a href="">AMIGOS</a>
            <a href="https://1604chan.fupi.cat" target="_blank">BOARDS</a>
          </div>

          <div class="coolUsery">
            <?php if (isset($usuario)) : ?>
              <div class="links">
                Olá novamente, <a href="/usuarios/<?= $usuario->username ?>"><?= $usuario->username ?></a>
                <button id="headerSeta"></button>
                <div id="headerMenu">
                  <a href="/usuarios/<?= $usuario->username ?>">Perfil</a>
                  <a href="/configuracoes.php">Configurações</a>
                  <a href="/convites.php">Seus convites</a>
                  <hr>
                  <a href="/sair.php">Sair</a>
                </div>
              </div>
            <?php else : ?>
              <div class="links" style="margin-top: 1px; margin-right: 10px;"><a href="/registro.php">Criar conta</a> | <a href="/entrar.php">Entrar</a></div>
            <?php endif ?>
          </div>

        </div>
        <!-- FIM DOS LINKS ABAIXO DO BANNER -->
      </div>
      <!-- FIM DO HEADER -->
    </div>