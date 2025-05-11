<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php'; ?>
<?php
login_obrigatorio($usuario); 

$mensagens = [];

// grandes numeros para grandes coisas 2
$msgs = coisos_tudo($mensagens, 'mensagens', 1, null, ' WHERE receptor = ' . $usuario->id . ' OR receptor = -1', 10000000);
		
ler_mensagens($usuario->id); 

include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<div class="container">
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

	<div class="page_content" style="min-height: 486px">
		<div class="inside_page_content">
			<img src="/elementos/pagetitles/mensagens.png" style="margin-top: -5px; margin-left: -5px; border-bottom: 1px solid #BAE2FF;">
			
			<?php foreach ($mensagens as $mensagem) { 
				if ($mensagem->lido == 0) { ?>
					<div class="mensagem msgNova">
						<img src="/elementos/mensagens/<?= $mensagem->icone ?>.png">
						<?= $mensagem->html ?>
						<p class="autorDeProjeto">dia <?= velhificar_data($mensagem->data); ?></p>
					</div>
			<?php }	
			} ?>
			<!-- separador de mensagem nova e velha -->
			<div class="separadorChique">
				<p class="autorDeProjeto" style="text-align: center; font-weight: bold; margin-bottom: 4px;">>> Mensagens antigas <<</p>
			</div>
			<?php foreach ($mensagens as $mensagem) { 
				if ($mensagem->lido == 1) { ?>
			<div class="mensagem">
				<img src="/elementos/mensagens/<?= $mensagem->icone ?>.png">
				<?= $mensagem->html ?>
				<p class="autorDeProjeto">dia <?= velhificar_data($mensagem->data); ?></p>
			</div>
			<?php }	
			} ?>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>