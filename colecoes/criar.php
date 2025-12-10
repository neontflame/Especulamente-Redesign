<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// mudar nome
	if (!isset($_POST['nome'])) {
		erro('Cadê o nome?');
	}
	
	$thumb = null;
	
	if (isset($_FILES['thumb']) && $_FILES['thumb']['size'] > 0) {
		$thumb = $_FILES['thumb'];
	}
	
	$criacoiso = criar_colecao($usuario->id, $_POST['nome'], $thumb);
	
	if (is_string($criacoiso) && str_starts_with($criacoiso, "§")) {
		erro(substr($criacoiso, 1));
	} else {
		header('Location: /colecoes/' . $criacoiso->id);
	}
}

function criar_colecao($id_criador, $nome, $thumb)
{
	global $db;
	
	$rows = $db->prepare("INSERT INTO colecoes (criador, nome, descricao) VALUES (?, ?, '')");
	$rows->bindParam(1, $id_criador);
	$rows->bindParam(2, $nome);
	$rows->execute();

	$id = $db->lastInsertId();
	
	if ($thumb != null && $thumb['size'] > 0) {
		$rtn = subir_arquivo($thumb, '/static/colecoes/', "colecoes", $id, "thumbnail", ["png", "gif", "jpg", "jpeg", "bmp"], 1024 * 1024 * 10); //nao tem como uma imagem ser maior que 10 mb
		if (is_string($rtn) && str_starts_with($rtn, "§")) {
			return $rtn;
		}
	}

	return colecao_requestIDator($id);
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
				
				<h1 style="text-align: center; font-style: italic;">Coleção!</h1>
				<p><i>Agrupe seus projetos e coloque-os à mostra para todos, tal como um álbum!</i></p>
				
				<form action="" method="post" enctype="multipart/form-data">
					<input type="hidden" name="tipo" value="bg">

					<label for="nome" class="labelManeira">>> NOME</label>
					<input type="text" style="width: 97%" id="nome" name="nome" required value="">
					<br>
					
					<div class="separador" style="margin-bottom:4px"></div>
					<label class="labelManeira">>> BANNER</label>
					<p>A resolução dessa imagem pode ser qualquer uma, mas preferencialmente 442x47!</p>
					<input type="file" name="thumb" id="thumb" accept=".png,.jpg,.jpeg,.gif,.bmp">
						
					<button type="submit" class="coolButt verde grandissimo">Criar coleção</button>
				</form>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>