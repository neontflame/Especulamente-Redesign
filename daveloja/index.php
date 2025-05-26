<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
login_obrigatorio($usuario);
?>

<?php
$meta["titulo"] = "[Loja de Davecoins <> PORTAL ESPECULAMENTE]";

$page = $_GET['page'] ?? 1;
$daveitens = [];

$pages = coisos_tudo($daveitens, 'daveitens', $page);

?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<link href="/cssDoDave.css" rel="stylesheet" type="text/css" />
<div class="container">
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

  <div class="page_content" style="height: 556px">
    <img src="/elementos/pagetitles/davelojaNormal.png" class="inside_page_content" style="padding: 0px; margin-bottom: 7px;">
    <div class="inside_page_content">
      <!-- nao sao projetos propriamente ditos mas we ball regardless -->
      <p style="color: #555555; margin-left: 2px; margin-top: 2px;">No momento corrente, você possui <b style="color: black;"><?= $usuario->davecoins ?> davecoins</b></p>
      <div class="projetos">
        <?php foreach ($daveitens as $daveitem) : ?>
          <div class="projeto">
            <img src="/daveloja/itens/<?= $daveitem->imagem ?>" style="float: left; margin-right: 8px;">
            <div class="projetoSide">
              <?php if ($daveitem->compravel == 1) { ?>
                <a href="/daveloja/checkout.php?id=<?= $daveitem->id ?>"><img src="/elementos/comprar.png"></a>
              <?php } else { ?>
                <img src="/elementos/semEstoque.png" style="float: right;">
              <?php } ?>
              <p class="autorDeProjeto"><?= $daveitem->daveprice ?> davecoins</p>
            </div>
            <h2><?= $daveitem->nome ?></h2>
            <p><?= $daveitem->descricao ?></p>
          </div>
        <?php endforeach ?>

        <!-- here be pagination -->
        <div class="pagination">
          <?php if ($page > 1) : ?>
            <a href="/daveloja/?page=1">Início</a>
            <p class="textinhoClaro">~</p>
            <a href="/daveloja/?page=<?= $page - 1 ?>">« Anterior</a>
            <p class="textinhoClaro">~</p>
          <?php endif ?>
          <?php if ($page == 1) : ?>
            <p class="textinhoClaro" style="margin-right: 4px;">Início ~ « Anterior ~ </a>
            <?php endif ?>

            <p>Página <?= $page ?> de <?= $pages ?></p>

            <?php if ($page < $pages) : ?>
              <p class="textinhoClaro">~</p>
              <a href="/daveloja/?page=<?= $page + 1 ?>">Próximo »</a>
              <p class="textinhoClaro">~</p>
              <a href="/daveloja/?page=<?= $pages ?>">Fim</a>
            <?php endif ?>
            <?php if ($page == $pages) : ?>
              <p class="textinhoClaro"> ~ Próximo » ~ Fim</a>
              <?php endif ?>
        </div>
      </div>
    </div>
  </div>

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>