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

function jaFizARequest($id) {
	global $usuario;
	
	if (isset($usuario)) {
		foreach (requisicao_entregas($id) as $entrega) {
			if ($entrega->id_entregador == $usuario->id) {
				return true;
			}
		}
	}
	
	return false;
}

// Post Quests
if (isset($_POST)) {
	// curador exclusive
	if (!jaFizARequest($id)) {
		// add url
		if (isset($_POST['entrega_fnf'])) {
			$pojejo = $_POST['entrega_fnf'];
			
			if ($pojejo == null || !str_contains($url, 'especulamente.com.br') || !str_contains($url, $config['URL'])) {
				erro('Não tem como fazer uma entrega que não existe ou que seja fora do site!!');
			} else {
				$rows = $db->prepare("INSERT INTO requisicoes_entregas (id_requisicao, id_entregador, urlEntrega) VALUES (?, ?, ?)");
				$rows->bindParam(1, $requisicao->id);
				$rows->bindParam(2, $usuario->id);
				$rows->bindParam(3, $pojejo);
				$rows->execute();
				
				info('Entrega feita!');
			}
		}
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
		// add curador
		if (isset($_POST['entregaAceita']) && $requisicao->id_entrega == null) {
			$entrgea = requisicao_entrega_requestIDator($_POST['entregaAceita']);
			$usario = usuario_requestinator($entrgea->id_entregador);
			
			if ($entrgea == null) {
				erro('Essa entrega não existe!!');
			} else {
				$rows = $db->prepare("UPDATE requisicoes SET dataEntregue = CURRENT_TIMESTAMP, id_entrega = ? WHERE id = ?");
				$rows->bindParam(1, $usario->id);
				$rows->bindParam(2, $requisicao->id);
				$rows->execute();
				
				criar_mensagem(
				$usario->id,
				<<<HTML
				<a href="/usuarios/{$usuario->username}" class="usuario">{$usuario->username}</a>
				aceitou sua entrega em 
				<a href="/requisicoes/{$id}">{$requisicao->nome}</a>!
				HTML,
				'add'
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
			<p style="display: inline-block;"><a href="/requisicoes">Requisições</a> >> <i style="color: #4f6bad"><?= $requisicao->nome ?></i></p>
		</div>
			
		<div class="projTitulo">
			<h1 style="line-height: 6px; margin-top: 14px; font-weight: normal;"><i><?= $requisicao->nome ?></i></h1>
			<p style="margin-top: 12px;">por <a href="/usuarios/<?= usuario_requestIDator($requisicao->id_requisitor)->username ?>"><?= usuario_requestIDator($requisicao->id_requisitor)->username ?></a></p>
		</div>

		<div class="inside_page_content">
			<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/statusbar.php'; ?>
			todo o fucking resto
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>