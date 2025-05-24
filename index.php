<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<div class="container">
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

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
			</style>
			<p class="labelManeira">>> DESTAQUE</p>
			<div class="separador" style="margin-bottom: 8px;"></div>
			<a href="/foruns/3/17"><img src="/elementos/destaques/collabMural1.png"></a>
			<div class="separador"></div>
			<p class="labelManeira">>> PROJETOS RECENTES</p>
			<div class="separador" style="margin-bottom: 8px;"></div>
			<?php
			$projetos = [];

			$pages = coisos_tudo($projetos, 'projetos', 1, '', '', 3);
			?>
			<div class="projetos">
				<?php foreach ($projetos as $projeto) {
					renderarProjeto($projeto);
				}
				?>
			</div>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>