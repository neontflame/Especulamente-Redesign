<?php
// $tipo é o tipo da coisa que tem os comentários (projeto ou perfil)
// $id é o id da coisa que tem os comentários
function vedor_d_comentario($tipo, $id, $temTudo, &$usuario)
{
	$comentarios = comentario_requestinator($tipo, $id);
	if ($temTudo == true) {
		echo '<div class="areaDeComentarios">';

		// php despedaçado quem diria !
		if (isset($usuario)) {
?>
			<img src="<?= pfp($usuario) ?>" alt="<?= $usuario->username; ?>" class="pfpComentarios">
			<div class="sayYourPrayers">
				<textarea name="comment_fnf" id="comment_fnf" style="width: 353px; max-width: 353px; height: 150px;"></textarea>
				<br>
				<button type="submit" onclick="postarComentario('<?= $tipo ?>', <?= $id ?>, document.getElementById('comment_fnf').value, 0, this);" class="coolButt">
					Enviar comentário
				</button>

				<button class="coolButt vermelho" onclick="document.getElementById('comment_fnf').value = '';">
					Cancelar
				</button>
				<br>
			</div>
		<?php
		}
		echo '<div id="osComentario">';
	}

	if (count($comentarios) == 0) {
		?>
		<p>Nenhum comentário ainda.</p>
	<?php
	} else {
	?>
		<ul>
			<?php foreach ($comentarios as $comentario) {
				$comentador = usuario_requestIDator($comentario->id_comentador);
				$respostas = respostas_requestinator($comentario->id);
			?>
				<!-- o comentario -->
				<li id="comentario_<?= $comentario->id ?>" class="comentario">
					<p class="nome">
						<a href="/usuarios/<?= $comentador->username; ?>">
							<img src="<?= pfp($comentador) ?>" alt="<?= $comentador->username; ?>" class="pfpComentarios">
							<?= $comentador->username; ?></a> em <?= velhificar_data($comentario->data); ?>
						<a class="linkmentario" onclick="document.getElementById('comment_fnf').value += '>><?= $comentario->id ?>'">&gt;&gt;<?= $comentario->id ?></a>
						</a>
					</p>
					<div class="conteudo">
						<p class="texto"><?= responde_clickers($comentario->texto); ?></p>
						<?php if (isset($usuario)) { ?>
							<button class="coolButt verde" onclick="document.getElementById('respondedor_<?= $comentario->id ?>').style.display='block';">Responder</button>
							<?php if ($usuario->id == $comentador->id) { ?>
								<button class="coolButt vermelho" onclick="deletarComentario('<?= $tipo ?>', <?= $id ?>, <?= $comentario->id ?>, this)">Deletar</button>
							<?php } ?>
						<?php } ?>
					</div>
					<!-- e assim que se responde comentarios -->
					<div id="respondedor_<?= $comentario->id ?>" style="display: none; padding-top: 8px; margin-bottom: 8px;">
						<textarea name="resposta_fnf_<?= $comentario->id ?>" id="resposta_fnf_<?= $comentario->id ?>" style="width: 414px; max-width: 414px; height: 75px;">&gt;&gt;<?= $comentario->id ?> </textarea>
						<br>
						<button type="submit" onclick="postarComentario('<?= $tipo ?>', <?= $id ?>, document.getElementById('resposta_fnf_<?= $comentario->id ?>').value, <?= $comentario->id ?>, this);" class="coolButt">
							Enviar comentário
						</button>

						<button class="coolButt vermelho" onclick="document.getElementById('resposta_fnf_<?= $comentario->id ?>').value = '>><?= $comentario->id ?>'; document.getElementById('respondedor_<?= $comentario->id ?>').style.display='none';">
							Cancelar
						</button>
						<br>
					</div>

					<!-- e agora respostas -->
					<?php if (count($respostas) > 0) { ?>
						<ul class="resposta">
							<?php foreach ($respostas as $resposta) {
								$respondente = usuario_requestIDator($resposta->id_comentador);
							?>
								<li id="comentario_<?= $resposta->id ?>" class="comentario">
									<p class="nome">
										<a href="/usuarios/<?= $respondente->username; ?>">
											<img src="<?= pfp($respondente) ?>" alt="<?= $respondente->username; ?>" class="pfpComentarios">
											<?= $respondente->username; ?></a> em <?= velhificar_data($resposta->data); ?>
										<a class="linkmentario" onclick="document.getElementById('comment_fnf').value += '>><?= $resposta->id ?>'">&gt;&gt;<?= $resposta->id ?></a>
										</a>
									</p>
									<div class="conteudo">
										<p class="texto" style="float:none"><?= responde_clickers($resposta->texto); ?></p>
										<?php if (isset($usuario)) { ?>
											<button class="coolButt verde" onclick="document.getElementById('respondedor_<?= $resposta->id ?>').style.display='block';">Responder</button>
											<?php if ($usuario->id == $respondente->id) { ?>
												<button class="coolButt vermelho" onclick="deletarComentario('<?= $tipo ?>', <?= $id ?>, <?= $resposta->id ?>, this)">Deletar</button>
											<?php } ?>
										<?php } ?>
									</div>
									<!-- e assim que se responde respostas -->
									<?php if (isset($usuario)) { ?>
										<div id="respondedor_<?= $resposta->id ?>" style="display: none; padding-top: 8px; margin-bottom: 8px;">
											<textarea name="resposta_fnf_<?= $resposta->id ?>" id="resposta_fnf_<?= $resposta->id ?>" style="width: 338px; max-width: 338px; height: 150px;">&gt;&gt;<?= $resposta->id ?> </textarea>
											<br>
											<button type="submit" onclick="postarComentario('<?= $tipo ?>', <?= $id ?>, document.getElementById('resposta_fnf_<?= $resposta->id ?>').value, <?= $resposta->fio ?>, this);" class="coolButt">
												Enviar comentário
											</button>

											<button class="coolButt vermelho" onclick="document.getElementById('resposta_fnf_<?= $resposta->id ?>').value = '>><?= $resposta->id ?>'; document.getElementById('respondedor_<?= $resposta->id ?>').style.display='none';">
												Cancelar
											</button>
											<br>
										</div>
									<?php } ?>
								</li>
							<?php } ?>
						</ul>
					<?php } ?>
				</li>
			<?php } ?>
		</ul>
<?php
	}
	if ($temTudo == true) {
		echo '</div></div>';
	}
}


function responde_clickers($texto)
{
	$replace = [
		'/&gt;&gt;(\d+)/' => '<a href="#comentario_$1" onmouseenter="document.getElementById(\'comentario_$1\').className=\'comentario livel\'" onmouseleave="document.getElementById(\'comentario_$1\').className=\'comentario\'">&gt;&gt;$1</a>'
	];

	$dir = $_SERVER['DOCUMENT_ROOT'] . "/elementos/emoticons/";
	if ($handle = scandir($dir)) {
		foreach ($handle as $target) {
			if (!in_array($target, [".", ".."])) {
				$replace += ['/' . pathinfo($dir . $target, PATHINFO_FILENAME) . '/' => '<img src="/elementos/emoticons/' . $target . '" alt="' . pathinfo($dir . $target, PATHINFO_FILENAME) . '">'];
			}
		}
	}

	$texto = htmlspecialchars($texto);

	return preg_replace(array_keys($replace), array_values($replace), $texto);
}
/* por algum motivo o mario quebra o vedor D imagem entao ele vai ter que ficar aqui :(
		^ pior dia da minha vida
 ⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣠⣤⣶⠶⠒⠂⠀⠐⠶⠶⠶⣶⣤⣀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣠⡾⠟⠋⠀⣀⣤⢶⡿⠿⠿⢿⢶⣤⡀⠈⠙⠷⣦⣀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣠⡾⠋⠀⠀⢀⣾⠋⠀⣸⣧⡀⣰⣾⡆⠉⢿⣶⣤⠤⠀⠙⢷⣄⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣠⡾⠃⠀⠀⠀⢀⣾⠇⠀⣼⡿⠻⣿⡿⢻⣿⣆⠀⢻⣿⣤⣀⠀⠀⠹⣦⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣰⠟⠀⠀⠀⠀⢀⣸⣿⡄⢸⣿⠇⠀⠈⠀⠀⢻⣿⡄⣾⣿⣿⣿⣶⣄⠀⠈⢷⡄⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⣼⠏⠀⡀⠀⠀⢈⣸⣷⣽⣿⣦⣝⣀⣤⣤⣤⣤⣼⣭⣾⣿⣿⣿⣿⣿⣿⣷⠀⠈⣿⡄⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⢸⠏⠀⠀⠀⣤⣶⣶⣿⣿⣿⣶⣾⣿⣿⣿⣿⣿⣿⣿⣿⣶⣯⣭⣛⣿⣿⣿⣿⣿⡄⠘⣷⡀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⢀⡟⠀⢀⣶⣿⣿⣿⣿⣿⣿⣿⣿⡿⢿⠿⠛⠛⠛⠛⠿⢿⣿⣿⣿⣿⣿⣿⣾⣿⣿⣿⡆⢹⣧⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⢸⣿⣠⣿⣿⣿⣿⡿⢿⣿⣿⣿⣿⣿⣆⠄⠀⠀⠀⠀⢀⣾⣿⣿⣿⣿⣿⠿⢿⣿⣿⡿⣿⣶⣿⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠸⣿⣿⣿⣿⡿⠁⠁⡿⢏⣺⣽⣷⣯⣿⡄⠀⡀⠀⠀⣼⣻⡿⠧⣤⡈⠛⠂⠀⠻⣿⣿⣿⣿⣿⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⢻⣿⣿⣿⡇⠀⠀⠉⡿⠋⢠⣶⣚⣿⡄⠀⠀⠀⢰⣿⣟⣳⣦⠀⢻⡀⠀⠀⠀⣿⣿⣿⣿⠋⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⣀⡀⠈⢻⣿⡇⠀⠀⠀⡇⠀⣿⣿⣭⣿⣷⠀⠀⠀⢸⣿⣯⣿⡿⡇⠀⡇⠀⠀⠀⣿⣿⠋⠁⣶⡄⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⢰⣿⠋⠁⣸⣿⣿⠀⠀⠀⣧⠀⢻⣿⡿⠋⠁⠀⠀⠀⠀⠉⣻⣿⣿⣧⣀⠇⠀⠀⣴⣿⣿⡀⠀⢹⣷⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠸⡇⠀⣼⢻⣿⠃⢀⣶⣄⠀⠀⠈⠙⠀⠀⠀⠀⠀⠀⠀⠀⠈⠉⠛⠛⠋⢀⣰⡇⠉⢻⣿⣷⡄⢸⡟⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⢷⡀⠈⠸⠟⠀⢸⣿⣿⣷⣶⣄⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⣴⣶⣿⣿⣿⡄⠈⣿⣿⣇⣿⠁⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠳⣄⠀⠀⠀⠸⣿⣿⣿⣿⣿⣄⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣼⣿⣿⣿⣿⡿⠃⠀⢸⣷⡿⠃⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠈⠛⢦⡀⠀⠈⠙⢿⣿⣿⣿⣧⣄⡀⠀⠀⠀⠀⣀⣤⣾⣿⣿⣿⠟⠉⠀⠀⣠⠞⠋⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠳⣄⠀⠀⠀⠙⠛⢿⡛⢿⣿⣿⡿⣿⣿⣿⠿⣻⠟⠛⠁⠀⠀⢀⡴⠋⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠈⠳⢤⡀⠀⠀⠀⠀⠀⣀⣀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣀⡴⠏⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⣤⣴⣿⣤⡀⠀⠀⠀⠈⠉⠉⠁⠀⠀⠀⠀⠀⣰⣷⣿⣤⣄⡀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣀⣴⠿⠋⢿⡿⠉⠻⢿⣷⣦⣄⣀⣀⣀⣀⣀⣠⣤⣾⣿⣿⣿⢿⣿⣿⣿⣷⣄⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⣠⣾⠏⠀⠀⢠⣿⠁⠀⠀⢸⣿⠋⠛⠛⠿⠿⢿⣿⣿⣿⣿⣿⢩⡀⠘⣿⣿⣿⣿⣿⣿⣦⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⢀⣴⡟⠁⠀⠀⢀⣾⣷⣠⠤⣤⣸⡿⣿⣷⣶⣤⣤⣴⣶⣿⣿⢿⣿⣾⠿⠶⣿⣿⣿⣿⣿⣿⣿⣷⣄⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⣠⡿⠋⠀⠀⠀⣀⣿⣿⠉⠀⠀⠈⢻⡇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣿⡇⠀⠀⠈⢻⣿⣿⣿⣿⣿⣿⣿⣷⡄⠀⠀⠀⠀⠀⠀
⠀⠀⠀⢀⣼⠏⠀⠀⠀⢀⣴⣿⡟⣧⠀⠀⠀⣰⣿⡇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠘⣧⡀⠀⠀⢸⣿⣿⣿⣿⣿⣿⣿⡟⠿⣦⠀⠀⠀⠀⠀
⠀⠀⣴⡿⠁⠀⠀⠀⣰⣾⣿⡿⠃⠘⣷⣶⣾⣿⠟⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠘⠾⣿⡶⣶⣿⡋⠘⣿⣿⣿⣿⣿⡇⠆⢽⣷⡀⠀⠀⠀
⢠⣾⡛⠁⠀⠀⢠⣠⣿⣿⡿⠀⠀⠀⠀⠉⠉⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠈⠙⠛⠉⠁⠈⢿⣿⣿⣿⡿⠀⠆⠀⠙⣿⣧⡀⠀
⠉⠉⠛⢶⣦⣠⣾⣿⣿⣿⠇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢺⣿⣿⣿⣃⣀⣠⣶⠿⠛⠉⠉⠀
⠀⠀⠀⠀⠙⢿⣿⣿⡿⢻⡟⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢹⡏⢻⣿⣷⡽⠋⠀⠀⠀⠀⠀⠈
⠀⠀⠀⠀⢄⠀⣹⠟⠁⢸⣧⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸⡇⠀⠙⣿⠁⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠈⢻⣿⠀⠀⠈⣿⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣸⡇⠀⠀⠻⡿⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠘⣧⠀⠀⠀⢹⣧⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⣿⠃⠀⠀⢠⠇⠀⠀⠀⠀⠀⠀⠀
⠀⠀⢠⡄⠀⠀⢹⠀⠀⠀⠀⣿⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸⣟⠀⠀⠀⡾⠀⠀⠀⣀⡀⠀⠀⠀
⠀⠀⠉⡇⠀⠀⢸⠆⠀⠀⢸⡿⠀⠀⠀⠀⠀⠂⣦⣶⣶⣶⣶⣤⠴⣶⣦⣹⣷⣾⣶⡶⠐⠒⠒⠲⠂⠸⣿⡀⠀⠀⣧⠀⠀⣸⠋⠉⠀⠀⠀
⠀⠀⠀⣿⡀⢠⣿⠀⠀⠀⣿⠀⠀⠀⠀⠀⠀⠀⠀⠉⠻⣿⣿⣿⣤⣬⣿⣿⣿⣿⣯⣤⠖⠂⠀⠀⠀⠀⢿⡇⠀⠀⠹⣆⠀⢿⠀⠀⠀⠀⠀
⠀⠴⠚⠛⠛⠉⠁⠀⠀⢸⡿⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣻⣿⣿⠉⠹⣿⣿⡿⠟⠋⠀⠀⠀⠀⠀⠀⠀⢸⣧⠀⠀⠀⠈⠙⠚⠓⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⣸⡇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣿⣿⡇⠀⠀⢿⡏⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢿⡄⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⣿⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣿⣿⠀⠀⠀⢸⡇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸⡇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⢸⡇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢰⣿⡇⠀⠀⠀⠘⣷⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸⣧⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⢸⠃⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸⣿⡇⠀⠀⠀⠀⢿⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣿⡄⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⣿⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣿⣿⠁⠀⠀⠀⠀⠸⡇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠸⣧⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⢸⡏⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⣿⡏⠀⠀⠀⠀⠀⠀⣿⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣿⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⣿⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣼⣿⠁⠀⠀⠀⠀⠀⠀⢿⡄⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢻⡆⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⣸⣧⣤⣤⣄⣀⣀⠀⠀⠀⠀⠀⢠⣿⣿⠀⠀⠀⠀⠀⠀⠀⢸⡇⠀⠀⠀⠀⠀⠀⢀⣀⣠⣤⣤⣤⣿⡆⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⢀⣤⠞⠛⠀⠀⠈⠉⠉⠛⠻⣶⣤⣀⠀⠘⠛⣿⡀⠀⠀⠀⠀⠀⠀⣰⡇⠀⠀⢀⣠⣴⠞⠛⠋⠉⠁⠀⠀⠀⠙⢦⣄⠀⠀⠀⠀⠀
⠀⠀⣰⠟⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠈⠻⣷⣶⣿⣿⠇⠀⠀⠀⠀⠀⠀⣿⣄⣠⡶⠟⠉⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠈⢷⡀⠀⠀⠀
⠀⢠⣯⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠈⠛⣻⠀⠀⠀⠀⠀⠀⠀⣿⡏⠉⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠈⣷⠀⠀⠀
⠀⠸⣿⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⣶⡿⠀⠀⠀⠀⠀⠀⠀⠘⣿⢆⡀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣴⣿⠀⠀⠀
⠀⠀⢿⣿⣆⠀⠀⠀⠀⠀⠀⠀⢀⣠⣄⣀⡤⢆⣸⡿⠀⠀⠀⠀⠀⠀⠀⠀⠀⠈⠻⣷⣦⣤⣀⠀⠀⠀⠀⠀⠀⠀⢀⣀⣤⣴⣿⠏⠀⠀⠀
⠀⠀⠀⠙⠛⠻⠿⠿⠿⠾⠿⠿⠿⠟⠋⠉⠛⠉⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠈⠉⠂⠉⠛⠻⠿⠿⠿⠿⠿⠿⠿⠛⡛⠁⠀⠀⠀⠀ */
?>