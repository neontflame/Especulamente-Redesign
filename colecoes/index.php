<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';

$query = $_GET['q'] ?? '';
$page = $_GET['page'] ?? 1;
$tipo = 'col'; // nao e um projeto mas conta!

$colecoes = [];


if ($query != '') {
	$coisodepagina = '?q=' . urlencode($query);
} else {
	$coisodepagina = '?';
}

$pages = coisos_tudo($colecoes, 'colecoes', $page, $query);

?>
<?php 
$meta["titulo"] = "[Coleções <> PORTAL ESPECULAMENTE]";
$meta["descricao"] = 'Organizações e listas de toda sorte! É como se fossem vários museus em um...';
include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<div class="container">
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

	<style>
	.projetoInfo {
		position:absolute;
		margin-left: -5px;
		padding-left: 5px;
		padding-top: 5px;
		width: 437px;
		height: 41px;
		background-color: #000000AA;
		opacity: 0;
	}

	.projetoInfo:hover {
		opacity: 100;
	}
	.projetoInfo h2 a {
		color: white; !important
	}
	.projetoInfo h2 a:hover {
		color: white; !important
	}
	.projetoInfo .autorDeProjeto {
		color: gray;
	}
	
	.semSal {
		min-height: 26px;
		border-bottom: 0px;
		padding-top: 10px;
		padding-bottom: 4px;
		padding-left: 5px;
		margin-left: -5px;
		margin-top: 0px;
		margin-right: -5px;
	}
	.semSal:hover {
		background-color: aliceblue;
	}
	</style>
	<div class="page_content">
		<img src="/elementos/pagetitles/colecoes.png" class="inside_page_content" style="padding: 0px; margin-bottom: 7px;">

		<div class="inside_page_content" style="padding-top: 0px;">
			<?php foreach ($colecoes as $colecao) : ?>
				<?php if ($colecao->thumbnail != null) : ?>
				<div class="projeto">
					<div class="projetoInfo">
						<div class="projetoSide">
							<a class="autorDeProjeto" href="/usuarios/<?= usuario_requestIDator($colecao->criador)->username ?>">
								por <?= usuario_requestIDator($colecao->criador)->username ?>
							</a>
							<br>
							<p class="autorDeProjeto">
								<?php
								if (isset($colecao->data)) {
									echo velhificar_data($colecao->data);
								} else {
									echo 'data nula WTF???';
								}
								?>
							</p>
						</div>
						
						<h2><a href="/colecoes/<?= $colecao->id ?>"><?= $colecao->nome ?></a></h2>
					</div>
					<img src="/static/colecoes/<?= $colecao->thumbnail ?>" width="442" height="46" style="padding: 0px; margin: -5px; margin-top: -4px;">
				</div>
				<?php else : ?>
				<div class="projeto semSal">
					<div class="projetoSide">
						<a class="autorDeProjeto" href="/usuarios/<?= usuario_requestIDator($colecao->criador)->username ?>">
							por <?= usuario_requestIDator($colecao->criador)->username ?>
						</a>
						<br>
						<p class="autorDeProjeto">
							<?php
							if (isset($colecao->data)) {
								echo velhificar_data($colecao->data);
							} else {
								echo 'data nula WTF???';
							}
							?>
						</p>
					</div>

					<h2><a href="/colecoes/<?= $colecao->id ?>"><?= $colecao->nome ?></a></h2>
					<p><?= markdown_apenas_texto(explode("\n", $colecao->descricao)[0]) ?></p>
				</div>
				<?php endif; ?>
			<?php endforeach; ?>

				
							<!-- here be pagination -->
				<div class="separador"></div>
							<div class="pagination">
								<?php if ($page > 1) : ?>
									<a href="/<?= pagetitlePorTipo($tipo) ?>/<?= $coisodepagina ?>page=1">Início</a>
									<p class="textinhoClaro">~</p>
									<a href="/<?= pagetitlePorTipo($tipo) ?>/<?= $coisodepagina ?>page=<?= $page - 1 ?>">« Anterior</a>
									<p class="textinhoClaro">~</p>
								<?php endif ?>
								<?php if ($page == 1) : ?>
									<p class="textinhoClaro" style="margin-right: 4px;">Início ~ « Anterior ~ </a>
									<?php endif ?>

									<p>Página <?= $page ?> de <?= $pages ?></p>

									<?php if ($page < $pages) : ?>
										<p class="textinhoClaro">~</p>
										<a href="/<?= pagetitlePorTipo($tipo) ?>/<?= $coisodepagina ?>page=<?= $page + 1 ?>">Próximo »</a>
										<p class="textinhoClaro">~</p>
										<a href="/<?= pagetitlePorTipo($tipo) ?>/<?= $coisodepagina ?>page=<?= $pages ?>">Fim</a>
									<?php endif ?>
									<?php if ($page == $pages) : ?>
										<p class="textinhoClaro"> ~ Próximo » ~ Fim</a>
										<?php endif ?>
							</div>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>