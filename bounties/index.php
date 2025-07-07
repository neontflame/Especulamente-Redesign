<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
login_obrigatorio($usuario);
?>

<?php
$meta["titulo"] = "[𝓑𝓸𝓾𝓷𝓽𝓲𝓯𝓾𝓵 𝓑𝓸𝓾𝓷𝓽𝓲𝓮𝓼 do dave <> PORTAL ESPECULAMENTE]";
?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<link href="/cssDoDave.css" rel="stylesheet" type="text/css" />
<div class="container">
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

  <div class="page_content" style="height: 556px">
    <img src="/elementos/pagetitles/bountiful-bounties.png" class="inside_page_content" style="padding: 0px; margin-bottom: 7px;">
    <div class="inside_page_content">
    </div>
  </div>

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>