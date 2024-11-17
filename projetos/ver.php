<?php
include $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
?>
<?php
if (!isset($_GET['id'])) {
  erro_404();
}
$id = $_GET['id'];

$projeto = projeto_requestIDator($id);
// EXPLODIR HOSTINGER
$arquivos = explode('\n', $projeto->arquivos);
?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<div class="container">
  <?php /* include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; */ ?>

  <div class="page_content" style="min-height: 486px; margin-left: 0">
    <div class="inside_page_content">
      <h1><?= $projeto->nome ?></h1>

      <?php if (str_ends_with($arquivos[0], '.sb2')) : ?>
        <iframe src="https://turbowarp.org/embed?project_url=<?= $config['URL'] ?>/static/projetos/<?= $projeto->id ?>/<?= $arquivos[0] ?>" width="482" height="412" allowtransparency="true" frameborder="0" scrolling="no" allowfullscreen></iframe>
      <?php endif ?>
    </div>
  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>