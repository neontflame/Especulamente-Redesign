<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
?>
<?php
$query = $_GET['q'] ?? '';
$page = $_GET['page'] ?? 1;
$projetos = [];

$pages = coisos_tudo($projetos, 'projetos', $page, $query);

if ($query != '') {
  $coisodepagina = '?q=' . $query . '&';
} else {
  $coisodepagina = '?';
}
?>

<?php
$meta["titulo"] = "[Projetos <> PORTAL ESPECULAMENTE]";
$meta["descricao"] = "Como todo bom ESPECULATIVO, nossa mesa está sempre transbordando de ideias, e às vezes essas ideias se tornam PROJETOS!! Veja aqui todos os melhores jogos, artes, vídeos e tudo mais já criados!";
include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<div class="container">
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

  <img src="/elementos/pagetitles/projetos.png" class="inside_page_content" style="padding: 0px; margin-left: 4px; margin-bottom: 7px;">

  <div class="page_content" style="min-height: 486px;">
    <div class="inside_page_content">

      <?php if ($query != '') { ?>
        <div class="pesquisaThing">Resultados da pesquisa por <b>"<?php echo htmlspecialchars($query) . '"</b></div>';
                                                                } ?>
            <div class="projetos">
              <?php foreach ($projetos as $projeto) : ?>
                <div class="projeto" style="min-height:84px">
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

                  <!-- nem tudo precisa ter uma thumbnail! -->
                  <?php if ($projeto->thumbnail != null) { ?>
                    <a href="/projetos/<?= $projeto->id ?>" style="float:left; margin-right: 8px"><img style="max-width:96px; height:72px" src="/static/projetos/<?= ($projeto->id) ?>/thumb/<?= ($projeto->thumbnail) ?>"></a>
                  <?php } ?>
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
                  <a href="/projetos/<?= $coisodepagina ?>page=1">Início</a>
                  <p class="textinhoClaro">~</p>
                  <a href="/projetos/<?= $coisodepagina ?>page=<?= $page - 1 ?>">« Anterior</a>
                  <p class="textinhoClaro">~</p>
                <?php endif ?>
                <?php if ($page == 1) : ?>
                  <p class="textinhoClaro" style="margin-right: 4px;">Início ~ « Anterior ~ </a>
                  <?php endif ?>

                  <p>Página <?= $page ?> de <?= $pages ?></p>

                  <?php if ($page < $pages) : ?>
                    <p class="textinhoClaro">~</p>
                    <a href="/projetos/<?= $coisodepagina ?>page=<?= $page + 1 ?>">Próximo »</a>
                    <p class="textinhoClaro">~</p>
                    <a href="/projetos/<?= $coisodepagina ?>page=<?= $pages ?>">Fim</a>
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