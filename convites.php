<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
login_obrigatorio($usuario);

$erro;

if ($_SERVER["REQUEST_METHOD"] == "GET") {
	if (isset($_GET['deletar'])) {
		$convite = obter_convite($_GET['deletar']);

		if (!$convite || $convite->criado_por != $usuario->id) {
			$erro = "Whoops! Esse convite não existe, ou não é seu.";
		} else {
			deletar_convite($convite->codigo);
		$info = "Convite deletado.";
		}
	}
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$codigo = $_POST['codigo'];

	if (isset($codigo)) {
		$convite = obter_convite($codigo);

		if ($convite) {
			$erro = "Whoops! Um convite com esse nome já existe.";
		} else {
			criar_convite($codigo, $usuario->id);
		$sucesso = 'Convite criado!';
		}
	}
}
?>

<?php
$meta["titulo"] = "[Seus convites <> PORTAL ESPECULAMENTE]";
include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php';
?>
<style>
.convites {
  margin-top: 8px;
}

.convites .conviteSingular {
  display: table;
  width: 98%;
  min-height: 32px;
  padding: 4px;
  border-top: 1px solid #9ebbff;
}

.convites .conviteSingular .nomeDoConvite {
  font-size: 16px;
  color: black;
}

.convites .conviteSingular .conviteDireitos {
  float:right;
  text-align:right;
}

.convites .conviteSingular .conviteDireitos .usadorDConvite {
  font-size: 11px;
  color: #888888;
  font-style: italic;
  vertical-align: top;
}

.convites .conviteSingular .conviteDireitos .usadorDConvite a {
  color: #888888;
  font-weight: bold;
  text-decoration: none;
}

.convites .conviteSingular .conviteDireitos .usadorDConvite a:hover {
  text-decoration: underline;
}

.inside_page_content form {
  text-align: center;  
}
</style>
<div class="container">
	<?php
	$esconder_ad = true;
	include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php';
	?>

	<div class="page_content">
		<div class="inside_page_content">
		<img src="/elementos/pagetitles/convites.png" style="margin-top: -5px; margin-left: -5px; margin-right: -5px;">
		<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/statusbar.php'; ?>
			<form action="" method="post">
				<label for="codigo">código</label>
				<input type="text" name="codigo" id="codigo">
				<button class="coolButt verde">Criar</button>
			</form>
			<div class="convites">
				<?php
				foreach (obter_convites_criados_por($usuario->id) as $convite) {
					$user = null;
					if ($convite->usado_por) {
						$user = usuario_requestIDator($convite->usado_por);
					}
				?>
					<div class="conviteSingular">
						<span class="nomeDoConvite"><?= $convite->codigo ?></span>
							<div class="conviteDireitos">
							<?php if ($user != null): ?>
								<span class="usadorDConvite">Usado por <a href="/usuarios/<?= $user->username ?>"><?= $user->username ?></a></span>
								<a href="/usuarios/<?= $user->username ?>"><img src="<?= pfp($user) ?>" width="32" height="32"></a>
							<?php else: ?>
								<button class="coolButt" onclick="navigator.clipboard.writeText('<?= $config['URL'] ?>/registro.php?convite=<?= $convite->codigo ?>')">Copiar link</button>
								<br>
								<a class="coolButt vermelho" href="/convites.php?deletar=<?= $convite->codigo ?>">Deletar</a>
							<?php endif ?>
							</div>
					</div>
				<?php
				}
				?>
			</div>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>