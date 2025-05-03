<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<div class="container">
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

  <div class="page_content" style="min-height: 486px">
    <div class="inside_page_content">
	  <style>
	  .labelManeira {
		  font-size:15px; 
		  font-weight:bold;
		  margin-top: 4px;
		  margin-bottom: 4px;
	  }
	  </style>
	  <p class="labelManeira">>> DESTAQUE</p>
	  <div class="separador" style="margin-bottom: 8px;"></div>
	  <a href="/projetos/ver.php?id=46"><img src="/elementos/destaques/vsespe.png"></a>
	  <div class="separador"></div>
	  <p class="labelManeira">>> PROJETOS RECENTES</p>
	  <div class="separador" style="margin-bottom: 8px;"></div>
	  <?php
	  $projetos = [];

	  $pages = coisos_tudo($projetos, 'projetos', 1, '', '', 3);
	  ?>
            <div class="projetos">
              <?php foreach ($projetos as $projeto) : ?>
                <div class="projeto" style="min-height:84px">
				  <?php if ($projeto->tipo == 'dl') : ?>
                  <a href="<?= $config['URL'] ?>/projetos/zipar.php?id=<?= $projeto->id ?>"><img src="/elementos/botaoTransferirProjetos.png"></a>
                  <?php endif ?>
				  
				  <?php if ($projeto->tipo == 'bg') : ?>
                  <a href="<?= $config['URL'] ?>/projetos/ver.php?id=<?= $projeto->id ?>"><img src="/elementos/botaoLerBlog.png"></a>
                  <?php endif ?>
				  
				  <?php if ($projeto->tipo == 'rt') : ?>
				  <a href="<?= $config['URL'] ?>/~<?= $projeto->arquivos_de_vdd ?>"><img src="/elementos/botaoVerResto.png"></a>
				  <?php endif ?>
				  
				  <?php if ($projeto->tipo == 'jg') : ?>
				  <a href="<?= $config['URL'] ?>/projetos/ver.php?id=<?= $projeto->id ?>"><img src="/elementos/botaoJogar.png"></a>
				  <?php endif ?>
				  
				  <?php if ($projeto->tipo == 'md') : ?>
				  <a href="<?= $config['URL'] ?>/projetos/ver.php?id=<?= $projeto->id ?>"><img src="/elementos/botaoVerMidia.png"></a>
				  <?php endif ?>
				  
				  <!-- nem tudo precisa ter uma thumbnail! -->
				  <?php if ($projeto->thumbnail != null) { ?>
                  <a href="<?= $config['URL'] ?>/projetos/ver.php?id=<?= $projeto->id ?>" style="float:left; margin-right: 8px"><img style="max-width:96px; height:72px" src="/static/projetos/<?= ($projeto->id) ?>/thumb/<?= ($projeto->thumbnail) ?>"></a>
				  <?php } ?>
                  <a class="autorDeProjeto" href="<?= $config['URL'] ?>/usuarios/<?= usuario_requestIDator($projeto->id_criador)->username ?>">
                    por <?= usuario_requestIDator($projeto->id_criador)->username ?>
                  </a>
                  <h2><a href="<?= $config['URL'] ?>/projetos/ver.php?id=<?= $projeto->id ?>"><?= $projeto->nome ?></a></h2>
                  <p><?= explode("\n", $projeto->descricao)[0] ?></p>
                </div>
              <?php endforeach ?>
            </div>
    </div>
  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>