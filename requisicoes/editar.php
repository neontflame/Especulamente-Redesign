<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';

$id = $_GET['id'];

$requisicao = requisicao_requestIDator($id);

$request_e_minha = (isset($usuario) && $requisicao->id_requisitor == $usuario->id);

if ($requisicao == null) {	erro_404();	}
if (!$request_e_minha) {	erro_403();	}

$voltarPara = $_GET['volta'] ?? 'requisicio';

if ($voltarPara == 'criar') {
		$johnTravolta = '/criar/';
} else {
		$johnTravolta = '/requisicoes/' . $id;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// mudar nome
	if (isset($_POST['nome']) && $_POST['nome'] != $requisicao->nome) {
		$nome = $_POST['nome'];

		mudar_requisicao($requisicao->id, ['nome' => $nome]);
		info('Nome da requisição atualizada!');
	}
	
	if (isset($_POST['descricao']) && $_POST['descricao'] != $requisicao->descricao) {
		$descricao = $_POST['descricao'];

		mudar_requisicao($requisicao->id, ['descricao' => $descricao]);
		info('Descrição da requisição atualizada!');
	}
	
	$requisicao = requisicao_requestIDator($id);
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
			<p style="display: inline-block;"><a href="/requisicoes">Requisições</a> >> <a href="/requisicoes/<?= $requisicao->id ?>"><?= $requisicao->nome ?></a> >> <i style="color: #4f6bad">Editar</i></p>
		</div>
		
		<div class="inside_page_content">
				<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/statusbar.php'; ?>
				<a href="<?= $johnTravolta ?>"><img style="margin-left: -5px; margin-top: -5px;" src="/elementos/voltar.png"></a>
				
				<h1 style="text-align: center; font-style: italic;">Editando requisição</h1>

				<form action="" method="post" enctype="multipart/form-data">
					<input type="hidden" name="tipo" value="bg">

					<label for="nome" class="labelManeira">>> NOME</label>
					<input type="text" style="width: 97%" id="nome" name="nome" required value="<?= $requisicao->nome ?>">
					<br>
					
					<label for="descricao" class="labelManeira">>> DESCRIÇÃO</label>
					<textarea style="width: 97%" name="descricao" id="descricao"><?= $requisicao->descricao ?></textarea>
					
					<button type="submit" class="coolButt verde grandissimo">Salvar mudanças</button>
					<a href="/requisicoes/<?= $requisicao->id ?>/deletar" id="botaoDoMal" class="coolButt vermelho grandissimo">DELETAR REQUISIÇÃO ???!?</a>
				</form>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>