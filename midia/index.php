<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
?>
<?php
$query = $_GET['q'] ?? '';
$page = $_GET['page'] ?? 1;
$projetos = [];

$pages = coisos_tudo($projetos, 'projetos', $page, $query, ' WHERE tipo = "md"', 9);

if ($query != '') {
  $coisodepagina = '?q=' . $query . '&';
} else {
  $coisodepagina = '?';
}
?>

<?php
$meta["titulo"] = "[Mídia <> PORTAL ESPECULAMENTE]";
$meta["descricao"] = "A arte é a forma de expressão mais pura do ser humano. Através dessas lindas criações dos ESPECULATIVOS, você conhecerá o que cada um deles tem na cabeça, pelo bem ou pelo mal.";
include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<div class="container">
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

  <img src="/elementos/pagetitles/midia.png" class="inside_page_content" style="padding: 0px; margin-left: 4px; margin-bottom: 7px;">

  <div class="page_content" style="min-height: 486px;">
    <style>

    </style>
    <div class="inside_page_content">

      <?php if ($query != '') { ?>
        <div class="pesquisaThing">Resultados da pesquisa por <b>"<?php echo htmlspecialchars($query) . '"</b></div>';
                                                                } ?>
            <div class="projetos">
              <?php foreach ($projetos as $projeto) : ?>
                <div class="item">
                  <a href="/projetos/<?= $projeto->id ?>"><img src="
					<?php
                $tiposDeVideo = ['mp4', 'ogg', 'avi', 'mkv'];
                $arquivo = explode('\n', $projeto->arquivos)[0];
                $eh_um_video = in_array(pathinfo($arquivo, PATHINFO_EXTENSION), $tiposDeVideo);

                if ($projeto->thumbnail != null) {
                  echo '/static/projetos/' . $projeto->id . '/thumb/' . $projeto->thumbnail;
                } else {
                  if ($eh_um_video) {
                    echo '/elementos/vedor_d_imagem/video_coiso.png';
                  } else {
                    echo '/static/projetos/' . $projeto->id . '/' . $arquivo;
                  }
                }

          ?>
					"></a>
                  <a href="/projetos/<?= $projeto->id ?>"><?= $projeto->nome ?></a>
                  <div><a class="autorItem" href="/usuarios/<?= usuario_requestIDator($projeto->id_criador)->username ?>">por <?= usuario_requestIDator($projeto->id_criador)->username ?></a>
				  <a class="autorItem"><?php if (isset($projeto->data)) { echo velhificar_data($projeto->data); } else { echo 'data nula WTF???'; } ?></a></div>
                </div>
              <?php endforeach ?>

              <!-- here be pagination -->
              <div class="pagination">
                <?php if ($page > 1) : ?>
                  <a href="/midia<?= $coisodepagina ?>page=1">Início</a>
                  <p class="textinhoClaro">~</p>
                  <a href="/midia<?= $coisodepagina ?>page=<?= $page - 1 ?>">« Anterior</a>
                  <p class="textinhoClaro">~</p>
                <?php endif ?>
                <?php if ($page == 1) : ?>
                  <p class="textinhoClaro" style="margin-right: 4px;">Início ~ « Anterior ~ </a>
                  <?php endif ?>

                  <p>Página <?= $page ?> de <?= $pages ?></p>

                  <?php if ($page < $pages) : ?>
                    <p class="textinhoClaro">~</p>
                    <a href="/midia<?= $coisodepagina ?>page=<?= $page + 1 ?>">Próximo »</a>
                    <p class="textinhoClaro">~</p>
                    <a href="/midia<?= $coisodepagina ?>page=<?= $pages ?>">Fim</a>
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