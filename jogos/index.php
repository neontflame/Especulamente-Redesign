<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
?>
<?php
$query = $_GET['q'] ?? '';
$page = $_GET['page'] ?? 1;
$projetos = [];

$pages = coisos_tudo($projetos, 'projetos', $page, $query, ' WHERE tipo = "jg"');

if ($query != '') {
  $coisodepagina = '?q=' . $query . '&';
} else {
  $coisodepagina = '?';
}
?>

<?php
$meta["titulo"] = "[Jogos <> PORTAL ESPECULAMENTE]";
$meta["descricao"] = "Você já se sentiu tão entediado que poderia comer um cavalo? Pois bem, apresento a você o maravilhoso mundo dos VIDEOGAMES!! Divirta-se com os melhores jogos produzidos pelos ESPECULATIVOS!";
include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<div class="container">
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

  <img src="/elementos/pagetitles/jogos.png" class="inside_page_content" style="padding: 0px; margin-left: 4px; margin-bottom: 7px;">

  <div class="page_content" style="min-height: 486px;">
    <div class="inside_page_content">

      <?php if ($query != '') { ?>
        <div class="pesquisaThing">Resultados da pesquisa por <b>"<?php echo htmlspecialchars($query) . '"</b></div>';
                                                                } ?>
            <div class="projetos">
              <?php foreach ($projetos as $projeto) : ?>
                <div class="projeto" style="min-height:84px">
                  <a href="/projetos/<?= $projeto->id ?>" style="float:left; margin-right: 8px"><img style="max-width:96px; max-height:72px" src="
				  <?php if ($projeto->thumbnail != null) { ?>/static/projetos/<?= ($projeto->id) ?>/thumb/<?= ($projeto->thumbnail) ?>
				  <?php } else { ?>/static/thumb_padrao.png<?php } ?>
				  "></a>
                  <a class="autorDeProjeto" href="/usuarios/<?= usuario_requestIDator($projeto->id_criador)->username ?>">
                    por <?= usuario_requestIDator($projeto->id_criador)->username ?>
                  </a>
                  <h2><a href="/projetos/<?= $projeto->id ?>"><?= $projeto->nome ?></a></h2>
                  <p><?= markdown_apenas_texto(explode("\n", $projeto->descricao)[0]) ?></p>
                </div>
              <?php endforeach ?>

              <!-- here be pagination -->
              <div class="pagination">
                <?php if ($page > 1) : ?>
                  <a href="/jogos<?= $coisodepagina ?>page=1">Início</a>
                  <p class="textinhoClaro">~</p>
                  <a href="/jogos<?= $coisodepagina ?>page=<?= $page - 1 ?>">« Anterior</a>
                  <p class="textinhoClaro">~</p>
                <?php endif ?>
                <?php if ($page == 1) : ?>
                  <p class="textinhoClaro" style="margin-right: 4px;">Início ~ « Anterior ~ </a>
                  <?php endif ?>

                  <p>Página <?= $page ?> de <?= $pages ?></p>

                  <?php if ($page < $pages) : ?>
                    <p class="textinhoClaro">~</p>
                    <a href="/jogos<?= $coisodepagina ?>page=<?= $page + 1 ?>">Próximo »</a>
                    <p class="textinhoClaro">~</p>
                    <a href="/jogos<?= $coisodepagina ?>page=<?= $pages ?>">Fim</a>
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