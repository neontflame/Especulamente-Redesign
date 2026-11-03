<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
login_obrigatorio($usuario);
?>
<?php
$erro = [];
$id = $_GET['id'] ?? null;
$colecao = colecao_requestIDator($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
  $id = $_POST['id'];

  $colecao_rtn = deletar_colecao($usuario->id, $id);
  if (is_string($colecao)) {
    array_push($erro, $colecao);
  }

  if (count($erro) == 0) {
    header('Location: /colecoes');
  }
}
?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<style>
  label {
    font-weight: bold;
    display: block;
    font-size: 15px;

    margin-top: 5px;
    margin-bottom: 5px;
  }
</style>

<div class="container">
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

  <div class="page_content">
    <?php if ($erro) : ?>
      <div class="erro" style="color: red; background: black; text-align: center;">
        <img src="/static/skull-and-cross.gif" width="24" height="24" />
        <?= $erro ?>
        <img src="/static/skull-and-cross.gif" width="24" height="24" />
      </div>
    <?php endif; ?>

    <div class="inside_page_content" style="padding-right: 0px;">
      <?php if ($id == null) : ?>
        <p>wtf</p>
      <?php else : ?>
        <a href="/colecoes/<?= $id ?>"><img style="margin-left: -5px; margin-top: -5px;" src="/elementos/voltar.png"></a>
        <h1 style="text-align: center; font-style: italic;">Deletando coleção...???!</h1>

        <form action="/colecoes/deletar.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="id" value="<?= $colecao->id ?>">
          <p>Tem certeza que quer deletar a coleção <b><?= $colecao->nome ?></b>??</p>
          <p>você NUNCA mais vai conseguir acessá-la.</p>
          <p>Isso é irreversível.</p>
          <p>Você não vai perder nenhuma <b>davecoin</b> com isso (por enquanto), mas todos os projetos que você já reuniu na coleção vão ficar todos espalhados pelo site.</p>
          <p>Pense bem.</p>
          <br><br><br><br>

          <img src="/colecoes/ilusaodeoticaoquevoceve.png" style="width: 100%" alt="">

          <br><br><br><br>
          <button class="coolButt vermelho grandissimo" style="font-size: 80px;">SIM. DELETAR.</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>