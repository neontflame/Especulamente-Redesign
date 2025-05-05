<?php
include $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
include $_SERVER['DOCUMENT_ROOT'] . '/shhhh/config.php';
include $_SERVER['DOCUMENT_ROOT'] . '/shhhh/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/shhhh/login_coisos.php';
include $_SERVER['DOCUMENT_ROOT'] . '/elementos/vedor_d_comentario/vdc.php';
include $_SERVER['DOCUMENT_ROOT'] . '/elementos/reajor_d_reagida/rdr.php';

function redirect($location)
{
  header("Location: " . $location);
  die();
}

function erro_404()
{
  include $_SERVER['DOCUMENT_ROOT'] . '/404.php';
  die();
}

function erro_403()
{
  include $_SERVER['DOCUMENT_ROOT'] . '/403.php';
  die();
}

function getFileMimeType($file)
{
  if (str_ends_with(strtolower($file), '.css')) {
	return 'text/css';
  }
  if (str_ends_with(strtolower($file), '.js')) {
	return 'application/javascript';
  }
  if (str_ends_with(strtolower($file), '.mjs')) {
	return 'application/javascript';
  }
  if (str_ends_with(strtolower($file), '.json')) {
	return 'application/json';
  }
  if (str_ends_with(strtolower($file), '.xml')) {
	return 'application/xml';
  }
  if (str_ends_with(strtolower($file), '.svg')) {
	return 'image/svg+xml';
  }
  if (str_ends_with(strtolower($file), '.php')) {
	return 'application/x-httpd-php';
  }

  if (function_exists('finfo_file')) {
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	$type = finfo_file($finfo, $file);
	finfo_close($finfo);
  } else {
	require_once $_SERVER['DOCUMENT_ROOT'] . 'mime.php';
	$type = mime_content_type($file);
  }

  if (!$type || in_array($type, array('application/octet-stream', 'text/plain'))) {
	require_once $_SERVER['DOCUMENT_ROOT'] . 'mime.php';
	$exifImageType = exif_imagetype($file);
	if ($exifImageType !== false) {
	  $type = image_type_to_mime_type($exifImageType);
	}
  }

  return $type;
}

function renderarProjeto($projeto, $botaoSim = true, $thumbObrigatoria = false) { ?>
				<div class="projeto" style="min-height:84px">
					<div class="projetoSide">
					<?php if ($botaoSim) : ?>
					  <?php if ($projeto->tipo == 'dl') : ?>
						<a href="/projetos/<?= $projeto->id ?>/zipar"><img src="/elementos/botaoTransferirProjetos.png"></a>
					  <?php endif ?>
					  <?php if ($projeto->tipo == 'bg') : ?>
						<a href="/projetos/<?= $projeto->id ?>"><img src="/elementos/botaoLerBlog.png"></a>
					  <?php endif ?>
					  <?php if ($projeto->tipo == 'rt') : ?>
						<a href="/~<?= $projeto->arquivos_de_vdd ?>"><img src="/elementos/botaoVerResto.png"></a>
					  <?php endif ?>

					  <?php if ($projeto->tipo == 'jg') : ?>
						<a href="/projetos/<?= $projeto->id ?>"><img src="/elementos/botaoJogar.png"></a>
					  <?php endif ?>

					  <?php if ($projeto->tipo == 'md') : ?>
						<a href="/projetos/<?= $projeto->id ?>"><img src="/elementos/botaoVerMidia.png"></a>
					  <?php endif ?>
					  <br>
					<?php endif ?>
					  <a class="autorDeProjeto" href="/usuarios/<?= usuario_requestIDator($projeto->id_criador)->username ?>">
						por <?= usuario_requestIDator($projeto->id_criador)->username ?>
					  </a>
					  <br>
					  <p class="autorDeProjeto">
						<?= velhificar_data($projeto->data); ?>
					  </p>
					</div>
				  <!-- nem tudo precisa ter uma thumbnail! -->
				  <?php if ($projeto->thumbnail != null) { ?>
					<a href="/projetos/<?= $projeto->id ?>" style="float:left; margin-right: 8px"><img style="max-width:96px; height:72px" src="/static/projetos/<?= ($projeto->id) ?>/thumb/<?= ($projeto->thumbnail) ?>"></a>
				  <?php } else if ($thumbObrigatoria) { ?>
					<a href="/projetos/<?= $projeto->id ?>" style="float:left; margin-right: 8px"><img style="max-width:96px; height:72px" src="/static/thumb_padrao.png"></a>
				  <?php } ?>

				  <h2><a href="/projetos/<?= $projeto->id ?>"><?= $projeto->nome ?></a></h2>
				<p><?= markdown_apenas_texto(explode("\n", $projeto->descricao)[0]) ?></p>
			</div>
<?php }
