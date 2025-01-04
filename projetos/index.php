<?php
include $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
?>
<?php
$page = $_GET['page'] ?? 1;
$projetos = [];

$pages = projetos_tudo($projetos, $page);
?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<div class="container">
  <?php /* include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; */ ?>

  <div class="page_content" style="min-height: 486px; margin-left: 0">
    <div class="inside_page_content">
      <h1>Projetos</h1>

      <div class="projetos">
        <?php foreach ($projetos as $projeto) : ?>
          <div class="projeto">
            <h2><?= $projeto->nome ?></h2>
            <p><?= $projeto->descricao ?></p>
            <a href="<?= $config['URL'] ?>/projetos/ver.php?id=<?= $projeto->id ?>">Ver</a>
          </div>
        <?php endforeach ?>

        <div class="pagination">
          <?php if ($page > 1) : ?>
            <a href="<?= $config['URL'] ?>/projetos/?page=<?= $page - 1 ?>">Anterior</a>
          <?php endif ?>

          <?php if ($page < $pages) : ?>
            <a href="<?= $config['URL'] ?>/projetos/?page=<?= $page + 1 ?>">Próximo</a>
          <?php endif ?>

          <p>Página <?= $page ?> de <?= $pages ?></p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>