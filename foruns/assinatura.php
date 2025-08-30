<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
$forum = true;
login_obrigatorio($usuario);

$erro;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $sig = $_POST['siggy'];

  if (isset($sig)) {
	mudar_usuario($usuario->id, ['assinatura' => $sig]);
	$sucesso = "Assinatura trocada!";
  }
}

if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
    $johnTravolta = $_SERVER['HTTP_REFERER'];
} else {
    $johnTravolta = '/foruns/';
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

  <div class="page_content">
    <div class="inside_page_content">
		<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/statusbar.php'; ?>
	  <a href="<?= $johnTravolta ?>"><img style="margin-left: -5px; margin-top: -5px;" src="/elementos/voltar.png"></a>
      <h2 style="text-align:center;">Mudar assinatura</h2>
	  Isso vai fazer uma mensagem de sua escolha aparecer em baixo de seus posts nos Fóruns.
	  <br>
	  Você pode usar Markdown! 
	  <br>
	  <br>
      <form action="" method="post">
		<textarea name="siggy" id="siggy" style="width: 427px; max-width: 427px;"><?= $usuario->assinatura ?></textarea>
        <button class="coolButt grandissimo">Mudar minha assinatura</button>
      </form>
    </div>
  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>