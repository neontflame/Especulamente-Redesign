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
						<?php
						if (isset($projeto->data)) { echo velhificar_data($projeto->data); } else { echo 'data nula WTF???'; } 
						?>
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
function renderarProjGrade($projeto) { ?>
                <div class="item">
                  <a href="/projetos/<?= $projeto->id ?>"><img src="
					<?php
                $tiposDeImagem = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];
                $tiposDeVideo = ['mp4', 'ogg', 'avi', 'mkv'];
                $arquivo = explode('\n', $projeto->arquivos)[0];
                $eh_um_video = in_array(pathinfo($arquivo, PATHINFO_EXTENSION), $tiposDeVideo);
                $eh_uma_imagem = in_array(pathinfo($arquivo, PATHINFO_EXTENSION), $tiposDeImagem);

                if ($projeto->thumbnail != null) {
                  echo '/static/projetos/' . $projeto->id . '/thumb/' . $projeto->thumbnail;
                } else {
                  if ($eh_um_video) {
                    echo '/elementos/vedor_d_imagem/video_coiso.png';
                  } else {
					if ($eh_uma_imagem) {
						echo '/static/projetos/' . $projeto->id . '/' . $arquivo;
					} else {
						echo '/static/thumb_padrao.png';
					}
                  }
                }

          ?>
					"></a>
                  <a href="/projetos/<?= $projeto->id ?>"><?= $projeto->nome ?></a>
                  <div><a class="autorItem" href="/usuarios/<?= usuario_requestIDator($projeto->id_criador)->username ?>">por <?= usuario_requestIDator($projeto->id_criador)->username ?></a>
				  <a class="autorItem"><?php if (isset($projeto->data)) { echo velhificar_data($projeto->data); } else { echo 'data nula WTF???'; } ?></a></div>
                </div>
				
<?php }

function nomePorTipo($tipo) {
	switch ($tipo) {
		case 'dl':
			return 'Downloadáveis';
		case 'jg':
			return 'Jogos';
		case 'md':
			return 'Mídia';
		case 'bg':
			return 'Blogs';
		case 'rt':
			return 'O resto...';
		default:
			return 'Projetos';
	}
}

function pagetitlePorTipo($tipo) {
	switch ($tipo) {
		case 'jg':
			return 'jogos';
		case 'md':
			return 'midia';
		case 'bg':
			return 'blogs';
		case 'rt':
			return 'resto';
		default:
			return 'projetos';
	}
}

function descPorTipo($tipo) {
	switch ($tipo) {
		case 'jg':
			return "Você já se sentiu tão entediado que poderia comer um cavalo? Pois bem, apresento a você o maravilhoso mundo dos VIDEOGAMES!! Divirta-se com os melhores jogos produzidos pelos ESPECULATIVOS!";
		case 'md':
			return "A arte é a forma de expressão mais pura do ser humano. Através dessas lindas criações dos ESPECULATIVOS, você conhecerá o que cada um deles tem na cabeça, pelo bem ou pelo mal.";
		case 'bg':
			return "Pensamentos estão em alta! Pense mais e leia mais com os BLOGS dos ESPECULATIVOS!!";
		case 'rt':
			return "Às vezes, um único site não é o suficiente. Ah, não não não. Os ESPECULATIVOS precisam de mais, nós precisamos conter O RESTO da nossa criatividade em um site próprio, criado em nossa própria imagem. Ide, meu filho, e criai o seu RESTO!";
		default:
			return "Como todo bom ESPECULATIVO, nossa mesa está sempre transbordando de ideias, e às vezes essas ideias se tornam PROJETOS!! Veja aqui todos os melhores jogos, artes, vídeos e tudo mais já criados!";
	}
}