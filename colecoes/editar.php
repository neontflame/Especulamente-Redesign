<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';

$id = $_GET['id'];

$colecao = colecao_requestIDator($id);

$estudio_e_meu = (isset($usuario) && $colecao->criador == $usuario->id);

if ($colecao == null) {	erro_404();	}
if (!$estudio_e_meu) {	erro_403();	}

$voltarPara = $_GET['volta'] ?? 'colecio';

if ($voltarPara == 'criar') {
		$johnTravolta = '/criar/';
} else {
		$johnTravolta = '/colecoes/' . $id;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// mudar nome
	if (isset($_POST['nome']) && $_POST['nome'] != $colecao->nome) {
		$nome = $_POST['nome'];

		mudar_colecao($colecao->id, ['nome' => $nome]);
		info('Nome da coleção atualizado!');
	}
		
	if (isset($_FILES['thumb']) && $_FILES['thumb']['size'] > 0) {
		$arquivo = $_FILES['thumb'];

		$rtn = subir_arquivo($arquivo, '/static/colecoes/', 'colecoes', $colecao->id, 'thumbnail', ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'PNG', 'JPG', 'JPEG', 'GIF', 'BMP'], 1024 * 1024 * 5);

		if (!str_starts_with($rtn, '§')) {
			info('Banner da coleção atualizado!');
		} else {
			erro(substr($rtn, 1));
		}
	}
	
	if (isset($_POST['removerThumb']) && $colecao->thumbnail != null) {
		if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/static/colecoes/' . $colecao->thumbnail)) {
			unlink($_SERVER['DOCUMENT_ROOT'] . '/static/colecoes/' . $colecao->thumbnail);
		}
		$rows = $db->prepare("UPDATE colecoes SET thumbnail = NULL WHERE criador = ? AND id = ?");
		$rows->bindParam(1, $usuario->id);
		$rows->bindParam(2, $colecao->id);
		$rows->execute();
	}
	
	$colecao = colecao_requestIDator($id);
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
		<div class="projTitulo">
			<p style="display: inline-block;"><a href="/colecoes">Coleções</a> >> <a href="/colecoes/<?= $colecao->id ?>"><?= $colecao->nome ?></a> >> <i style="color: #4f6bad">Editar</i></p>
		</div>
		
		<div class="inside_page_content">
				<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/statusbar.php'; ?>
				<a href="<?= $johnTravolta ?>"><img style="margin-left: -5px; margin-top: -5px;" src="/elementos/voltar.png"></a>
				
				<form action="" method="post" enctype="multipart/form-data">
					<input type="hidden" name="tipo" value="bg">

					<label for="nome" class="labelManeira">>> NOME</label>
					<input type="text" style="width: 97%" id="nome" name="nome" required value="<?= $colecao->nome ?>">
					<br>
					
					<div class="separador" style="margin-bottom:4px"></div>
					<label class="labelManeira">>> BANNER</label>
					<p>A resolução dessa imagem pode ser qualquer uma, mas preferencialmente 442x47!</p>
					<input type="file" name="thumb" id="thumb" accept=".png,.jpg,.jpeg,.gif,.bmp">
						
					<p>Deixe em branco para deixar o mesmo banner que está agora.</p>

					<input type="checkbox" name="removerThumb" id="removerThumb" onchange="
						if (this.checked) {
							document.getElementById('thumb').setAttribute('disabled', 'disabled');
						} else {
							document.getElementById('thumb').removeAttribute('disabled');
						}">
					<label for="removerThumb" style="display: inline-block; font-size: 12px;">remover banner</label>
							
					<button type="submit" class="coolButt verde grandissimo">Salvar mudanças</button>
				</form>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>