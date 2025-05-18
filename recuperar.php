<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php'; ?>
<?php
if (isset($usuario)) {
	redirect('/');
}

$erro;
$codigo = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
	if (isset($_GET["codigo"])) {
		$codigo = $_GET["codigo"];
		$codigo = obter_recuperacao($codigo);

		if ($codigo == null) {
			$erro = "Seu código é: null CÓDIGO";
		} else {
			$tempo = time() - strtotime($codigo->data);
			if ($tempo > 86400) { // 24 horas
				$erro = "Seu código é: null CÓDIGO";
				deletar_recuperacao($codigo->codigo);
				$codigo = null;
			}
		}
	} else {
		$erro = "Whoops! Você precisa de um código para recuperar sua senha!";
	}
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$codigo = $_POST["codigo"];
	$senha = $_POST["senha"];

	if (isset($codigo) && isset($senha)) {
		$codigo = obter_recuperacao($codigo);

		if (strlen($senha) < 6) {
			$erro = "Sua senha é: muito curta. senha.";
		} else {
			$hashword = password_hash($senha, PASSWORD_DEFAULT);

			if ($codigo != null) {
				mudar_usuario($codigo->criado_por, ['password_hash' => $hashword]);
				deletar_recuperacao($codigo->codigo);
				redirect('/entrar.php?recuperado=true');
			} else {
				$erro = "Seu código é: null CÓDIGO";
			}
		}
	} else {
		$erro = "Whoops! Você precisa de um código para recuperar sua senha!";
	}
}
?>

<?php
$meta["titulo"] = "[Recuperar senha <> PORTAL ESPECULAMENTE]";
include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php';
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

	.inside_page_content h1 {
		color: #0055DA;
	}

	.inside_page_content a {
		text-decoration: none;
	}
</style>
<div class="container">
	<?php
	$esconder_ad = true;
	include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php';
	?>

	<div class="page_content" style="min-height: 324px">
		<div class="inside_page_content">
			<?php if (isset($erro)) : ?>
				<p><?= $erro ?></p>
			<?php endif ?>
			<?php if ($codigo) : ?>
				<p>Mude sua senha abaixo:</p>
				<form action="" method="post">
					<input type="hidden" name="codigo" value="<?= $codigo->codigo ?>">
					<label for="senha">nova senha</label>
					<input name="senha" id="senha" type="password" required>
					<br>
					<button class="coolButt">Mudar senha</button>
				</form>
				<p>já tem uma conta? então <a href="/entrar.php">entre</a></p>
			<?php endif ?>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>