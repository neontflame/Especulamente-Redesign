<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php'; ?>
<?php
if (isset($usuario)) {
	redirect('/');
}

$erro;
$enviado = false;

$random = random_int(1, 1000);
if ($random == 1) {
	redirect('/vaisefoderem.php');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$username = $_POST['username'];

	if (isset($username)) {
		$usuario_obtido = usuario_requestinator($username);

		if ($usuario_obtido != null) {
			$codigo = enviar_recuperacao($usuario_obtido);
			if (str_starts_with($codigo, "§")) {
				$erro = $codigo;
			} else {
				$enviado = true;
			}
		} else {
			$erro = "Usuário não existe!";
		}
	}
}
?>

<?php
$meta["titulo"] = "[Esqueci a senha <> PORTAL ESPECULAMENTE]";
include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php';
?>

<div class="container">
	<?php
	$esconder_ad = true;
	include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php';
	?>

	<style>
		.inside_page_content {
			text-align-last: center;
			color: #898989;
		}

		.inside_page_content form {
			text-align-last: right;
			margin-right: 80px;
			color: #566C9E;
		}

		.inside_page_content form input {
			text-align-last: left;
			margin-top: 4px;
		}


		.inside_page_content form button {
			margin-top: 16px;
			margin-right: 118px;
			text-align-last: left;
		}

		.inside_page_content a {
			text-decoration: none;
		}
	</style>

	<div class="page_content" style="min-height: 324px">
		<div class="inside_page_content">
			<h1>Esqueci a senha :(</h1>
			<p>Tudo bem!!! Acontece com as melhores famílias. Você não está sozinho. Entre abaixo o seu nome de usuário e nossa equipe de gorilas adestrados irá entrar em contato para você recuperar sua senha.</p>
			<form action="" method="post">
				<label for="username">nome de usuário</label>
				<input name="username" id="username" type="text" required>
				<br>
				<button class="coolButt">Enviar</button>
			</form>
			<?php if ($enviado) : ?><p style="color: green;">Email enviado!!</p><?php endif ?>
			<?php if (isset($erro)) : ?><p style="color: #FF0000;"><?= $erro ?></p><?php endif ?>
			<p><a href="/entrar.php">ah nvm eu lembrei</a></p>
			<p>não tem uma conta ainda? <a href="/registro.php" title="ou morra tentando">crie uma aqui</a></p>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>