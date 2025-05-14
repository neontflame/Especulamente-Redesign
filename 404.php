<?php http_response_code(404); ?>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<div class="container">
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

	<div class="page_content" style="height: 556px">
		<div class="inside_page_content">
			<div class="erro">
				<h1>404</h1>
				<img src="/elementos/erros/espe404.png">
				<p>Se você estava tentando achar algo,</p>
				<p>não está mais aqui, foi deletado,</p>
				<p>ou nunca existiu pra começo de conversa.</p>


				<a href="/projetos">Ver projetos?</a>
			</div>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>