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
$arquivos_de_vdd = explode('\n', $projeto->arquivos_de_vdd);
?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<div class="container">
  <?php /* include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; */ ?>

  <div class="page_content" style="min-height: 486px; margin-left: 0">
    <div class="inside_page_content">
      <h1><?= $projeto->nome ?></h1>
		
      <?php if ($projeto->tipo == 'dl') : ?>
	  <!-- downloadavel -->
        <ul>
          <?php foreach ($arquivos as $i => $arquivo) : ?>
            <li><a href="<?= $config['URL'] ?>/static/projetos/<?= $projeto->id ?>/<?= $arquivo ?>" download="<?= $arquivos_de_vdd[$i] ?>"><?= $arquivos_de_vdd[$i] ?></a></li>
          <?php endforeach ?>
        </ul>
		
		<a href="<?= $config['URL'] ?>/projetos/zipar.php?id=<?= $projeto->id ?>">Baixar todos os arquivos!</a>
      <?php endif ?>
	  
      <?php if ($projeto->tipo == 'md') : ?>
	  <!-- midia -->
        <object width="600" height="360" data="/elementos/vedorDImagem.swf"><param name="flashvars" value="server=<?= $config['URL'] ?>/&projectid=<?= $projeto->id ?>"/></object>
		
		<a href="<?= $config['URL'] ?>/projetos/zipar.php?id=<?= $projeto->id ?>">Baixar todos os arquivos!</a>
      <?php endif ?>

      <?php if (str_ends_with($arquivos[0], '.sb2')) : ?>
        <iframe src="https://turbowarp.org/embed?project_url=<?= $config['URL'] ?>/static/projetos/<?= $projeto->id ?>/<?= $arquivos[0] ?>" width="482" height="412" allowtransparency="true" frameborder="0" scrolling="no" allowfullscreen></iframe>
      <?php endif ?>
    </div>
  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>