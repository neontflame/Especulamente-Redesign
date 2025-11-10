<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<div class="container">
	<?php 
	$forum_no_lado = true;
	$esconder_ad = true;
	$quantiaDeProjsPorCategoria = 6;
	include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

	<div class="page_content" style="min-height: 556px">
		<div class="inside_page_content">
			<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/statusbar.php'; ?>
			<style>
				.labelManeira {
					font-size: 15px;
					font-weight: bold;
					margin-top: 4px;
					margin-bottom: 4px;
				}
				.aDoLado {
				  color: #3F88F4;
				  display: block;
				  text-align: right;
				  float:right;
				  margin-top: -19px;
				  margin-right: 4px;
				  text-decoration: none;
				  font-weight: bold;
				}
				.aDoLado:hover {
				  text-decoration: underline;
				}

				.projetosTreco {
				  width:210px !important;
				  margin-top: 5px;
				}

				.miniProjetoTreco {
				  width: 214px;
				  min-height: 36px;
				  display:table;
				  word-wrap: break-word;
				}
			
				.miniProjetoTreco img {
					float:left;
				}
				.miniProjetoTreco .tit {
				  font-weight: bold;
				  color: #395FCE;
				  margin: 0px;
				  text-decoration: none;
				  font-size: 11px;
				}
				
				.miniProjetoTreco .tit:hover {
				  text-decoration: underline;
				}
				
				.miniProjetoTreco .desc {
				  margin-top: 0px;
					color: #BCBCBC;
					font-size: 10px;
				}
			</style>
			<img src="/elementos/principaltitles/destaque.png" style="margin-left: -5px; margin-bottom: 5px;">
			<a href="/projetos/117"><img src="/elementos/destaques/jornal.png"></a>
			<img src="/elementos/principaltitles/projsRecentes.png" style="margin-left: -5px; margin-top: 5px;">
			<img src="/elementos/principaltitles/projetosRecentes1.png" style="margin-left: -5px; margin-top: 5px;">
			<!-- TODO PODEROSO display: table; -->
			<div style="display:table;">
			<!-- midia -->
				<?php
				$projetos = [];

				$pages = coisos_tudo($projetos, 'projetos', 1, '', ' WHERE naolist = 0 AND tipo = "md"', $quantiaDeProjsPorCategoria, 'GREATEST(COALESCE(dataBump, 0), data) DESC, id DESC');
				?>
				<div class="projetosTreco" style="float:left">
					<?php foreach ($projetos as $projeto) {
						renderar_bosta($projeto);
					}
					?>
				</div>
				<!-- jogos -->
				<?php
				$projetos = [];

				$pages = coisos_tudo($projetos, 'projetos', 1, '', ' WHERE naolist = 0 AND tipo = "jg"', $quantiaDeProjsPorCategoria, 'GREATEST(COALESCE(dataBump, 0), data) DESC, id DESC');
				?>
				<div class="projetosTreco" style="float:right; margin-left: 10px;">
					<?php foreach ($projetos as $projeto) {
						renderar_bosta($projeto);
					}
					?>
				</div>
			</div>
			<img src="/elementos/principaltitles/projetosRecentes2.png" style="margin-left: -5px; margin-top: 5px;">
			<!-- OUTRO PODEROSO display: table; -->
			<div style="display:table;">
				<!-- blogs -->
				<?php
				$projetos = [];

				$pages = coisos_tudo($projetos, 'projetos', 1, '', ' WHERE naolist = 0 AND tipo = "bg"', $quantiaDeProjsPorCategoria, 'GREATEST(COALESCE(dataBump, 0), data) DESC, id DESC');
				?>
				<div class="projetosTreco" style="float:left">
					<?php foreach ($projetos as $projeto) {
						renderar_bosta($projeto, false);
					}
					?>
				</div>
				<!-- downloadaveis -->
				<?php
				$projetos = [];

				$pages = coisos_tudo($projetos, 'projetos', 1, '', ' WHERE naolist = 0 AND tipo = "dl"', $quantiaDeProjsPorCategoria, 'GREATEST(COALESCE(dataBump, 0), data) DESC, id DESC');
				?>
				<div class="projetosTreco" style="float:right; margin-left: 10px;">
					<?php foreach ($projetos as $projeto) {
						renderar_bosta($projeto, false);
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; 

function renderar_bosta($projeto, $thumbObrigatoria = true) { ?>
	<div class="miniProjetoTreco">
		<?php if ($projeto->thumbnail != null) { ?>
			<a href="/projetos/<?= $projeto->id ?>" style="float:left; margin-right: 8px"><img style="max-width:32px; height:32px" src="/static/projetos/<?= ($projeto->id) ?>/thumb/<?= ($projeto->thumbnail) ?>"></a>
		<?php } else if ($thumbObrigatoria) { ?>
			<a href="/projetos/<?= $projeto->id ?>" style="float:left; margin-right: 8px"><img style="max-width:32px; height:32px" src="/static/thumb_padrao.png"></a>
		<?php } ?>
		
		<a class="tit" href="/projetos/<?= $projeto->id ?>"><?= $projeto->nome ?></a>
		<p class="desc"><?= markdown_apenas_texto(explode("\n", $projeto->descricao)[0]) ?></p>
	</div>
<?php
}
?>