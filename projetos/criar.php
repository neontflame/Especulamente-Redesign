<?php
include $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
login_obrigatorio($usuario);
?>
<?php
$erro = [];
$tipo = $_GET['tipo'] ?? null;

if (isset($_POST)) {
}
?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<div class="container">
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

  <div class="page_content" style="min-height: 486px">
    <?php if ($erro) : ?>
      <div class="erro" style="color: red; background: black; text-align: center;">
        <img src="/static/skull-and-cross.gif" width="24" height="24" />
        <?= $erro ?>
        <img src="/static/skull-and-cross.gif" width="24" height="24" />
      </div>
    <?php endif; ?>

    <div class="inside_page_content">
      <?php if ($tipo == null) : ?>
        <h1>O que você quer criar hoje?</h1>

        <ul>
          <li><a href="/projetos/criar.php?tipo=dl">Downloadável</a></li>
        </ul>
      <?php endif; ?>

      <?php if ($tipo == 'dl') : ?>
        <!-- Downloadável -->
        <a href="/projetos/criar.php">&lt; Voltar</a>
        <h1>Downloadável!</h1>
        <p><i>Esse tipo de projeto oferece arquivos para descarga. Os usuários podem transferir as suas coisas para seus discos rígidos.</i></p>

        <form action="" method="post" enctype="multipart/form-data">
          <label for="nome">Nome</label>
          <input type="text" id="nome" name="nome" required>
          <br>

          <label for="descricao">Descrição</label>
          <textarea name="descricao" id="descricao"></textarea>
          <br>

          <label for="arquivos">Arquivos</label>
          <ul>
            <li><input type="file" name="arquivos[]" id="arquivos"> - <button>Remover</button></li>
            <li><button>+ Adicionar mais um</button></li>
          </ul>
        </form>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>