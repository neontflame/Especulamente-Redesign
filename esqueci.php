<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php'; ?>
<?php
if (isset($usuario)) {
	redirect('/');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	function checagens($username)
	{
		if (!isset($username)) {
			erro("Preencha todos os campos!");
			return null;
		}

		// Verifica se o usuário existe
		$usuario_obtido = usuario_requestinator($username);
		if ($usuario_obtido == null) {
			erro("Usuário não existe!");
			return null;
		}

		return [
			'usuario' => $usuario_obtido,
		];
	}

	$entradas = checagens($_POST['username'] ?? null);
	if ($entradas) {
		$codigo = enviar_recuperacao($entradas['usuario']);
		sucesso("Email enviado!");
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
			text-align: center;
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
			margin-right: 108px;
			text-align-last: left;
		}

		.inside_page_content a {
			text-decoration: none;
		}
	</style>

	<div class="page_content">
		<div class="inside_page_content">
			<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/statusbar.php'; ?>
			<img src="elementos/esqueci.png" style="margin-top: -5px; margin-left: -5px; margin-right: -5px;">
			<p>Tudo bem!!! Acontece com as melhores famílias. Você não está sozinho. Entre abaixo o seu nome de usuário e nossa equipe de gorilas adestrados irá entrar em contato para você recuperar sua senha.</p>
			<form action="" method="post">
				<label for="username">nome de usuário</label>
				<input name="username" id="username" type="text" required>
				<br>
				<button class="coolButt">Enviar</button>
			</form>
			<p><a href="/entrar.php">ah nvm eu lembrei</a></p>
			<p>não tem uma conta ainda? <a href="/registro.php" title="ou morra tentando">crie uma aqui</a></p>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>