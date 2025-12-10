<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
login_obrigatorio($usuario);
?>
<?php
$erro = [];
$tipo = $_GET['tipo'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
	if (isset($_POST['tipo'])) {
		$tipo = $_POST['tipo'];

		$nome = $_POST['nome'];
		$descricao = $_POST['descricao'];

		if (strlen($nome) < 3) {
			array_push($erro, "O nome do projeto é muito curto.");
		}

		$tiposBons = (($tipo == 'md') ? ['png', 'bmp', 'jpg', 'jpeg', 'gif', 'mp4', 'ogg', 'mp3', 'wav', 'avi', 'wmv', 'mkv', 'swf'] : (($tipo == 'bg') ? ['png', 'bmp', 'jpg', 'jpeg', 'gif', 'mp4', 'ogg', ''] : []));

		if ($tipo == 'dl' || $tipo == 'md' || $tipo == 'bg') {
			$arquivos = $_FILES['arquivos'] ?? null;
			$thumb = $_FILES['thumb'] ?? null;

			if (count($erro) == 0) {
				$projeto = criar_projeto($usuario->id, $nome, $descricao, $tipo, $arquivos, null, $thumb, $tiposBons);
				if (is_string($projeto)) {
					array_push($erro, $projeto);
				}
			}
		} else if ($tipo == 'jg') {
			$arquivoJogavel = $_FILES['arquivoJogavel'];
			$thumb = $_FILES['thumb'];
			$arquivos = $_FILES['arquivos'] ?? null;

			if (count($erro) == 0) {
				$projeto = criar_projeto($usuario->id, $nome, $descricao, $tipo, $arquivos, $arquivoJogavel, $thumb, []);
				if (is_string($projeto)) {
					array_push($erro, $projeto);
				}
			}
		} else if ($tipo == 'rt') {
			$pasta = $_POST['pasta'];
			$thumb = $_FILES['thumb'];

			$checadorDeCoiso = $db->prepare("SELECT * FROM projetos WHERE arquivos_de_vdd = ?");
			$checadorDeCoiso->bindParam(1, $pasta);
			$checadorDeCoiso->execute();

			if ($checadorDeCoiso->rowCount() != 0) {
				array_push($erro, "Cadê a originalidade? Esse nome de pasta JÁ existe.");
			}

			if (strlen($pasta) < 3) {
				array_push($erro, "O nome da pasta é muito curto.");
			}

			if (!preg_match('/^[a-zA-Z0-9_-]+$/', $pasta)) {
				array_push($erro, "NÃO use acentos, nem espaços, nem caracteres especiais.");
			}

			if (count($erro) == 0) {
				$projeto = criar_projeto($usuario->id, $nome, $descricao, $tipo, $pasta, null, $thumb, []);
				if (is_string($projeto)) {
					array_push($erro, $projeto);
				}
			}
		} else {
			array_push($erro, "Tipo de projeto inválido.");
		}

		// codigo debativelmente com alma porque eu fiquei com preguiça de colocar na funçao
		if ($_POST['unlist'] == 'checked') {
			$rows = $db->prepare("UPDATE projetos SET naolist = 1 WHERE id = ?");
			$rows->bindParam(1, $projeto->id);
			$rows->execute();
		}
				
		if (count($erro) == 0 && ($_POST['unlist'] != 'checked')) {
			if ($tipo == 'md') { fazer_bounty(4); }
			if ($tipo == 'dl') { fazer_bounty(5); }
			if ($tipo == 'bg') { fazer_bounty(6); }
			if ($tipo == 'jg') { fazer_bounty(7); }
			if ($tipo == 'rt') { fazer_bounty(8); }
			header('Location: /projetos/' . $projeto->id);
		}
	}
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
		width: <?= ($tipo == 'jg' ? '33' : '49') ?>%;
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
	
	.coisoDownloadavel {
		text-decoration: none;
		display: table;
		float: left;
	}
</style>

<style>
	.itemditavel {
		text-decoration: none;
		font-size: 12px;
	}

	.itemditavel:hover {
		text-decoration: underline;
	}

	.tipodl {
		color: #FF003B;
	}

	.tipojg {
		color: #EFAF6F;
	}

	.tipomd {
		color: #4DC13E;
	}

	.tipobg {
		color: #56A5EA;
	}

	.tiport {
		color: #878787;
	}
</style>
					
<div class="container">
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

	<div class="page_content">
		<div class="inside_page_content" style="padding-right: 0px;">
			<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/statusbarArray.php'; ?>
			<?php if ($tipo != null) : ?>
				<a href="/criar"><img style="margin-left: -5px; margin-top: -5px;" src="/elementos/voltar.png"></a>
			<?php endif; ?>

			<?php if ($tipo == null) : ?>
				<img src="/elementos/pagetitles/projeto-criator.png" style="margin-top: -5px; margin-left: -5px; text-decoration: none;">
				<h1 style="text-align: center; font-style: italic; font-weight: normal;">O que você quer criar hoje...?</h1>

				<!-- Coiso Downloadável -->
				<a href="/criar/dl" style="margin-left: -5px;" class="coisoDownloadavel">
					<img src="/elementos/projetos/dl.png" onmouseover="this.src='/elementos/projetos/dl-hover.png';" onmouseout="this.src='/elementos/projetos/dl.png';">
				</a>

				<a href="/criar/jg" class="coisoDownloadavel">
					<img src="/elementos/projetos/jogo.png" onmouseover="this.src='/elementos/projetos/jogo-hover.png';" onmouseout="this.src='/elementos/projetos/jogo.png';">
				</a>
				<a href="/criar/md" class="coisoDownloadavel">
					<img src="/elementos/projetos/midia.png" onmouseover="this.src='/elementos/projetos/midia-hover.png';" onmouseout="this.src='/elementos/projetos/midia.png';">
				</a>
				<a href="/criar/bg" class="coisoDownloadavel">
					<img src="/elementos/projetos/blog.png" onmouseover="this.src='/elementos/projetos/blog-hover.png';" onmouseout="this.src='/elementos/projetos/blog.png';">
				</a>
				<a href="/criar/rt" class="coisoDownloadavel">
					<img src="/elementos/projetos/resto.png" onmouseover="this.src='/elementos/projetos/resto-hover.png';" onmouseout="this.src='/elementos/projetos/resto.png';">
				</a>
				<a href="/criar/col" style="margin-left: -5px; margin-bottom: 4px;" class="coisoDownloadavel">
					<img src="/elementos/projetos/colecao.png" onmouseover="this.src='/elementos/projetos/colecao-hover.png';" onmouseout="this.src='/elementos/projetos/colecao.png';">
				</a>

					<h2 style="color: #000000; text-align: center; font-style: italic; font-weight: normal;">...ou talvez editar?</h2>
				<table style="margin-right: 5px">
					<thead>
						<tr>
							<th scope="col">Projetos</th>
							<th scope="col">Coleções</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<!-- projetos -->
							<td style="width: 50%; vertical-align:top;">
							<?php
							$projetos = [];

							// grandes numeros para grandes coisas
							$projejos = coisos_tudo($projetos, 'projetos', 1, null, ' WHERE id_criador = ' . $usuario->id, 10000000);

							if (count($projetos) > 0) : ?>
								<?php foreach ($projetos as $projeto) :	?>
									<a class="itemditavel tipo<?= $projeto->tipo ?>" href="/projetos/<?= $projeto->id ?>/editar?volta=criar">[<?= strtoupper($projeto->tipo) ?>] <?= $projeto->nome ?></a>
									<br>
								<?php endforeach;?>
							<?php endif; ?>
							</td>

							<!-- colecoes -->
							<td style="width: 50%; vertical-align:top;">
							<?php
							$colecoes = [];

							// grandes numeros para grandes coisas
							$colceios = coisos_tudo($colecoes, 'colecoes', 1, null, ' WHERE criador = ' . $usuario->id, 10000000);

							if (count($colecoes) > 0) : ?>
								<?php foreach ($colecoes as $colecao) :	?>
									<a class="itemditavel tipocol" href="/colecoes/<?= $colecao->id ?>/editar?volta=criar"><?= $colecao->nome ?></a>
									<br>
								<?php endforeach;?>
							<?php endif; ?>
							</td>
						</tr>
					</tbody>
					</table>
			<?php endif; ?>
			
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
			<?php if ($tipo == 'bg') : ?>
				<script>
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
				</script>
				<!-- Blogs -->
				<h1 style="text-align: center; font-style: italic;">Blog!</h1>
				<p><i>Escreva qualquer coisa aqui e publique para que qualquer pessoa na rede mundial de computadores possa ler o que você escreveu!!</i></p>

				<form action="" method="post" enctype="multipart/form-data">
					<input type="hidden" name="tipo" value="bg">

					<label for="nome" class="labelManeira">>> NOME</label>
					<input type="text" style="width: 97%" id="nome" name="nome" required value="<?= $nome ?? "" ?>">
					<br>

					<label for="descricao" class="labelManeira">>> POSTAGEM</label>
					<textarea style="width: 97%" name="descricao" id="descricao"><?= $descricao ?? "" ?></textarea>
					<br>
					
					<div class="separador" style="margin-bottom:4px"></div>
					<input type="checkbox" name="unlist" id="unlist"> <label for="unlist" class="labelDeVerdade">Marcar como não listado</label>
					<br>
					<div class="separador"></div>
					<!-- abas wuatafaq -->
					<div id="abaBotoes">
						<div style="float: left;" class="coiso"></div>
						<button type="button" class="abaButt abaAtiva" onclick="inativarAsAbas(); this.className = 'abaButt abaAtiva'; abaAnexos.style.display = 'table';">Anexos no post</button>
						<button type="button" class="abaButt" onclick="inativarAsAbas(); this.className = 'abaButt abaAtiva'; abaThumb.style.display = 'table';">Thumbnail (opcional)</button>
						<div style="float: right;" class="coiso"></div>
					</div>
					
					<div class="aba" id="abaAnexos" style="display:table;">
						<div id="multiFileUploader" style="margin-bottom: 10px;">
							<ul class="files">

							</ul>
							<button class="coolButt grandissimo" type="button" onclick="addMais1()">+ Adicionar mais um</button>
						</div>
					</div>

					<div class="aba" id="abaThumb">
						<p>A resolução dessa imagem pode ser qualquer uma, mas preferencialmente 92x76!</p>
						<input type="file" name="thumb" id="thumb" accept=".png,.jpg,.jpeg,.gif,.bmp">
					</div>
					
					<button type="submit" class="coolButt verde grandissimo">Criar</button>
				</form>
			<?php endif; ?>

			<?php if ($tipo == 'dl') : ?>
				<!-- Downloadável -->
				<h1 style="text-align: center; font-style: italic;">Downloadável!</h1>
				<p><i>Esse tipo de projeto oferece arquivos para descarga. Os usuários podem transferir as suas coisas para seus discos rígidos.</i></p>

				<form action="" method="post" enctype="multipart/form-data">
					<input type="hidden" name="tipo" value="dl">

					<label for="nome" class="labelManeira">>> NOME</label>
					<input type="text" style="width: 97%" id="nome" name="nome" required value="<?= $nome ?? "" ?>">
					<br>

					<label for="descricao" class="labelManeira">>> DESCRIÇÃO</label>
					<textarea style="width: 97%" name="descricao" id="descricao"><?= $descricao ?? "" ?></textarea>
					<br>
					<div class="separador" style="margin-bottom:4px"></div>
					<input type="checkbox" name="unlist" id="unlist"> <label for="unlist" class="labelDeVerdade">Marcar como não listado</label>
					<br>
					<div class="separador"></div>
					<label for="arquivos" class="labelManeira">>> ARQUIVOS</label>
					<div id="multiFileUploader" style="margin-bottom: 10px;">
						<ul class="files">

						</ul>
						<button class="coolButt grandissimo" type="button" onclick="addMais1()">+ Adicionar mais um</button>
					</div>

					<button type="submit" class="coolButt verde grandissimo">Criar</button>
				</form>
			<?php endif; ?>

			<?php if ($tipo == 'jg') : ?>
				<!-- JÓgos -->
				<h1 style="text-align: center; font-style: italic;">Jogos!</h1>
				<p><i>Esse tipo de projeto oferece Diversão e Brincadeiras diretamente na telinha do seu microcomputador. Usuários podem Jogar.</i></p>

				<form action="" method="post" enctype="multipart/form-data">
					<input type="hidden" name="tipo" value="jg">

					<label for="nome" class="labelManeira">>> NOME</label>
					<input type="text" style="width: 97%" id="nome" name="nome" required value="<?= $nome ?? "" ?>">
					<br>

					<label for="descricao" class="labelManeira">>> DESCRIÇÃO</label>
					<textarea style="width: 97%" name="descricao" id="descricao"><?= $descricao ?? "" ?></textarea>
					<br>
					<div class="separador" style="margin-bottom:4px"></div>
					<input type="checkbox" name="unlist" id="unlist"> <label for="unlist" class="labelDeVerdade">Marcar como não listado</label>
					<br>
					<div class="separador"></div>
					
					<!-- abas wuatafaq -->
					<div id="abaBotoes">
						<div style="float: left;" class="coiso"></div>
						<button type="button" class="abaButt abaAtiva" onclick="inativarAsAbas(); this.className = 'abaButt abaAtiva'; abaNavs.style.display = 'table';">Jogo p/navegadores</button>
						<button type="button" class="abaButt" onclick="inativarAsAbas(); this.className = 'abaButt abaAtiva'; abaThumb.style.display = 'table';">Thumbnail</button>
						<button type="button" class="abaButt" onclick="inativarAsAbas(); this.className = 'abaButt abaAtiva'; abaArquivos.style.display = 'table';">Downloadáveis</button>
						<div style="float: right;" class="coiso"></div>
					</div>
					
					<div class="aba" id="abaNavs" style="display:table";>
						<p>Esse arquivo deve ser:</p>
						<ul>
							<li>Um arquivo .swf/.sb/.sb2/.sb3 contendo seu jogo inteiro;</li>
							<li>OU um arquivo .zip com um index.html dentro que tenha o seu jogo;</li>
							<li>OU apenas um index.html</li>
						</ul>
						<p>Se o seu jogo não rodar em navegador, deixe em branco.</p>
						<input type="file" name="arquivoJogavel" id="arquivoJogavel" accept=".swf,.zip,.html,.sb,.sb2,.sb3">
						<p>Limite: <b>1GB</b></p>
					</div>
					<div class="aba" id="abaThumb">
						<p>A resolução dessa imagem pode ser qualquer uma, mas preferencialmente 92x76!</p>
						<input type="file" name="thumb" id="thumb" accept=".png,.jpg,.jpeg,.gif,.bmp">
						<!-- ^ esse código tem ALMA -->
					</div>
					<div class="aba" id="abaArquivos">
						<p>CASO o seu jogo possa ser baixado, ou tenha versão baixável, ou seja apenas baixável, suba aqui. Caso contrário, deixe em branco</p>
						<div id="multiFileUploader" style="margin-bottom: 10px;">
							<ul class="files">

							</ul>
							<button class="coolButt grandissimo" type="button" onclick="addMais1()">+ Adicionar mais um</button>
						</div>
						<p>Limite: <b>continua 1GB</b></p>
					</div>
					<div class="separador"></div>
					<button type="submit" class="coolButt verde grandissimo">Criar</button>
				</form>


			<?php endif; ?>

			<?php if ($tipo == 'md') : ?>
				<!-- Mídias -->
				<h1 style="text-align: center; font-style: italic;">Mídia!</h1>
				<p><i>Esse tipo de projeto oferece as imagens (dentre outras coisas) que você carregar aqui como se fosse um pequeno álbum!!</i></p>

				<form action="" method="post" enctype="multipart/form-data">
					<input type="hidden" name="tipo" value="md">

					<label for="nome" class="labelManeira">>> NOME</label>
					<input type="text" style="width: 97%" id="nome" name="nome" required value="<?= $nome ?? "" ?>">
					<br>

					<label for="descricao" class="labelManeira">>> DESCRIÇÃO</label>
					<textarea style="width: 97%" name="descricao" id="descricao"><?= $descricao ?? "" ?></textarea>
					<br>
					
					<div class="separador" style="margin-bottom:4px"></div>
					<input type="checkbox" name="unlist" id="unlist"> <label for="unlist" class="labelDeVerdade">Marcar como não listado</label>
					<br>
					<!-- abas wuatafaq -->
					<div id="abaBotoes">
						<div style="float: left;" class="coiso"></div>
						<button type="button" class="abaButt abaAtiva" onclick="inativarAsAbas(); this.className = 'abaButt abaAtiva'; abaMidias.style.display = 'table';">Imagens e vídeos</button>
						<button type="button" class="abaButt" onclick="inativarAsAbas(); this.className = 'abaButt abaAtiva'; abaThumb.style.display = 'table';">Thumbnail</button>
						<div style="float: right;" class="coiso"></div>
					</div>
					
					<div class="aba" id="abaMidias" style="display: table;">
						<div id="multiFileUploader" style="margin-bottom: 10px;">
							<ul class="files">

							</ul>
							<button class="coolButt grandissimo" type="button" onclick="addMais1()">+ Adicionar mais um</button>
						</div>
					</div>
					
					<div class="aba" id="abaThumb">
						<p>A resolução dessa imagem pode ser qualquer uma, mas preferencialmente 124x124px!</p>
						<input type="file" name="thumb" id="thumb" accept=".png,.jpg,.jpeg,.gif,.bmp">
						<!-- ^ esse código tem ALMA -->
					</div>
					
					<div class="separador"></div>
					<button type="submit" class="coolButt verde grandissimo">Criar</button>
				</form>
			<?php endif; ?>

			<?php if ($tipo == 'rt') : ?>
				<!-- JÓgos -->
				<h1 style="text-align: center; font-style: italic;">O Resto!</h1>
				<p><i>O resto é tipo o "geocities" (seja lá oq isso seja). Aqui vc pode criar e hospedar seus PRÓPRIOS sites {html, sem php!! (nem aspx (nem ruby)) :(}</i></p>

				<form action="" method="post" enctype="multipart/form-data">
					<input type="hidden" name="tipo" value="rt">

					<label for="nome" class="labelManeira">>> NOME</label>
					<input type="text" style="width: 97%" id="nome" name="nome" required value="<?= $nome ?? "" ?>">
					<br>

					<label for=" descricao" class="labelManeira">>> DESCRIÇÃO</label>
					<textarea style="width: 97%" name="descricao" id="descricao"><?= $descricao ?? "" ?></textarea>
					<br>
					<div class="separador" style="margin-bottom:4px"></div>
					<input type="checkbox" name="unlist" id="unlist"> <label for="unlist" class="labelDeVerdade">Marcar como não listado</label>
					<br>
					<!-- abas wuatafaq -->
					<div id="abaBotoes">
						<div style="float: left;" class="coiso"></div>
						<button type="button" class="abaButt abaAtiva" onclick="inativarAsAbas(); this.className = 'abaButt abaAtiva'; abaPasta.style.display = 'table';">Nome da pasta</button>
						<button type="button" class="abaButt" onclick="inativarAsAbas(); this.className = 'abaButt abaAtiva'; abaThumb.style.display = 'table';">Thumbnail</button>
						<div style="float: right;" class="coiso"></div>
					</div>
					
					<div class="aba" id="abaPasta" style="display: table;">
						<p>Sem espaços nem acentos, nem qlq coisa esquisita.</p>
						<p>Seu site estará disponível na página /~[nome_da_pasta]</p>
						<input type="text" style="width: 97%" id="pasta" name="pasta" required pattern="[a-zA-Z0-9_-]+" value="<?= $pasta ?? "" ?>">
						<br>
					</div>

					<div class="aba" id="abaThumb">
						<p>A resolução dessa imagem pode ser qualquer uma, mas preferencialmente 92x76!</p>
						<input type="file" name="thumb" id="thumb" accept=".png,.jpg,.jpeg,.gif,.bmp">
						<!-- ^ esse código tem ALMA -->
					</div>
					<div class="separador"></div>
					<button type="submit" class="coolButt verde grandissimo">Criar</button>
				</form>


			<?php endif; ?>

			<div id="fileTemplate" style="display: none;">
				<li>
					<input type="file" name="arquivos[]" id="arquivos" oninput="inputComico(this)" <?php if ($tipo != 'bg') { ?> required <?php } ?>>
					<button type="button" class="coolButt vermelho" onclick="
						<?php if ($tipo != 'jg' && $tipo != 'bg') : ?>
						if (this.parentElement.parentElement.children.length > 1) {
						<?php endif; ?>
							if (!confirm('Tem certeza que deseja remover este arquivo?')) return;
							this.parentElement.remove()
						<?php if ($tipo != 'jg' && $tipo != 'bg') : ?>
						}
						<?php endif; ?>
						// ^ esse código tem ALMA tambpen (ver php para entender piada: https://github.com/neontflame/Especulamente-Redesign/blob/main/projetos/criar.php)
					">Remover</button>
					<button type="button" class="coolButt" onclick="
						var prev = this.parentElement.previousElementSibling;
						if (prev) {
							prev.before(this.parentElement);
						}
					">^</button>
					<button type="button" class="coolButt" onclick="
						var next = this.parentElement.nextElementSibling;
						if (next) {
							next.after(this.parentElement);
						}
					">v</button>
				</li>
			</div>
		</div>
	</div>
</div>

<script>
	function addMais1() {
		var template = document.getElementById('fileTemplate').getElementsByTagName('li')[0];
		var clone = template.cloneNode(true);
		clone.style.display = 'list-item';
		document.getElementById('multiFileUploader').getElementsByClassName('files')[0].appendChild(clone);
	}

	<?php if ($tipo != 'jg') : ?>
		addMais1();
	<?php endif; ?>
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>