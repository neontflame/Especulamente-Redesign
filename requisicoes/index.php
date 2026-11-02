<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';

$query = $_GET['q'] ?? '';
$page = $_GET['page'] ?? 1;
$tipo = 'req'; // nao e um projeto mas conta!

$requisicoes = [];


if ($query != '') {
	$coisodepagina = '?q=' . urlencode($query);
} else {
	$coisodepagina = '?';
}

$pages = coisos_tudo($requisicoes, 'requisicoes', $page, $query);

?>
<?php 
$meta["titulo"] = "[Requisições <> PORTAL ESPECULAMENTE]";
$meta["descricao"] = 'Aqui é como um mercado... um mercado de IDEIAS...';
include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<div class="container">
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>
	
	<style>
	.projeto {
		display:table;
		width: 437px;
		position: relative;
		min-height: 26px;
		border-bottom: 1px solid #beddff;
		padding-top: 4px;
		padding-left: 5px;
		margin-left: -5px;
		margin-top: 0px;
		margin-right: -5px;
	}
	
	.disponivel {
		background-color: #f9ffe6;
	}
	</style>
	<div class="page_content">
		<img src="/elementos/pagetitles/requisicoes.png" class="inside_page_content" style="padding: 0px; margin-bottom: 7px;">

		<div class="inside_page_content" style="padding-top: 0px;">
			<?php foreach ($requisicoes as $requisicao) : ?>
				<div class="projeto <?= ($requisicao->dataEntregue == null) ? 'disponivel' : '' ?>">
					<div class="projetoSide">
						<a class="autorDeProjeto" href="/usuarios/<?= usuario_requestIDator($requisicao->id_requisitor)->username ?>">
							por <?= usuario_requestIDator($requisicao->id_requisitor)->username ?>
						</a>
						<br>
						<p class="autorDeProjeto">
							<?php
							if (isset($requisicao->data)) {
								echo velhificar_data($requisicao->data);
							} else {
								echo 'data nula WTF???';
							}
							?>
						</p>
						
						<?php if ($requisicao->dataEntregue == null) : ?>
						<p class="autorDeProjeto" style="margin-top: -8px; color: #9008E0;">Aberto! <b><?= requisicao_preco($requisicao->id) ?> Dv¢</b></p>
						<?php else : ?>
						<p class="autorDeProjeto" style="margin-top: -8px; color: red;">Concluída por <b><?= requisicao_preco($requisicao->id) ?> Dv¢</b></p>
						<?php endif;?>
					</div>

					<h2><a href="/requisicoes/<?= $requisicao->id ?>"><?= $requisicao->nome ?></a></h2>
					<p><?= markdown_apenas_texto(explode("\n", $requisicao->descricao)[0]) ?></p>
				</div>
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