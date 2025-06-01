<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php'; ?>
<?php
if (isset($usuario)) {
	redirect('/');
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
	function checagens($codigo)
	{
		if (!isset($codigo)) {
			erro_404();
			return null;
		}

		// Checa se o código existe
		$recuperacao = obter_recuperacao($codigo);
		if ($recuperacao == null) {
			erro_404();
			return null;
		}

		// Checa se o código expirou
		$tempo = time() - strtotime($recuperacao->data);
		if ($tempo > 86400) { // 24 horas
			erro("Seu código expirou! Peça outro.");
			deletar_recuperacao($codigo);
			redirect('/esqueci.php');
			return null;
		}

		return [
			'codigo' => $codigo,
		];
	}

	$entradas = checagens($_GET["codigo"] ?? null);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	function checagens($codigo, $senha, $senhaConfirm)
	{
		if (!isset($codigo) || !isset($senha) || !isset($senhaConfirm)) {
			erro("Preencha todos os campos!");
			return null;
		}

		// Checa se o código existe
		$recuperacao = obter_recuperacao($codigo);
		if ($recuperacao == null) {
			erro_404();
			return null;
		}

		// Checa se o código expirou
		$tempo = time() - strtotime($recuperacao->data);
		if ($tempo > 86400) { // 24 horas
			erro("Seu código expirou! Peça outro.");
			deletar_recuperacao($codigo);
			redirect('/esqueci.php');
			return null;
		}

		// Checa se a senha é válida
		if (strlen($senha) < 6) {
			erro("Sua senha é: muito curta. senha.");
			return null;
		}
		if ($senha != $senhaConfirm) {
			erro("As senhas não coincidem!");
			return null;
		}

		return [
			'codigo' => $codigo,
			'senha' => $senha,
			'usuario_id' => $recuperacao->criado_por,
		];
	}

	$entradas = checagens(
		$_POST['codigo'] ?? null,
		$_POST['senha'] ?? null,
		$_POST['senhaConfirm'] ?? null
	);

	if ($entradas) {
		$hashword = password_hash($entradas['senha'], PASSWORD_DEFAULT);
		mudar_usuario($entradas['usuario_id'], ['password_hash' => $hashword]);
		deletar_recuperacao($entradas['codigo']);
		sucesso("Aproveite sua senha nova :]");
		redirect('/entrar.php');
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
		margin-right: 86px;
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

	<div class="page_content">
		<div class="inside_page_content">
			<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/statusbar.php'; ?>
			<img src="elementos/recuperar.png" style="margin-top: -5px; margin-left: -5px; margin-right: -5px;">
			<p>Mude sua senha abaixo:</p>
			<form action="" method="post">
				<input type="hidden" name="codigo" value="<?= $_GET['codigo'] ?? $_POST['codigo'] ?? "" ?>">
				<label for="senha">nova senha</label>
				<input name="senha" id="senha" type="password" required>
				<br>
				<label for="senhaConfirm">confirme a senha</label>
				<input name="senhaConfirm" id="senhaConfirm" type="password" required>
				<br>
				<button class="coolButt">Mudar senha</button>
			</form>
			<p>já tem uma conta? então <a href="/entrar.php">entre</a></p>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>