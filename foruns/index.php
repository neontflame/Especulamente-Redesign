<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php'; ?>
<?php 
$forum = true;
$meta["titulo"] = "[Fóruns <> PORTAL ESPECULAMENTE]";
$meta["descricao"] = "Como se já não bastassem os blogs dos ESPECULATIVOS para pensar sozinho, agora você também pode pensar em GRUPO! Pense mais e fale mais com os FÓRUNS do PORTAL ESPECULAMENTE!";

function topicoCount($id) {
	global $db;
	
	$rows = $db->prepare("SELECT COUNT(*) as count FROM forum_posts WHERE id_categoria = ? AND id_resposta = -1");
	$rows->bindParam(1, $id, PDO::PARAM_INT);
	$rows->execute();
	$count = $rows->fetch(PDO::FETCH_OBJ)->count;

	return $count;
}

function postCount($id) {
	global $db;
	
	$rows = $db->prepare("SELECT COUNT(*) as count FROM forum_posts WHERE id_categoria = ?");
	$rows->bindParam(1, $id, PDO::PARAM_INT);
	$rows->execute();
	$count = $rows->fetch(PDO::FETCH_OBJ)->count;

	return $count;
}

function postMaisRecente($id) {
	global $db;
	
	$rows = $db->prepare("SELECT * FROM forum_posts WHERE id_categoria = ? AND id_resposta = -1 ORDER BY dataBump DESC LIMIT 1 OFFSET 00");
	$rows->bindParam(1, $id, PDO::PARAM_INT);
	$rows->execute();

	return $rows->fetch(PDO::FETCH_OBJ);
}

function topicoCountFull() {
	global $db;
	
	$rows = $db->prepare("SELECT COUNT(*) as count FROM forum_posts WHERE id_resposta = -1");
	$rows->execute();
	$count = $rows->fetch(PDO::FETCH_OBJ)->count;

	return $count;
}

function postCountFull() {
	global $db;
	
	$rows = $db->prepare("SELECT COUNT(*) as count FROM forum_posts");
	$rows->execute();
	$count = $rows->fetch(PDO::FETCH_OBJ)->count;

	return $count;
}
include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; 
?>

<div class="container">
	<img src="/elementos/pagetitles/foruns.png" class="inside_page_content" style="padding: 0px; margin-bottom: 7px;">

	<div>	
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
			<p class="labelManeira">>> TÓPICOS DO SITE</p>
				<table style="margin-left: -6px; width: 633px;">
					<thead>
						<tr>
							<th style="width: 360px;">Categoria</th>
							<th>Tópicos</th>
							<th>Posts</th>
							<th>Tópico mais recente</th>
						</tr>
					</thead>
					<tbody>
					<?php
					$cats = [];

					$pages = coisos_tudo($cats, 'forum_categorias', 1, '', ' WHERE tipoDeTopico = 0', 100);
					foreach ($cats as $cat) { ?>

						<tr><!-- woah coluna! -->
							<td><p><a href="/foruns/<?= $cat->id ?>">● <?= $cat->nome ?></a> <?= $cat->descricao ?></p></td> <!-- categora -->
							<td style="text-align: center;"><?= topicoCount($cat->id) ?></td> <!-- topicos quant -->
							<td style="text-align: center;"><?= postCount($cat->id) ?></td> <!-- posts quant -->
							<td><?php if (postMaisRecente($cat->id) != null) {
								echo '<a href="/foruns/' . $cat->id . '/' . postMaisRecente($cat->id)->id . '">' . htmlspecialchars(postMaisRecente($cat->id)->sujeito) . '</a>';
							} else {
								echo 'Não tem ainda :P';
							}
							?></td> <!-- topico mais recente-->
						</tr>
					<?php }
					?>
					</tbody>
				</table>
			<p class="labelManeira">>> TÓPICOS GERAIS</p>
				<table style="margin-left: -6px; width: 633px;">
					<thead>
						<tr>
							<th style="width: 360px;">Categorias</th>
							<th>Tópicos</th>
							<th>Posts</th>
							<th>Tópico mais recente</th>
						</tr>
					</thead>
					<tbody>
					<?php
					$cats = [];

					$pages = coisos_tudo($cats, 'forum_categorias', 1, '', ' WHERE tipoDeTopico = 1', 100);
					foreach ($cats as $cat) { ?>
						<tr><!-- woah coluna! -->
							<td><p><a href="/foruns/<?= $cat->id ?>">● <?= $cat->nome ?></a> <?= $cat->descricao ?></p></td> <!-- categora -->
							<td style="text-align: center;"><?= topicoCount($cat->id) ?></td> <!-- topicos quant -->
							<td style="text-align: center;"><?= postCount($cat->id) ?></td> <!-- posts quant -->
							<td><?php if (postMaisRecente($cat->id) != null) {
								echo htmlspecialchars(postMaisRecente($cat->id)->sujeito);
							} else {
								echo 'Não tem ainda :P';
							}
							?></td> <!-- topico mais recente-->
						</tr>
					<?php }
					?>
					</tbody>
				</table>
				
				<p>
				Fun fact: nós temos <?= topicoCountFull() ?> tópicos e <?= postCountFull() ?> posts ao todo!
				</p>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>