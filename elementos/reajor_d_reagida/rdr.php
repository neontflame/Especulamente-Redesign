<?php
// $tipo é o tipo da coisa que tem os reações (projeto ou perfil)
// $coisa é a coisa! um perfil ou um projeto
function reajor_d_reagida($tipo, &$coisa, &$usuario, $stringAdicional = null)
{
  $coisa_e_minha = $usuario ? ($tipo == "perfil" ? ($usuario->id == $coisa->id) : ($tipo == "forum" ? $usuario->id == $coisa->id_postador : $usuario->id == $coisa->id_criador)) : false;

  $ja_mitou = (isset($usuario) && $usuario) ? ja_reagiu($usuario->id, $coisa->id, $tipo, 'mitada') : false;
  $ja_sojou = (isset($usuario) && $usuario) ? ja_reagiu($usuario->id, $coisa->id, $tipo, 'sojada') : false;
?>
  <div class="reajorDReagida">
	<div class="oReajorEmSi">
    <button onclick="mitar_<?= $coisa->id ?>(this)" title="Mitar" id="mitar" <?= $ja_mitou ? "style='display: none;'" : "" ?>>
      <img src="/elementos/reajor_d_reagida/mitada_cinza.png" alt="mitada">
    </button>
    <button onclick="mitar_<?= $coisa->id ?>(this)" title="Desmitar" id="desmitar" <?= $ja_mitou ? "" : "style='display: none;'" ?>>
      <img src="/elementos/reajor_d_reagida/mitada.png" alt="mitada">
    </button>
    <span id="mitadas_cnt"><?= $coisa->mitadas ?></span>
    <span>ou</span>
    <span id="sojadas_cnt"><?= $coisa->sojadas ?></span>
    <button onclick="sojar_<?= $coisa->id ?>(this)" title="Sojar" id="sojar" <?= $ja_sojou ? "style='display: none;'" : "" ?>>
      <img src="/elementos/reajor_d_reagida/sojada_cinza.png" alt="sojada">
    </button>
    <button onclick="sojar_<?= $coisa->id ?>(this)" title="Dessojar" id="dessojar" <?= $ja_sojou ? "" : "style='display: none;'" ?>>
      <img src="/elementos/reajor_d_reagida/sojada.png" alt="sojada">
    </button>
	</div>
	<?php if (isset($stringAdicional)) : ?>
	<p class="stringAdicional"><?= $stringAdicional ?></p>
	<?php endif ?>
  </div>

  <?php if (isset($usuario)) : ?>
    <script>
      var ja_mitou_<?= $coisa->id ?> = <?= $ja_mitou ? 'true' : 'false' ?>;
      var ja_sojou_<?= $coisa->id ?> = <?= $ja_sojou ? 'true' : 'false' ?>;
	  
      function mitar_<?= $coisa->id ?>(that) {
		var coiso = that.parentElement;
		
        var req = new XMLHttpRequest();
        req.addEventListener("load", function() {
          if (this.responseText == "null" || this.responseText == "-1") {
            alert("Erro ao mitar");
          } else {
			if (this.responseText[this.responseText.length - 1] == '§') {
				coiso.querySelector('#mitadas_cnt').innerText = this.responseText.slice(0, this.responseText.length - 1);
				moeda(1);
			} else {
				coiso.querySelector('#mitadas_cnt').innerText = this.responseText;
			}
			
            ja_mitou_<?= $coisa->id ?> = !ja_mitou_<?= $coisa->id ?>;
            if (ja_mitou_<?= $coisa->id ?>) {
				coiso.querySelector("#mitar").style.display = "none";
            	coiso.querySelector("#desmitar").style.display = "";
            } else {
            	coiso.querySelector("#mitar").style.display = "";
            	coiso.querySelector("#desmitar").style.display = "none";
            }
          }
        });
        var formData = new FormData();
        formData.append("tipo", "<?= $tipo ?>");
        formData.append("id", <?= $coisa->id ?>);
        formData.append("reacao", "mitada");

        req.open("POST", "/elementos/reajor_d_reagida/reagir.php");
        req.send(formData);
      }

      function sojar_<?= $coisa->id ?>(that) {
		var coiso = that.parentElement;
		
        var req = new XMLHttpRequest();
        req.addEventListener("load", function() {
          if (this.responseText == "-1") {
            alert("Erro ao sojar");
          } else {
			if (this.responseText[this.responseText.length - 1] == '§') {
				coiso.querySelector('#sojadas_cnt').innerText = this.responseText.slice(0, this.responseText.length - 1);
				// moeda(1); pqq voce ganharia davecoins por espalhar odio
			} else {
				coiso.querySelector('#sojadas_cnt').innerText = this.responseText;
			}
            ja_sojou_<?= $coisa->id ?> = !ja_sojou_<?= $coisa->id ?>;
            if (ja_sojou_<?= $coisa->id ?>) {
              coiso.querySelector("#sojar").style.display = "none";
              coiso.querySelector("#dessojar").style.display = "";
            } else {
              coiso.querySelector("#sojar").style.display = "";
              coiso.querySelector("#dessojar").style.display = "none";
            }
          }
        });
        var formData = new FormData();
        formData.append("tipo", "<?= $tipo ?>");
        formData.append("id", <?= $coisa->id ?>);
        formData.append("reacao", "sojada");

        req.open("POST", "/elementos/reajor_d_reagida/reagir.php");
        req.send(formData);
      }
    </script>
  <?php endif; ?>
<?php
}
