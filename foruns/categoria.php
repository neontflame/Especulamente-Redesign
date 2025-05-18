<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php'; ?>
<?php 
$forum = true;
$id = $_GET['id'] ?? 1;

$meta["titulo"] = "[" . categoria_requestIDator($id)->nome . " <> Fóruns do PORTAL ESPECULAMENTE]";
$meta["descricao"] = "Como se já não bastassem os blogs dos ESPECULATIVOS para pensar sozinho, agora você também pode pensar em GRUPO! Pense mais e fale mais com os FÓRUNS do PORTAL ESPECULAMENTE!";

function replyCount($id) {
	global $db;
	
	$rows = $db->prepare("SELECT COUNT(*) as count FROM forum_posts WHERE id_resposta = ?");
	$rows->bindParam(1, $id, PDO::PARAM_INT);
	$rows->execute();
	$count = $rows->fetch(PDO::FETCH_OBJ)->count;

	return $count;
}

function postMaisRecente($id) {
	global $db;
	
	$rows = $db->prepare("SELECT * FROM forum_posts WHERE id_resposta = ? ORDER BY dataBump DESC LIMIT 1 OFFSET 00");
	$rows->bindParam(1, $id, PDO::PARAM_INT);
	$rows->execute();

	return $rows->fetch(PDO::FETCH_OBJ);
}

include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; 
?>

<div class="container">
	<img src="/elementos/pagetitles/foruns.png" class="inside_page_content" style="padding: 0px; margin-bottom: 7px;">

	<div>
		<div class="projTitulo">
			<p><a href="/foruns">Fóruns</a> >> <i style="color: #4f6bad"><?= categoria_requestIDator($id)->nome ?></i></p>
		</div>
		
		<div class="inside_page_content">
			<style>
				table th {
					font-size: 9px;
					font-weight: normal;
				}
				
				table td {
					color: #A8A8A8;
				}
				
				table td p {
					font-size: 10px;
					margin-top: 4px;
					margin-bottom: 4px;
				}
				table td p a {
					font-size: 12px;
					font-weight: bold;
					text-decoration: none;
					color: #000000;
				}
				table td p a:hover {
					text-decoration: underline;
				}
				.labelManeira {
					font-size: 15px;
					font-weight: bold;
					margin-top: 4px;
					margin-bottom: 8px;
				}
			</style>
				<table style="margin-left: -6px; margin-top: -6px; width: 633px;">
					<thead>
						<tr>
							<th style="width: 360px;">Tópicos</th>
							<th>Respostas</th>
							<th>Resposta mais recente</th>
						</tr>
					</thead>
					<tbody>
					<?php
					$posts = [];

					$rows = $db->prepare("SELECT * FROM forum_posts WHERE id_categoria = ? AND id_resposta = -1 ORDER BY dataBump DESC");
					$rows->bindParam(1, $id, PDO::PARAM_INT);
					$rows->execute();
					
					
					while ($row = $rows->fetch(PDO::FETCH_OBJ)) {
						array_push($posts, $row);
					}
					foreach ($posts as $post) { ?>
						<tr><!-- woah coluna! -->
							<td><p><a href="/foruns/<?= $post->id_categoria ?>/<?= $post->id ?>">● <?= $post->sujeito ?></a> por <?=usuario_requestIDator($post->id_postador)->username ?></p></td> <!-- categora -->
							<td style="text-align: center;"><?= replyCount($post->id) ?></td> <!-- respostas quant -->
							<td><?php if (postMaisRecente($post->id) != null) {
								echo '<a href="/foruns/' . $post->id . '/' . postMaisRecente($post->id)->id . '"> Resposta de ' . usuario_requestIDator(postMaisRecente($post->id)->id_postador)->username . '</a>';
							} else {
								echo 'Não tem ainda :P';
							}
							?></td> <!-- topico mais recente-->
						</tr>
					<?php }
					?>
					</tbody>
				</table>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>