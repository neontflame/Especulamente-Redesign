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

$estudio_e_meu = (isset($usuario) && $colecao->criador == $usuario->id);

function souCurador($id) {
	global $usuario;
	
	if (isset($usuario)) {
		return in_array($usuario->id, colecao_curacios($id));
	} else {
		return false;
	}
}

// Post Quests
if (isset($_POST)) {
	// curador exclusive
	if (souCurador($id)) {
		// add projeto
		if (isset($_POST['proj_fnf'])) {
			$pojejo = projeto_requestIDator($_POST['proj_fnf']);
			
			if ($pojejo == null) {
				erro('Esse projeto não existe!!');
			} else if (in_array($pojejo->id, colecao_projetos($id))) {
				erro('Esse projeto já foi adicionado na coleção!');
			} else {
				$rows = $db->prepare("INSERT INTO colecoes_projetos (id_colecao, id_projeto, id_adicionador) VALUES (?, ?, ?)");
				$rows->bindParam(1, $colecao->id);
				$rows->bindParam(2, $pojejo->id);
				$rows->bindParam(3, $usuario->id);
				$rows->execute();
				
				info('Projeto adicionado!');
			}
		}
		// remover proejto
		if (isset($_POST['removerProj_fnf'])) {
			$pojejo = projeto_requestIDator($_POST['removerProj_fnf']);
			
			if (!in_array($pojejo->id, colecao_projetos($id))) {
				erro('Esse projeto não está na coleção!');
			} else {
				$rows = $db->prepare("DELETE FROM colecoes_projetos WHERE id_colecao = ? AND id_projeto = ?");
				$rows->bindParam(1, $colecao->id);
				$rows->bindParam(2, $pojejo->id);
				$rows->execute();
				
				info('Projeto removido!');
			}
		}
		
		// mudar desc
		if (isset($_POST['desc_fnf'])) {
			$desc = $_POST['desc_fnf'];

			mudar_colecao($colecao->id, ['descricao' => $desc]);
			info('Descrição da coleção atualizada!');
		}
	}
	
	// dono do estudio exclusive
	if ($estudio_e_meu) {
		// add curador
		if (isset($_POST['user_fnf'])) {
			$usario = usuario_requestinator($_POST['user_fnf']);
			
			if ($usario == null) {
				erro('Esse usuário não existe!!');
			} else if (in_array($usario->id, colecao_curacios($id))) {
				erro('Esse usuário já é um curador!');
			} else {
				$rows = $db->prepare("INSERT INTO colecoes_curadores (id_colecao, id_curador) VALUES (?, ?)");
				$rows->bindParam(1, $colecao->id);
				$rows->bindParam(2, $usario->id);
				$rows->execute();
				
				criar_mensagem(
				$usario->id,
				<<<HTML
				<a href="/usuarios/{$usuario->username}" class="usuario">{$usuario->username}</a>
				adicionou você como curador na coleção
				<a href="/colecoes/{$id}">{$colecao->nome}</a>!
				HTML,
				'add'
				);
			
				info($usario->username . ' adicionado como curador!');
			}
		}
		
		// remove curador
		if (isset($_POST['removerUser_fnf'])) {
			$usario = usuario_requestIDator($_POST['removerUser_fnf']);
			
			if (!in_array($usario->id, colecao_curacios($id))) {
				erro('Esse usuário não é um curador!');
			} else {
				$rows = $db->prepare("DELETE FROM colecoes_curadores WHERE id_colecao = ? AND id_curador = ?");
				$rows->bindParam(1, $colecao->id);
				$rows->bindParam(2, $usario->id);
				$rows->execute();
				
				info('Curador removido!');
			}
		}
	}

	$colecao = colecao_requestIDator($id);
}

?>
<?php 
$meta["titulo"] = "[" . htmlspecialchars($colecao->nome) . " <> Coleção no PORTAL ESPECULAMENTE]";
$meta["descricao"] = str_replace("\n", " ", markdown_apenas_texto($colecao->descricao));
$meta["pagina"] = '/colecoes/' . $colecao->id;
$meta["imagem"] = $colecao->thumbnail ? '/static/colecoes/' . $colecao->thumbnail : null;
include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

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
			width: 101.2%;
			margin-top: 6px;
			margin-right:0px;
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
		
		.botaoDoLado {
			float: right;
		}
		
		
		/* roubado da pagina de usuarios mas #EuNaoToNemAi */
			.bioEditavel {
				background: none;
				border: none;
				position: relative;
				padding: 1px 1px;
				text-align: left;
				vertical-align: top;

				font: 12px "Verdana";
				color: #4f6bad;
			}

			.bioEditavel:hover {
				background-color: #FFFFDD;
			}

			.bioEditavel:active {
				background-color: #FFEEAA;
			}

			.bioButt {
				margin-top: 3px;
				width: 100%;
				background-color: #D6EBFF;
				border-style: solid;
				border-width: 1px;
				border-color: #5d85e2;

				font-family: Verdana;
			}

			.bioButt:hover {
				background-color: aliceblue;
			}

			.bioButt:active {
				background-color: #B5DCFF;
			}
			
			.editarInfo {
				display: block;
				text-align: center;
				text-decoration: none;
			}
			.editarInfo:hover {
				text-decoration: underline;
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

		<div class="inside_page_content">
			<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/statusbar.php'; ?>
			<?php if ($estudio_e_meu) : ?>
				<a href="/colecoes/<?= $colecao->id ?>/editar" class="editarInfo">Editar informações do estúdio</a>
			<?php endif; ?>
			<div id="abaBotoes">
				<div style="float: left;" class="coiso"></div>
				<button type="button" class="abaButt abaAtiva" onclick="inativarAsAbas(); this.className = 'abaButt abaAtiva'; abaProjs.style.display = 'table';">Projetos</button>
				<button type="button" class="abaButt" onclick="inativarAsAbas(); this.className = 'abaButt abaAtiva'; abaCurs.style.display = 'table';">Curadores</button>
				<button type="button" class="abaButt" onclick="inativarAsAbas(); this.className = 'abaButt abaAtiva'; abaDesc.style.display = 'table';">Descrição</button>
				<div style="float: right;" class="coiso"></div>
			</div>
			
			<!-- aba de projetos -->
			<div class="aba" id="abaProjs" style="display:table">
				<!-- adicionar projos -->
				<?php if (souCurador($id)) : ?>
				<form action="" method="post">
					<label for="curadoradd">ID:</label>
					<input type="text" name="proj_fnf" id="proj_fnf">
					<button class="coolButt verde">Adicionar projeto</button>
				</form>
				<div class="separador" style="margin-bottom:8px;"></div>
				<?php endif; ?>
				
				<?php
					$projos = colecao_projetos($id);
					
					if (count($projos) > 0) : ?>
					<div class="projetos">
						<?php
						foreach ($projos as $projejo) {
							// isso provavelmente pode ser feito de um jeito melhor mas Whatever!!! Blehhhhhhh
							$botao = '
							<form action="" method="post" enctype="multipart/form-data" id="form_removerProj">
								<input type="text" name="removerProj_fnf" id="removerProj_fnf" style="display: none;" value="' . $projejo . '">
								<button class="coolButt vermelho" style="display: block;">Remover</button>
							</form>
							';
							$projeto = projeto_requestIDator($projejo);
							renderarProjeto($projeto, true, false, (souCurador($colecao->id) ? $botao : null));
						}
						?>
					</div>
				<?php
					endif;
				?>
			</div>
			
			<!-- aba de curadores -->
			<div class="aba" id="abaCurs">
				<!-- adicionar caras -->
				<?php if ($estudio_e_meu) : ?>
				<form action="" method="post">
					<label for="user_fnf">Usuário:</label>
					<input type="text" name="user_fnf" id="user_fnf">
					<button class="coolButt verde">Adicionar curador</button>
				</form>
				<div class="separador" style="margin-bottom:4px;"></div>
				<?php endif; ?>
				
				<?php foreach (colecao_curacios($id) as $curador) { 
					$uusuuaario = usuario_requestIDator($curador) ?>
					<div class="rankeado">
						<a href="/usuarios/<?= $uusuuaario->username ?>"><img src="<?= pfp($uusuuaario) ?>"></a>
						<a class="username" href="/usuarios/<?= $uusuuaario->username ?>"><?= $uusuuaario->username ?></a>
						<?php if ($estudio_e_meu && ($uusuuaario->id != $usuario->id)) : ?>
							<form action="" method="post" enctype="multipart/form-data" id="form_removerUser" style="display:inline">
								<input type="text" name="removerUser_fnf" id="removerUser_fnf" style="display: none;" value="<?= $uusuuaario->id ?>">
								<button class="coolButt vermelho botaoDoLado">Remover</button>
							</form>
						<?php endif; ?>
					</div>
				<?php } ?>
			</div>
			
			<!-- aba de descriçao -->
			<div class="aba" id="abaDesc">
				<?php if (souCurador($colecao->id)) : ?>
					<button class="bioEditavel" onclick="form_desc.style.display = 'block'; desc.style.display = 'none'">
					<?php endif; ?>

					<p id="desc" style="margin-top: 0px; white-space: pre-line;">
						<?php if (souCurador($colecao->id) && ($colecao->descricao == null or $colecao->descricao == '')) : ?>vazio - insira algo aqui!<?php endif; ?>
						<?= responde_clickers($colecao->descricao) ?></p>

					<?php if (souCurador($colecao->id)) : ?>
					</button>
					<form action="" method="post" enctype="multipart/form-data" id="form_desc" style="display: none;">
						<textarea name="desc_fnf" id="desc_fnf" style="width: 425px; height: 300px;"><?= htmlspecialchars($colecao->descricao) ?></textarea>
						<button type="submit" class="bioButt">
							Salvar descrição
						</button>
					</form>
				<?php else :
					responde_clickers($colecao->descricao);
					endif; ?>
			</div>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>