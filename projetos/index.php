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
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

  <img src="/elementos/pagetitles/projetos.png" class="inside_page_content" style="padding: 0px; margin-left: 4px; margin-bottom: 7px;">
	
  <div class="page_content" style="min-height: 486px;">
    <div class="inside_page_content">
	
      <div class="projetos">
        <?php foreach ($projetos as $projeto) : ?>
          <div class="projeto">
            <a href="<?= $config['URL'] ?>/projetos/ver.php?id=<?= $projeto->id ?>"><img src="/elementos/botaoTransferir.png"></a>
            <a class="autorDeProjeto" href="<?= $config['URL'] ?>/usuarios/<?= usuario_requestIDator($projeto->id_criador)->username ?>">
			feito por <?= usuario_requestIDator($projeto->id_criador)->username ?>
			</a>
            <h2><?= $projeto->nome ?></h2>
            <p><?= $projeto->descricao ?></p>
          </div>
        <?php endforeach ?>

		<!-- here be pagination -->
        <div class="pagination">
          <?php if ($page > 1) : ?>
            <a href="<?= $config['URL'] ?>/projetos/?page=1">Início</a>
			<p class="textinhoClaro">~</p>
            <a href="<?= $config['URL'] ?>/projetos/?page=<?= $page - 1 ?>">« Anterior</a>
			<p class="textinhoClaro">~</p>
          <?php endif ?>
		  <?php if ($page == 1) : ?>
		    <p class="textinhoClaro" style="margin-right: 4px;">Início ~ « Anterior ~ </a>
		  <?php endif ?>

          <p>Página <?= $page ?> de <?= $pages ?></p>
		  
          <?php if ($page < $pages) : ?>
			<p class="textinhoClaro">~</p>
            <a href="<?= $config['URL'] ?>/projetos/?page=<?= $page + 1 ?>">Próximo »</a>
			<p class="textinhoClaro">~</p>
            <a href="<?= $config['URL'] ?>/projetos/?page=<?= $pages ?>">Fim</a>
          <?php endif ?>
		  <?php if ($page == $pages) : ?>
		    <p class="textinhoClaro"> ~ Próximo » ~ Fim</a>
		  <?php endif ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>