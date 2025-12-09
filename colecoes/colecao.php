<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';

if (!isset($_GET['id'])) {
	erro_404();
}
$id = $_GET['id'];

$colecao = colecao_requestIDator($id);

if ($colecao == null) {
	erro_404();
}
$projos = simples_where_tudo('colecoes_projetos', 'id_colecao', $id);

function colecao_curacios($id)
{
	$autores = [];
	
	global $db;
	// pega do autor da coleçao
	array_push($autores, colecao_requestIDator($id)->criador);
	
	// pega dos curadores
	$rows = $db->prepare("SELECT * FROM colecoes_curadores WHERE id_colecao = ?");
	$rows->bindParam(1, $id);
	$rows->execute();
	$colecaoaut = $rows->fetch(PDO::FETCH_OBJ);
	
	if ($colecaoaut != false) {	
		foreach ($colecaoaut as $cara) {
			array_push($autores, $cara->id_curador);
		}
	}

	return $autores;
}

?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<div class="container">
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>
	
	<style>
		.projTitulo {
			width: 442px;
		}
		
		.aba {
			display:none;
			width: 100%;
		}

		#abaBotoes {
			display:table;
			width: 100%;
			margin-top: 6px;
		}
		
		#abaBotoes .coiso {
			border-bottom: 1px solid #9EBBFF;
			display: table;
			padding-left: 6px;
			margin-left: -5px;
			margin-bottom: 6px;
			height: 20px;
		}

		.abaButt {
			border: 1px solid #9EBBFF;
			background-color: #CCDBFF;
			width: 33%;
			font-size: 14px;
			margin-right:-5px;
		}

		.abaAtiva {
			border-bottom: 1px solid #FFFFFF !important;
			background-color: white;
		}
		
		.inside_page_content form {
			text-align: center;  
		}
	</style>
	
	<script>
			function inativarAsAbas() {
				var abaBotoes = document.getElementById('abaBotoes');
				for (var i = 0; i < abaBotoes.children.length; i++) {
					if (abaBotoes.children[i].className != 'coiso') {
						abaBotoes.children[i].className = 'abaButt';
					}
				}
				
				var abasReais = document.getElementsByClassName('aba');
				for (var i = 0; i < abasReais.length; i++) {
					abasReais[i].style.display = 'none';
				}
			}
	</script>
			
	<div class="page_content">
		<div class="projTitulo">
			<p style="display: inline-block;"><a href="/colecoes">Coleções</a> >> <i style="color: #4f6bad"><?= $colecao->nome ?></i></p>
		</div>
			
		<div class="projTitulo">
			<h1 style="line-height: 6px; margin-top: 14px; font-weight: normal;"><i><?= $colecao->nome ?></i></h1>
			<p style="margin-top: 12px;">por <a href="/usuarios/<?= usuario_requestIDator($colecao->criador)->username ?>"><?= usuario_requestIDator($colecao->criador)->username ?></a></p>
		</div>

		<div class="inside_page_content" style="padding-right: 0px;">
			<div id="abaBotoes">
				<div style="float: left;" class="coiso"></div>
				<button type="button" class="abaButt abaAtiva" onclick="inativarAsAbas(); this.className = 'abaButt abaAtiva'; abaProjs.style.display = 'table';">Projetos</button>
				<button type="button" class="abaButt" onclick="inativarAsAbas(); this.className = 'abaButt abaAtiva'; abaCurs.style.display = 'table';">Curadores</button>
				<button type="button" class="abaButt" onclick="inativarAsAbas(); this.className = 'abaButt abaAtiva'; abaDesc.style.display = 'table';">Descrição</button>
				<div style="float: right;" class="coiso"></div>
			</div>
					
			<div class="aba" id="abaProjs" style="display:table">
				<?php
					if (count($projos) > 0) : ?>
					<div class="projetos">
						<?php
						foreach ($projos as $projejo) {
							$projeto = projeto_requestIDator($projejo->id_projeto);
							renderarProjeto($projeto);
						}
						?>
					</div>
				<?php
					endif;
				?>
			</div>
			<div class="aba" id="abaCurs">
				<form action="" method="post">
					<label for="curadoradd">adicionar curador</label>
					<input type="text" name="curadoradd" id="curadoradd">
					<button class="coolButt verde">Adicionar</button>
				</form>
				<div class="separador" style="margin-bottom:4px;"></div>
			
				<?php foreach (colecao_curacios($id) as $curador) { 
					$uusuuaario = usuario_requestIDator($curador) ?>
					<div class="rankeado">
						<a href="/usuarios/<?= $uusuuaario->username ?>"><img src="<?= pfp($uusuuaario) ?>"></a>
						<a class="username" href="/usuarios/<?= $uusuuaario->username ?>"><?= $uusuuaario->username ?></a>
					</div>
				<?php } ?>
			</div>
			<div class="aba" id="abaDesc">
			</div>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>