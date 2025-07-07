<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
login_obrigatorio($usuario);

if (!isset($_GET['id'])) {
  erro_404();
}
$id = $_GET['id'];
$item = daveitem_requestIDator($id);


?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<link href="/cssDoDave.css" rel="stylesheet" type="text/css" />
<div class="container">
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

  <div class="page_content" style="height: 556px">
    <div class="inside_page_content">
      <img src="/daveloja/itens/<?= $item->id ?>.png" style="margin:auto; display: block;">
      <h2 style="text-align: center;">Você quer mesmo comprar <b style="color: black;"><?= $item->nome ?></b>?</h2>
      <p style="text-align: center; color: #555555;">Esse item custa <?= $item->daveprice ?> davecoins.</p>

      <button class="coolButt verde grandissimo" onclick="window.location.replace('<?= $config['URL'] ?>/daveloja/comprator.php?id=<?= $item->id ?>')">SIM!!! EU QUERO!!! PEGUE MEU DINHEIRO!!!</button>
      <button class="coolButt vermelho grandissimo">Não.</button>
    </div>
  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>