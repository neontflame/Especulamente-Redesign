<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php'; ?>
<?php
if (isset($usuario)) {
	redirect('/');
}

$erro;
$username = "";
$codigo = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
	if (isset($_GET["codigo"])) {
		$codigo = $_GET["codigo"];
		$rows = $db->prepare("SELECT * FROM reccodigo WHERE codigo = ? AND usado_por IS NULL");
		$rows->bindParam(1, $codigo);
		$rows->execute();

		if ($rows->rowCount() == 0) {
			$erro = "Seu código é: null CÓDIGO";
			$codigo = null;
		}
	} else {
		$erro = "Whoops! Você precisa de um código para recuperar sua senha!";
	}
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$username = $_POST['username'];
	$email = $_POST['email'];
	$senha = $_POST['senha'];
	$codigo = $_POST['codigo'];

	$permitido_criar = true;

	$rows = $db->prepare("SELECT * FROM reccodigo WHERE codigo = ? AND usado_por IS NULL");
	$rows->bindParam(1, $codigo);
	$rows->execute();

	if ($rows->rowCount() == 0) {
		$erro = "Seu código é: null CÓDIGO";
		$permitido_criar = false;
	} else {
		if (strlen($senha) < 6) {
			$erro = "Sua senha é: muito curta. senha.";
			$permitido_criar = false;
		}
	}

	if ($permitido_criar) {
		$rows = $db->prepare("UPDATE usuarios SET password_hash = ? WHERE username = ?");
		$hashword = password_hash($senha, PASSWORD_DEFAULT);
		$rows->bindParam(1, $hashword);
		$rows->bindParam(2, $username);
		$rows->execute();
	
		$row = $rows->fetch(PDO::FETCH_OBJ);
		$rows = $db->prepare("UPDATE reccodigo SET usado_por = ? WHERE codigo = ?");
		$rows->bindParam(1, $last_id);
		$rows->bindParam(2, $convite);
		$rows->execute();

		/*
		// The message
		$message = "Sua conta foi CRIADA com SUCESSO";

		// In case any of our lines are larger than 70 characters, we should use wordwrap()
		$message = wordwrap($message, 70, "\r\n");

		// Send
		mail($email, 'Atenção', $message);
		*/
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

	<div class="page_content" style="min-height: 254px">
		<div class="inside_page_content">
			<?php if (isset($erro)) : ?>
				<p><?= $erro ?></p>
			<?php endif ?>
			<?php if ($codigo) : ?>
				<h1>VOCÊ É DIGNO!!!</h1>
				<p>Seu código é: <?= $codigo ?></p>
				<p>isso e um placeholder meio brega por enquanto</p>
				<form action="" method="post">
					<input type="hidden" name="codigo" value="<?= $codigo ?>">
					<input type="hidden" name="username" value="<?= $username ?>">
					<label for="senha">senha</label>
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