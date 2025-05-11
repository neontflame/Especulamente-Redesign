<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php'; ?>
<?php
login_obrigatorio($usuario); 
include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<div class="container">
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

	<div class="page_content" style="min-height: 486px">
		<div class="inside_page_content">
			<img src="/elementos/pagetitles/mensagens.png" style="margin-top: -5px; margin-left: -5px; border-bottom: 1px solid #BAE2FF;">
			
			<h2 style="color: red;">ESSA PAGINA AINDA E UM WIP E NAO FUNCIONA!!!</h2>
			<p style="color: gray;">legit so tem o layout aqui (depois vai funcionar confia)</p>
			
			<!-- menção (comentario) -->
			<div class="mensagem">
				<img src="/elementos/mensagens/menciona.png">
				<a href="/usuarios/" class="usuario">usuario</a>
				mencionou você em um
				<a href="/projetos/">comentário</a>!
				
				<blockquote>
				"esse cara ai o @<?= $usuario->username ?>"
				</blockquote>
				<p class="autorDeProjeto">dia 01/01/2001 às 01:01</p>
			</div>
			<!-- menção (projeto) -->
			<div class="mensagem">
				<img src="/elementos/mensagens/menciona.png">
				<a href="/usuarios/" class="usuario">usuario</a>
				mencionou você em
				<a href="/projetos/">Projeto</a>!
				
				<p class="autorDeProjeto">dia 01/01/2001 às 01:01</p>
			</div>
			<!-- comentario (projeto) -->
			<div class="mensagem">
				<img src="/elementos/mensagens/comentario.png">
				<a href="/usuarios/" class="usuario">usuario</a>
				comentou em seu projeto
				<a href="/projetos/">Projeto</a>!
				
				<blockquote>
				"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam vitae pulvinar nibh. Sed vel libero interdum, vehicula nibh eget, luctus risus."
				</blockquote>
				<p class="autorDeProjeto">dia 01/01/2001 às 01:01</p>
			</div>
			<!-- comentario (perfil) -->
			<div class="mensagem">
				<img src="/elementos/mensagens/comentario.png">
				<a href="/usuarios/" class="usuario">usuario</a>
				comentou no
				<a href="/usuarios/">seu perfil</a>!
				
				<blockquote>
				"nao entendi mas achei legal"
				</blockquote>
				<p class="autorDeProjeto">dia 01/01/2001 às 01:01</p>
			</div>
			<!-- resposta (projeto) -->
			<div class="mensagem">
				<img src="/elementos/mensagens/resposta.png">
				<a href="/usuarios/" class="usuario">usuario</a>
				respondeu seu comentário em
				<a href="/projetos/">Projeto</a>!
				
				<blockquote>
				">>125 cara e que assim voce ta simplesmente errado"
				</blockquote>
				<p class="autorDeProjeto">dia 01/01/2001 às 01:01</p>
			</div>
			<!-- resposta (perfil) -->
			<div class="mensagem">
				<img src="/elementos/mensagens/resposta.png">
				<a href="/usuarios/" class="usuario">usuario</a>
				respondeu seu comentário em
				<a href="/projetos/">Projeto</a>!
				
				<blockquote>
				">>1 cara e que assim voce ta simplesmente certo"
				</blockquote>
				<p class="autorDeProjeto">dia 01/01/2001 às 01:01</p>
			</div>
			<!-- separador de mensagem nova e velha -->
			<div class="separadorChique">
				<p class="autorDeProjeto" style="text-align: center; font-weight: bold; margin-bottom: 4px;">>> Mensagens antigas <<</p>
			</div>
			<div class="mensagem">
				<img src="/elementos/mensagens/resposta.png">
				<a href="/usuarios/" class="usuario">usuario</a>
				respondeu seu comentário em
				<a href="/projetos/">Projeto</a>!
				
				<blockquote>
				">>1 cara e que assim voce ta simplesmente certo"
				</blockquote>
				<p class="autorDeProjeto">dia 01/01/2001 às 01:01</p>
			</div>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>