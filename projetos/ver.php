<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
?>
<?php
if (!isset($_GET['id'])) {
  erro_404();
}
$id = $_GET['id'];

$projeto = projeto_requestIDator($id);
if ($projeto == null) {
  erro_404();
}
// EXPLODIR HOSTINGER
$arquivos = explode('\n', $projeto->arquivos);
$arquivos_de_vdd = explode('\n', $projeto->arquivos_de_vdd);
$arquivo_vivel = explode('\n', $projeto->arquivo_vivel);

// bug q a gente surpreendentemente nao tinha pego
$projeto_e_meu = false;

if (isset($usuario)) {
  $projeto_e_meu = $projeto->id_criador == $usuario->id;
}

?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<div class="container">
  <?php /* include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; */ ?>

  <div class="page_content" style="min-height: 120px; margin-left: 0">
    <div class="projTitulo">
      <?php if ($projeto_e_meu) : ?>
        <a href="<?= $config['URL'] ?>/projetos/editar.php?id=<?= $projeto->id ?>" style="float:right; margin:8px;"><img src="/elementos/botaoEditar.png"></a>
      <?php endif ?>
      <?php if ($arquivos[0] != '') : ?>
        <a href="<?= $config['URL'] ?>/projetos/zipar.php?id=<?= $projeto->id ?>" style="float:right; margin:8px;"><img src="/elementos/botaoTransferir.png"></a>
      <?php endif ?>

      <h1><i><?= $projeto->nome ?></i></h1>
      <p>por <a href="/usuarios/<?= usuario_requestIDator($projeto->id_criador)->username ?>"><?= usuario_requestIDator($projeto->id_criador)->username ?></a></p>
    </div>
    <div class="inside_page_content">
      <?php if ($projeto->tipo == 'jg') : ?>
        <!-- Embed -->
        <?php if (str_ends_with($arquivo_vivel[0], '.swf')) : ?>
          <div style="margin: 0 auto 16px; width: -moz-fit-content; width: intrinsic; width: fit-content;">
            <object width="auto" height="auto" data="<?= $config['URL'] . '/static/projetos/' . $projeto->id . '/' . $arquivo_vivel[1] ?>" allowfullscreen="true">
            </object>
          </div>
        <?php endif; ?>
        <?php if (str_ends_with($arquivo_vivel[0], '.zip')) : ?>
          <iframe width="100%" height="360" src="<?= $config['URL'] . '/static/projetos/' . $projeto->id . '/jogo/index.html' ?>" frameborder="0" allowfullscreen="true"></iframe>
        <?php endif; ?>
      <?php endif; ?>

      <?php if (($projeto->tipo == 'dl' || $projeto->tipo == 'jg') && $arquivos[0] != '') : ?>
        <!-- downloadavel -->
        <!-- isso tecnicamente nao sao projetos mas nada me impede de reusar o css deles lol -->
        <div class=" projetos">
          <?php foreach ($arquivos as $i => $arquivo) : ?>
            <div class="projeto">
              <a href="<?= $config['URL'] ?>/static/projetos/<?= $projeto->id ?>/<?= $arquivo ?>" download="<?= $arquivos_de_vdd[$i] ?>"><img src="/elementos/botaoTransferirSingular.png"></a>
              <img src="/elementos/filetypes/<?= the_filetype_image($arquivo, '/static/projetos/' . $projeto->id) ?>.png" style="float:left; margin-right: 8px;">
              <h2><?= $arquivos_de_vdd[$i] ?></h2>
              <p><?= human_filesize($arquivo, '/static/projetos/' . $projeto->id) ?></p>
            </div>
          <?php endforeach ?>
        </div>
      <?php endif ?>

      <?php if ($projeto->tipo == 'md') : ?>
        <!-- midia -->
        <object width="600" height="360" data="/elementos/vedorDImagem.swf" allowfullscreen="true">
          <param name="flashvars" value="server=<?= $config['URL'] ?>/&projectid=<?= $projeto->id ?>" />
        </object>
      <?php endif ?>

      <?php if (str_ends_with($arquivos[0], '.sb2')) : ?>
        <iframe src="https://turbowarp.org/embed?project_url=<?= $config['URL'] ?>/static/projetos/<?= $projeto->id ?>/<?= $arquivos[0] ?>" width="482" height="412" allowtransparency="true" frameborder="0" scrolling="no" allowfullscreen></iframe>
      <?php endif ?>
    </div>

    <div class="inside_page_content" style="margin-top: 8px; margin-bottom: 8px;">
      <div class="descricao" style="white-space:pre-line"><?= $projeto->descricao ?></div>
      <?php reajor_d_reagida('projeto', $projeto, $usuario) ?>
    </div>

  </div>

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

  <div class="page_content" style="min-height: 486px;">
    <div class="inside_page_content">
      <?php vedor_d_comentario('projeto', $projeto->id, true, $usuario); ?>
    </div>
  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>