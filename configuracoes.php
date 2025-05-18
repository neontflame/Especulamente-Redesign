<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
login_obrigatorio($usuario);

$erro;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $sig = $_POST['siggy'];

  if (isset($sig)) {
	mudar_usuario($usuario->id, ['assinatura' => $sig]);
  }
}
?>

<?php
$meta["titulo"] = "[Seus convites <> PORTAL ESPECULAMENTE]";
include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php';
?>

<div class="container">
  <?php
  $esconder_ad = true;
  include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php';
  ?>

  <div class="page_content" style="min-height: 323px">
    <div class="inside_page_content">
      <h2>Configurações</h2>
      <h3>Mudar assinatura</h3>
	  Isso vai fazer uma mensagem de sua escolha aparecer em baixo de seus posts nos Fóruns.
	  <br>
	  Você pode usar Markdown! 
	  <br>
	  <br>
      <form action="" method="post">
		<textarea name="siggy" id="siggy" style="width: 427px; max-width: 427px;"><?= $usuario->assinatura ?></textarea>
        <button class="coolButt grandissimo">Mudar minha assinatura</button>
      </form>
      <?php if (isset($erro)) : ?>
        <p><?= $erro ?></p>
      <?php endif ?>
    </div>
  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>