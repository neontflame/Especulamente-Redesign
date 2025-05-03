<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
?>
<?php
$query = $_GET['q'] ?? '';
$page = $_GET['page'] ?? 1;
$projetos = [];

$pages = coisos_tudo($projetos, 'projetos', $page, $query, ' WHERE tipo = "rt"');

if ($query != '') {
  $coisodepagina = '?q=' . $query . '&';
} else {
  $coisodepagina = '?';
}
?>

<?php
$meta["titulo"] = "[O resto... <> PORTAL ESPECULAMENTE]";
$meta["descricao"] = "Às vezes, um único site não é o suficiente. Ah, não não não. Os ESPECULATIVOS precisam de mais, nós precisamos conter O RESTO da nossa criatividade em um site próprio, criado em nossa própria imagem. Ide, meu filho, e criai o seu RESTO!";
include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<div class="container">
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

  <img src="/elementos/pagetitles/resto.png" class="inside_page_content" style="padding: 0px; margin-left: 4px; margin-bottom: 7px;">

  <div class="page_content" style="min-height: 486px;">
    <div class="inside_page_content">

      <?php if ($query != '') { ?>
        <div class="pesquisaThing">Resultados da pesquisa por <b>"<?php echo htmlspecialchars($query) . '"</b></div>';
                                                                } ?>
            <div class="projetos">
              <?php foreach ($projetos as $projeto) : ?>
                <div class="projeto" style="min-height:84px">
                  <a href="/~<?= $projeto->arquivos_de_vdd ?>"><img src="/elementos/botaoVerResto.png"></a>
                  <a href="/projetos/<?= $projeto->id ?>" style="float:left; margin-right: 8px"><img style="width:96px; height:72px" src="
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
                  <a href="/resto<?= $coisodepagina ?>page=1">Início</a>
                  <p class="textinhoClaro">~</p>
                  <a href="/resto<?= $coisodepagina ?>page=<?= $page - 1 ?>">« Anterior</a>
                  <p class="textinhoClaro">~</p>
                <?php endif ?>
                <?php if ($page == 1) : ?>
                  <p class="textinhoClaro" style="margin-right: 4px;">Início ~ « Anterior ~ </a>
                  <?php endif ?>

                  <p>Página <?= $page ?> de <?= $pages ?></p>

                  <?php if ($page < $pages) : ?>
                    <p class="textinhoClaro">~</p>
                    <a href="/resto<?= $coisodepagina ?>page=<?= $page + 1 ?>">Próximo »</a>
                    <p class="textinhoClaro">~</p>
                    <a href="/resto<?= $coisodepagina ?>page=<?= $pages ?>">Fim</a>
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