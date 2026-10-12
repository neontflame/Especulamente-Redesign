<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
login_obrigatorio($usuario);
?>
<?php
$erro = [];
$sucesso = [];
$id = $_GET['id'] ?? null;
$projeto = projeto_requestIDator($id);
$voltarPara = $_GET['volta'] ?? 'proj';

$projeto_e_meu = false;

if (isset($usuario)) {
	$projeto_e_meu = $projeto->id_criador == $usuario->id;

	if (!$projeto_e_meu) {
		erro_403();
	}
}

if ($voltarPara == 'criar') {
		$johnTravolta = '/criar/';
} else {
		$johnTravolta = '/projetos/' . $id;
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
	
	.statusbar {
		margin-right: 0px;
	}

	.aba {
		display:none;
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
		width: <?= ($projeto->tipo == 'jg' ? '33' : '49') ?>%;
		font-size: 14px;
		margin-right:-5px;
	}

	.abaAtiva {
		border-bottom: 1px solid #FFFFFF !important;
		background-color: white;
	}
	
	.labelDeVerdade {
		font-weight: normal;
		font-size: 12px;
		display: inline;
	}


	.barrinha {
		border: 1px solid #9ebbff;
		margin-top: 4px;
		width: 430px;
		background-image: url("/elementos/ranks/vidro.png");

		/* TODO PODEROSO DISPLAY TABLE */
		/* TODO PODEROSO DISPLAY TABLE */
		/* TODO PODEROSO DISPLAY TABLE */
		display: table;
		/* TODO PODEROSO DISPLAY TABLE */
		/* TODO PODEROSO DISPLAY TABLE */
		/* TODO PODEROSO DISPLAY TABLE */
	}

	/* aqui esta um exemplo de como desenvolver para Varios Browsers */
	.barrinha .juice {
		width: 430px;
		height: 24px;
		background-image: url("/elementos/ranks/davejuice.png");
		animation: davejuice_flow 25s infinite linear;
		-webkit-animation: davejuice_webkitflow 25s infinite linear;
		-moz-animation: davejuice_mozflow 25s infinite linear;
	}
	
	#uploadProgresso {
		text-align: center;
		width: 100%;
		display: block;
		margin-top: 2px;
		font-size: 14px;
	}
</style>

<div class="container">
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

	<div class="page_content">
		<div class="inside_page_content" style="padding-right: 0px;">
			<div class="statusbar error" id="errorTemplate" onclick="this.style.display='none'" title="Clique para fechar" style="display: none;">
				<img src="/elementos/erro.gif" alt="" style="float: left;">
				<img src="/elementos/erro.gif" alt="" style="float: right;">
				<p>Seu erro vai aqui lol</p>
			</div>

			<div class="statusbar success" id="successTemplate" onclick="this.style.display='none'" title="Clique para fechar" style="display: none;">
				<img src="/elementos/emoticons/_yay_.png" alt="" style="float: left;">
				<img src="/elementos/emoticons/_yay_.png" alt="" style="float: right;">
				<p>Seu sucesso vai aqui lol</p>
			</div>
			
			<?php if ($id == null) : ?>
				<p>wtf</p>
			<?php else : ?>
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
				<!-- Downloadável -->
				<a href="<?= $johnTravolta ?>"><img style="margin-left: -5px; margin-top: -5px;" src="/elementos/voltar.png"></a>
				<h1 style="text-align: center; font-style: italic;">Editando projeto</h1>

				<form action="/projetos/interno/editarInternal.php" method="post" enctype="multipart/form-data">
					<input type="hidden" name="tipo" value="<?= $projeto->tipo ?>">
					<input type="hidden" name="id" value="<?= $projeto->id ?>">

					<label for="nome" class="labelManeira">>> NOME</label>
					<input type="text" style="width: 97%" id="nome" name="nome" value="<?= $projeto->nome ?>" required>
					<br>

					<label for="descricao" class="labelManeira">>> <?= ($projeto->tipo == 'bg') ? 'POSTAGEM' : 'DESCRIÇÃO' ?></label>
					<textarea style="width: 97%" name="descricao" id="descricao"><?= $projeto->descricao ?></textarea>
					<br>
					
					<div class="separador" style="margin-bottom:4px"></div>
					<input type="checkbox" name="unlist" id="unlist" <?php if ($projeto->naolist == 1) { echo 'checked'; } ?>> <label for="unlist" class="labelDeVerdade">Marcar como não listado</label>
					<br>
					
					<div class="separador"></div>
					<!-- abas wuatafaq -->
					<div id="abaBotoes">
						<div style="float: left;" class="coiso"></div>
						<?php if ($projeto->tipo == 'jg') : ?>
						<button type="button" class="abaButt" onclick="inativarAsAbas(); this.className = 'abaButt abaAtiva'; abaNavs.style.display = 'block';">Jogo p/navegadores</button>
						<?php endif;?>
						<?php if ($projeto->tipo != 'rt') : ?>
						<button type="button" class="abaButt" onclick="inativarAsAbas(); this.className = 'abaButt abaAtiva'; abaArquivos.style.display = 'block';"><?= $projeto->tipo == 'jg' ? 'Downloadáveis' : ($projeto->tipo == 'bg' ? 'Anexos no post' : 'Arquivos') ?></button>
						<?php endif;?>
						<?php if ($projeto->tipo != 'dl') : ?>
						<button type="button" class="abaButt" onclick="inativarAsAbas(); this.className = 'abaButt abaAtiva'; abaThumb.style.display = 'block';">Thumbnail</button>
						<?php endif;?>
						<div style="float: right;" class="coiso"></div>
					</div>
				
					<?php if ($projeto->tipo == 'jg') : ?>
						<div class="aba" id="abaNavs">
							<p>Deixe em branco para deixar o mesmo arquivo que está agora.</p>
							<p>Esse arquivo deve ser:</p>
							<ul>
								<li>Um arquivo .swf/.sb/.sb2/.sb3 contendo seu jogo inteiro;</li>
								<li>OU um arquivo .zip com um index.html dentro que tenha o seu jogo;</li>
								<li>OU apenas um index.html</li>
							</ul>
							<input type="file" name="arquivoJogavel" id="arquivoJogavel" accept=".swf,.zip,.html,.sb,.sb2,.sb3">
							<p>Limite: <b>1GB</b></p>

							<input type="checkbox" name="removerArquivoJogavel" id="removerArquivoJogavel" onchange="
								if (this.checked) {
									document.getElementById('arquivoJogavel').setAttribute('disabled', 'disabled');
								} else {
									document.getElementById('arquivoJogavel').removeAttribute('disabled');
								}">
							<label for="removerArquivoJogavel" style="display: inline-block; font-size: 12px;">remover arquivo jogável</label>
						</div>
					<?php endif; ?>

					<?php if ($projeto->tipo != 'rt'): ?>
						<div class="aba" id="abaArquivos">
							<input type="hidden" name="ordem" value="<?= $projeto->arquivos_de_vdd ?>">
							<div id="multiFileUploader" style="margin-bottom: 10px;">
								<ul class="files">
									<?php if ($projeto->arquivos_de_vdd != '') : ?>
										<?php foreach (explode('\n', $projeto->arquivos_de_vdd) as $i => $arquivo) : ?>
											<li data-filename="<?= $arquivo ?>">
												<p style="width: 253px; margin: 0; display: inline-block"><?= $arquivo ?></p>
												<button type="button" class="coolButt vermelho" onclick="
											<?php if ($projeto->tipo != 'jg' && $projeto->tipo != 'bg') : ?>
											if (this.parentElement.parentElement.children.length > 1) {
											<?php endif; ?>
												marcarParaRemoção(this.parentElement);
												recalcularOrdem()
											<?php if ($projeto->tipo != 'jg' && $projeto->tipo != 'bg') : ?>
											}
											<?php endif; ?>
											// eu me recuso
										">Remover</button>
												<button type="button" class="coolButt" onclick="
											var prev = this.parentElement.previousElementSibling;
											if (prev) {
												prev.before(this.parentElement);
												recalcularOrdem();
											}
										">^</button>
												<button type="button" class="coolButt" onclick="
											var next = this.parentElement.nextElementSibling;
											if (next) {
												next.after(this.parentElement);
												recalcularOrdem();
											}
										">v</button>
											</li>
										<?php endforeach ?>
									<?php endif ?>
								</ul>
								<button class="coolButt grandissimo" type="button" onclick="addMais1()">+ Adicionar mais um</button>
							</div>
						</div>
					<?php endif; ?>

					<?php if ($projeto->tipo == 'jg' || $projeto->tipo == 'rt' ||	$projeto->tipo == 'md' ||	$projeto->tipo == 'bg') : ?>
						<div class="aba" id="abaThumb">
							<input type="file" name="thumb" id="thumb" accept=".png,.jpg,.jpeg,.gif,.bmp">

							<p>Deixe em branco para deixar a mesma thumbnail que está agora.</p>

							<input type="checkbox" name="removerThumb" id="removerThumb" onchange="
								if (this.checked) {
									document.getElementById('thumb').setAttribute('disabled', 'disabled');
								} else {
									document.getElementById('thumb').removeAttribute('disabled');
								}">
							<label for="removerThumb" style="display: inline-block; font-size: 12px;">remover thumbnail</label>
						</div>
					<?php endif; ?>
					
					<?php if ($projeto->tipo == 'rt'): ?>
						<a href="/gerenciator/index.php?pasta=<?= $projeto->arquivos_de_vdd ?>" target="_blank" class="coolButt grandissimo" style="font-size: 30px; margin-bottom: 20px">Editar site!!!</a>
					<?php endif; ?>

					<button type="button" onclick="fazerSubmit(this)" class="coolButt verde grandissimo">Salvar mudanças</button>
				</form>
				<a href="/projetos/deletar.php?id=<?= $projeto->id ?>" id="botaoDoMal" class="coolButt vermelho grandissimo">DELETAR PROJETO ???!?</a>

				<div id="fileTemplate" style="display: none;">
					<li data-filename="">
						<input type="file" name="arquivos[]" id="arquivos" <?php if ($projeto->tipo != 'bg') { ?> required <?php } ?> oninput="inputComico(this)" onchange="this.parentElement.setAttribute('data-filename', this.files[0].name); recalcularOrdem()">
						<button type="button" class="coolButt vermelho" onclick="
							<?php if ($projeto->tipo != 'jg' && $projeto->tipo != 'bg') : ?>
							if (this.parentElement.parentElement.children.length > 1) {
							<?php endif; ?>
								if (!confirm('Tem certeza que deseja remover este arquivo?')) return;
								this.parentElement.remove()
								recalcularOrdem()
							<?php if ($projeto->tipo != 'jg' && $projeto->tipo != 'bg') : ?>
							}
							<?php endif; ?>
							// ^ esse código tem ALMA tambpen (ver php para entender piada: https://github.com/neontflame/Especulamente-Redesign/blob/main/projetos/editar.php)
						">Remover</button>
						<button type="button" class="coolButt" onclick="
							var prev = this.parentElement.previousElementSibling;
							if (prev) {
								prev.before(this.parentElement);
								recalcularOrdem();
							}
						">^</button>
						<button type="button" class="coolButt" onclick="
							var next = this.parentElement.nextElementSibling;
							if (next) {
								next.after(this.parentElement);
								recalcularOrdem();
							}
						">v</button>
					</li>
				</div>
			<?php endif; ?>

			<!-- progresso -->
			<div id="uploadTrecos" style="display:none">
				<div class="barrinha">
					<div class="juice" id="uploadJuice" style="width: 430px"></div>
				</div>
				<span id="uploadProgresso">50%</span>
			</div>
			
		</div>
	</div>
</div>

<script>
	var abaBotoes = document.getElementById('abaBotoes');
	var primeiroBotaoAtivado = false
	for (var i = 0; i < abaBotoes.children.length; i++) {
		if (abaBotoes.children[i].className != 'coiso') {
			abaBotoes.children[i].className = 'abaButt';
			if (!primeiroBotaoAtivado) {
				abaBotoes.children[i].className = 'abaButt abaAtiva';
				primeiroBotaoAtivado = true
			}
		}
	}
	
	var abasReais = document.getElementsByClassName('aba');
	var primeiraAbaAtiva = false
	for (var i = 0; i < abasReais.length; i++) {
		abasReais[i].style.display = 'none';
		if (!primeiraAbaAtiva) {
			abasReais[i].style.display = 'block';
			primeiraAbaAtiva = true
		}
	}
</script>
			
<style>
	.removido p {
		text-decoration: line-through;
		font-style: italic;
		opacity: 0.5;
	}
</style>

<script>
	function addMais1() {
		var template = document.getElementById('fileTemplate').getElementsByTagName('li')[0];
		var clone = template.cloneNode(true);
		clone.style.display = 'list-item';
		document.getElementById('multiFileUploader').getElementsByClassName('files')[0].appendChild(clone);
	}

	function marcarParaRemoção(elemento) {
		var input = document.createElement('input');
		input.type = 'hidden';
		input.name = 'remover[]';
		input.value = elemento.children[0].innerText;
		document.getElementById('multiFileUploader').appendChild(input);

		elemento.className = 'removido';

		var remover = elemento.getElementsByTagName('button')[0];
		remover.className = 'coolButt verde';
		remover.innerText = 'Adicionar';
		remover.onclick = function() {
			marcarParaDesremoção(elemento);
			recalcularOrdem()
		}
	}

	function marcarParaDesremoção(elemento) {
		var input = document.querySelector('input[value="' + elemento.children[0].innerText + '"]');
		input.remove();

		elemento.className = '';

		var remover = elemento.getElementsByTagName('button')[0];
		remover.className = 'coolButt vermelho';
		remover.innerText = 'Remover';
		remover.onclick = function() {
			marcarParaRemoção(elemento);
			recalcularOrdem()
		}
	}

	function recalcularOrdem() {
		var ordem = []
		var files = document.getElementById('multiFileUploader').getElementsByClassName('files')[0].children;
		for (var i = 0; i < files.length; i++) {
			if (files[i].className == 'removido') continue;
			ordem.push(files[i].getAttribute('data-filename'));
		}

		document.querySelector('input[name="ordem"]').value = ordem.join('\\n');
	}

	<?php if ($projeto->tipo == 'bg') { ?>
		function extractFilename(path) {
			if (path.substr(0, 12) == "C:\\fakepath\\")
				return path.substr(12); // modern browser
			var x;
			x = path.lastIndexOf('/');
			if (x >= 0) // Unix-based path
				return path.substr(x + 1);
			x = path.lastIndexOf('\\');
			if (x >= 0) // Windows-based path
				return path.substr(x + 1);
			return path; // just the filename
		}

		function inputComico(valor) {
			document.getElementById("descricao").value += '\n![](' + extractFilename(valor.value) + ')';
		}
	<?php } ?>


	function addErro(oErro) {
		var template = document.getElementById('errorTemplate');
		
		var clone = template.cloneNode(true);
		clone.style.display = 'block';
		clone.removeAttribute('id');
		clone.getElementsByTagName('p')[0].innerHTML = oErro;
		
		var insidePageContent = document.getElementsByClassName('inside_page_content')[0]
		insidePageContent.insertBefore(clone, insidePageContent.firstChild);
	}

	function addSucesso(oSucesso) {
		var template = document.getElementById('successTemplate');
		
		var clone = template.cloneNode(true);
		clone.style.display = 'block';
		clone.removeAttribute('id');
		clone.getElementsByTagName('p')[0].innerHTML = oSucesso;
		
		var insidePageContent = document.getElementsByClassName('inside_page_content')[0]
		insidePageContent.insertBefore(clone, insidePageContent.firstChild);
	}

	function fazerSubmit(botao){
		var botaoDoMal = document.getElementById("botaoDoMal");
		var form = document.querySelectorAll('form[enctype="multipart/form-data"]')[0];
		
		// os breguenaites de browser véio
		if (!window.FormData || !window.XMLHttpRequest || !('upload' in new XMLHttpRequest())) {
			form.action += "?browserVeio=true&voltar=<?= $voltarPara ?>";
			form.submit();
			return;
		}
	
		var xhttp = new XMLHttpRequest();
		
		var tudoProgresso = document.getElementById('uploadTrecos');
		var textoProgresso = document.getElementById('uploadProgresso');
		var barraProgresso = document.getElementById('uploadJuice');
		
		var formData = new FormData(form);

		xhttp.onload = function () {
			barraProgresso.style.width = '430px';
            textoProgresso.textContent = 'Aguarde!';
			
			var allErrors = xhttp.responseText.split("\n");
			
			if (allErrors[0] == "erro") {
				for (var i = allErrors.length - 1; i >= 1; i--) {
					addErro(allErrors[i]);
				}
			}
			if (allErrors[0] == "sucesso") {
				for (var i = allErrors.length - 1; i >= 1; i--) {
					addSucesso(allErrors[i]);
				}
				<?php if ($projeto->tipo != 'rt'): ?>
				reloadFilelist();
				<?php endif; ?>
			}
			
			botao.disabled = false;
			botao.style.display = '';
			
			botaoDoMal.disabled = false;
			botaoDoMal.style.display = '';
			
			tudoProgresso.style.display = 'none';
		};
		
		xhttp.upload.onprogress = function(event) {
			if (event.lengthComputable) {
				textoProgresso.innerHTML = "Carregando arquivos... (" + Math.round((event.loaded/event.total) * 100) + "%)";
				barraProgresso.style.width = ((event.loaded/event.total) * 430) + 'px';
			}
			
			tudoProgresso.style.display = 'block';
		}

		xhttp.onerror = function() {
			addErro('Houve um erro não-descrito :(');
			
			botao.disabled = false;
			botao.style.display = '';
			
			botaoDoMal.disabled = false;
			botaoDoMal.style.display = '';
			
			tudoProgresso.display = 'none';
		}

		xhttp.onabort = function() {
			botao.disabled = false;
			botao.style.display = '';
			
			botaoDoMal.disabled = false;
			botaoDoMal.style.display = '';
			
			tudoProgresso.style.display = 'none';
		}
		
		xhttp.open("POST", form.action, true);
		xhttp.send(formData);
		botao.disabled = true;
		botao.style.display = 'none';
		
		botaoDoMal.disabled = true;
		botaoDoMal.style.display = 'none';
	}
	
	function reloadFilelist() {
		var xhttp = new XMLHttpRequest();
		
		xhttp.onload = function () {
			document.getElementById('multiFileUploader').getElementsByClassName('files')[0].innerHTML = this.responseText;
		};
		
		xhttp.open(
			"GET",
			"/projetos/interno/editarFilelist.php?id=" + <?= $projeto->id ?>,
			true
		);
		
		xhttp.send();
	}
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>