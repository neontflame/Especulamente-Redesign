<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
login_obrigatorio($usuario);
?>
<?php
$erro = [];
$id = $_GET['id'] ?? null;
$requisicao = requisicao_requestIDator($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
  $id = $_POST['id'];

  $requisicao_rtn = deletar_requisicao($usuario->id, $id);
  if (is_string($requisicao)) {
    array_push($erro, $requisicao);
  }

  if (count($erro) == 0) {
    header('Location: /requisicoes');
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
        <a href="/requisicoes/<?= $id ?>"><img style="margin-left: -5px; margin-top: -5px;" src="/elementos/voltar.png"></a>
        <h1 style="text-align: center; font-style: italic;">Deletando requisição...???!</h1>

        <form action="/requisicoes/deletar.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="id" value="<?= $requisicao->id ?>">
          <p>Tem certeza que quer desistir de pedir <b><?= $requisicao->nome ?></b>??</p>
          <p>Isso é irreversível.</p>
          <p>Suas <b><?= requisicao_preco($requisicao->id) ?> davecoins</b> também terão sido gastas em vão.</p>
          <p>Pense bem.</p>
          <br><br><br><br>

          <img src="/requisicoes/ilusaodeoticaoquevoceve.png" style="width: 100%" alt="">

          <br><br><br><br>
          <button class="coolButt vermelho grandissimo" style="font-size: 80px;">SIM. DELETAR.</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</div>


<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>