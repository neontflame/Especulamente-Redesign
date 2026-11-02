<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// mudar nome
	if (!isset($_POST['nome'])) {
		erro('Cadê o nome?');
	}
	if (!isset($_POST['preco'])) {
		erro('Não vai cobrar nada??');
	}
	
	$nome = $_POST['nome'];
	$descricao = $_POST['descricao'];
	$preco = $_POST['preco'];
	
	$criacoiso = criar_requisicao($usuario->id, $nome, $descricao ?? '', $preco);
	
	if (is_string($criacoiso) && str_starts_with($criacoiso, "§")) {
		erro(substr($criacoiso, 1));
	} else {
		header('Location: /requisicoes/' . $criacoiso->id);
	}
}

function criar_requisicao($id_criador, $nome, $desc, $preco)
{
	global $db;
	$precoClamp = max(10, min(intval($preco), $usuario->davecoins));
	
	$rows = $db->prepare("INSERT INTO requisicoes (nome, id_requisitor, descricao, precoInicial) VALUES (?, ?, ?, ?)");
	$rows->bindParam(1, $nome);
	$rows->bindParam(2, $id_criador);
	$rows->bindParam(3, $desc);
	$rows->bindParam(4, $precoClamp);
	$rows->execute();

	$id = $db->lastInsertId();

	return requisicao_requestIDator($id);
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
	
	.projTitulo {
		width: 442px;
	}
	
</style>
<div class="container">
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

	<div class="page_content">
		
		<div class="inside_page_content">
				<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/statusbar.php'; ?>
				<a href="/criar"><img style="margin-left: -5px; margin-top: -5px;" src="/elementos/voltar.png"></a>
				
				<h1 style="text-align: center; font-style: italic;">Requisição!</h1>
				<p><i>Deixe sua ideia à mercê das habilidades de outras pessoas!</i></p>
				
				<form action="" method="post" enctype="multipart/form-data">
					<input type="hidden" name="tipo" value="bg">

					<label for="nome" class="labelManeira">>> NOME</label>
					<input type="text" style="width: 97%" id="nome" name="nome" required value="<?= $nome ?? "" ?>">
					<br>
					
					<label for="descricao" class="labelManeira">>> DESCRIÇÃO</label>
					<textarea style="width: 97%" name="descricao" id="descricao"><?= $descricao ?? "" ?></textarea>
					<br>
					
					<div class="separador" style="margin-bottom:4px"></div>
					<label class="labelManeira">>> INFORMAÇÃO ADICIONAL</label>
					<p>Valor em <img style="vertical-align: bottom;" alt="Davecoins" src="/elementos/davecoin/dvc.gif"> a ser pago: <b>Dv¢</b> <input type="number" id="preco" name="preco" min="10" max="<?= $usuario->davecoins > 10 ? $usuario->davecoins : 10 ?>" value="<?= $preco ?? 10 ?>"/></p>
					
					<button type="submit" class="coolButt verde grandissimo">Criar requisição</button>
				</form>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>