<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';

if (!isset($_GET['id'])) {
	erro_404();
}
$id = $_GET['id'];

$requisicao = requisicao_requestIDator($id);

if ($requisicao == null) {
	erro_404();
}

$request_e_minha = (isset($usuario) && $requisicao->id_requisitor == $usuario->id);

function osRequestantes() {
	global $id;
	$requestantes = [];
	
	foreach (requisicao_entregas($id) as $entrega) {
		array_push($requestantes, usuario_requestIDator($entrega->id_entregador)->username);
	}
	$quantosForam = 0;
	$leString = '';
	
	foreach ($requestantes as $requestante) {
		$quantosForam += 1;
		$leString .= $requestante;
		if ($quantosForam < count($requestantes)) {
			$leString .= ', ';
		}
	}
	return $leString;
}

function jaFizARequest() {
	global $usuario;
	global $id;
	
	if (isset($usuario)) {
		foreach (requisicao_entregas($id) as $entrega) {
			if ($entrega->id_entregador == $usuario->id) {
				return true;
			}
		}
	}
	
	return false;
}

function noMoreReqs() {
	global $requisicao;
	return ($requisicao->dataEntregue != null);
}

// Post Quests
if (isset($_POST)) {
	// curador exclusive
	if (!jaFizARequest($id) && isset($usuario)) {
		// add url
		if (isset($_POST['entrega_fnf'])) {
			$pojejo = $_POST['entrega_fnf'];
			
			if (projeto_requestIDator($pojejo)->id_criador != $usuario->id) {
				erro('Esse projeto não é seu!');
			}
			else if ($pojejo == null || $pojejo == '') {
				erro('Não tem como fazer uma entrega que não existe!!');
			} else {
				$rows = $db->prepare("INSERT INTO requisicoes_entregas (id_requisicao, id_entregador, idProjeto) VALUES (?, ?, ?)");
				$rows->bindParam(1, $requisicao->id);
				$rows->bindParam(2, $usuario->id);
				$rows->bindParam(3, $pojejo);
				$rows->execute();
				
				criar_mensagem(
				$requisicao->id_requisitor,
				<<<HTML
				<a href="/usuarios/{$usuario->username}" class="usuario">{$usuario->username}</a>
				fez sua 
				<a href="/requisicoes/{$id}">requisição</a>!
				HTML,
				'add'
				);
			
			
				info('Entrega feita!');
			}
		}
	} else {
		// remover entrega
		if (isset($_POST['removerentrega_fnf'])) {
			$rows = $db->prepare("DELETE FROM requisicoes_entregas WHERE id_requisicao = ? AND id_entregador = ?");
			$rows->bindParam(1, $requisicao->id);
			$rows->bindParam(2, $usuario->id);
			$rows->execute();
			
			info('Entrega removida!');
		}
	}
	
	// dono do estudio exclusive
	if ($request_e_minha) {
		// aceita entrega
		if (isset($_POST['entregaAceita']) && $requisicao->id_entrega == null) {
			$entrgea = requisicao_entrega_requestIDator($_POST['entregaAceita']);
			$usario = usuario_requestIDator($entrgea->id_entregador);
			
			if ($entrgea == null) {
				erro('Essa entrega não existe!!');
			} else {
				$rows = $db->prepare("UPDATE requisicoes SET dataEntregue = CURRENT_TIMESTAMP, id_entrega = ? WHERE id = ?");
				$rows->bindParam(1, $entrgea->id);
				$rows->bindParam(2, $requisicao->id);
				$rows->execute();
				
				$preco = requisicao_preco($id);
				
				criar_mensagem(
				$entrgea->id_entregador,
				<<<HTML
				<a href="/usuarios/{$usuario->username}" class="usuario">{$usuario->username}</a>
				aceitou sua entrega em 
				<a href="/requisicoes/{$id}">{$requisicao->nome}</a>!
				<br>
				Você foi recompensado com {$preco} Dv¢.
				HTML,
				'add',
				requisicao_preco($requisicao->id)
				);
			
				info('Entrega de ' . $usario->username . ' aceita!!');
			}
		}
	}

	$requisicao = requisicao_requestIDator($id);
}

?>
<?php 
$meta["titulo"] = "[" . htmlspecialchars($requisicao->nome) . " <> Requisição no PORTAL ESPECULAMENTE]";
$meta["descricao"] = str_replace("\n", " ", markdown_apenas_texto($requisicao->descricao));
$meta["pagina"] = '/requisicoes/' . $requisicao->id;
include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<div class="container">
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>
	
	<style>
		.projTitulo {
			width: 442px;
		}
		
		.inside_page_content form {
			text-align: center;  
		}
		
		.botaoDoLado {
			float: right;
		}

		.contrato {
			text-align: center;
			
			border-top: 1px solid #8d5910;
			border-bottom: 1px solid #8d5910;

			margin-top: 0px;
			margin-left: -5px;
			margin-right: -5px;
			margin-bottom: 4px;
			
			padding-left: 5px;
			padding-right: 5px;
			padding-top: 4px;
			padding-bottom: 8px;

			font-family: "Times New Roman", serif;
			font-size: 15px;
			color: black;

			background-image: url("/elementos/requisicoes/fundo.png");
			background-repeat: repeat-x;
			background-color: antiquewhite;
		}
		
		.assinatura {
			font-family: "Segoe Script", cursive;
			text-decoration: underline;
			color: darkred;
		}

/* entregas */
.feitos h3 {
	text-align: center;
	margin: 8px;
}
.entrega {
  width: 100%;
  border-top: 1px solid #9ebbff;
  margin-top: 4px;
  padding-top: 4px;
  margin-bottom: 8px;
}

.entrega a {
  color: black;
  text-decoration: none;
  font-size: 16px;
}

.entrega a:hover {
  font-weight: bold;
}

.aceito {
  background-color: lightgreen;
  color: white;
  text-align: center;
  padding: 4px;
}

.aceito a {
  color: white;
  text-decoration: none;
}

.aceito a:hover {
  font-weight: bold;
}
	</style>
			
	<div class="page_content">
		<div class="projTitulo">
			<p style="display: inline-block;"><a href="/requisicoes">Requisições</a> >> <i style="color: #4f6bad"><?= $requisicao->nome ?></i></p>
		</div>
			
		<div class="projTitulo">
			<?php if ($request_e_minha) : ?>
				<a href="/requisicoes/<?= $requisicao->id ?>/editar" style="float:right; margin:8px;"><img src="/elementos/botaoEditarRequest.png"></a>
			<?php endif ?>
			
			<h1 style="line-height: 6px; margin-top: 14px; font-weight: normal;"><i><?= $requisicao->nome ?></i></h1>
			<p style="margin-top: 12px;">por <a href="/usuarios/<?= usuario_requestIDator($requisicao->id_requisitor)->username ?>"><?= usuario_requestIDator($requisicao->id_requisitor)->username ?></a></p>
		</div>

		<div class="inside_page_content">
			<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/statusbar.php'; ?>
			
			<?php if (noMoreReqs()) : ?>
			<div class="aceito">
			Essa request foi entregue por <a href="/usuarios/<?= usuario_requestIDator(requisicao_entrega_requestIDator($requisicao->id_entrega)->id_entregador)->username ?>">
			<?= usuario_requestIDator(requisicao_entrega_requestIDator($requisicao->id_entrega)->id_entregador)->username ?>!
			</a>
			<br>
			<a href="/projetos/<?= requisicao_entrega_requestIDator($requisicao->id_entrega)->idProjeto ?>">Clique aqui para ver.</a>
			</div>
			<?php endif;?>
			
			<div class="descricao">
				<?= responde_clickers($requisicao->descricao) ?>
			</div>
			
			<div class="contrato">
Para realizar uma requisição, deve ser enviado o número identificador (ID) do projeto para que, em seguida, o requerente faça a devida análise.
<br>
Após a realização dessa análise, o requerente efetuará o pagamento de <b><?= requisicao_preco($requisicao->id) ?> Dv¢</b> ao feitor da requisição. 
<br>
Na ocasião de que mais de uma pessoa tenha realizado a requisição, a escolha de quem será pago será decidida por intermédio da escolha manual do requerente.
<br>
<br>
<span class="assinatura"><?= strlen(osRequestantes()) > 0 ? osRequestantes() : '_____________________' ?></span>
<br>
Assinatura dos feitores
			</div>
			
			<div class="feitos">
				<h3>Entregas</h3>
				<?php if (count(requisicao_entregas($requisicao->id)) <= 0) : ?>
					<center>Não tem nenhuma entrega ainda...</center>
				<?php endif; ?>
				<?php foreach (requisicao_entregas($requisicao->id) as $feito) : ?>
					<div class="entrega">
						<a href="/projetos/<?= $feito->idProjeto ?>"><?= projeto_requestIDator($feito->idProjeto)->nome ?></a>
						<span style="font-size:10px">de <?= usuario_requestIDator($feito->id_entregador)->username ?></span>
						
						<?php if ($request_e_minha && !noMoreReqs()) : ?>
						<!-- vamos entregar ... -->
						<form action="" method="post" style="float:right;" onsubmit="return confirm('Tem certeza? Uma vez que você aceitou, você não pode mais mudar sua decisão.');">
							<input type="hidden" name="entregaAceita" value="<?= $feito->id ?>">
							<button type="submit" class="coolButt verde grandissimo">Aceitar</button>
						</form>
						<?php endif; ?>
						
						<?php if (noMoreReqs() && ($feito->id == $requisicao->id_entrega)) : ?>
						<span style="float:right; color:green;">Aceita!</span>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
			
			<?php if (isset($usuario) && !noMoreReqs() && !$request_e_minha): ?>
				<form action="" method="post" style="border-top: 1px solid #9ebbff; margin-top: 4px; padding-top: 4px;">
					<?php if (!jaFizARequest()) : ?>
						<label for="entrega_fnf">ID do projeto:</label>
						<input type="text" name="entrega_fnf" id="entrega_fnf">
						<button type="submit" class="coolButt verde grandissimo">Enviar resultado ao requerente</button>
					<?php else : ?>
						<input type="hidden" name="removerentrega_fnf" value="yeah">
						<button type="submit" class="coolButt vermelho grandissimo">Remover resultado</button>
					<?php endif; ?>
				</form>
		<?php endif; ?>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>