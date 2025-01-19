<?php
// $tipo é o tipo da coisa que tem os reações (projeto ou perfil)
// $coisa é a coisa! um perfil ou um projeto
function reajor_d_reagida($tipo, &$coisa, &$usuario)
{
  $coisa_e_minha = $usuario
    ? ($tipo == "perfil"
      ? ($usuario->id == $coisa->id)
      : $usuario->id == $coisa->id_criador)
    : false;

  $ja_mitou = (isset($usuario) && $usuario) ? ja_reagiu($usuario->id, $coisa->id, $tipo, 'mitada') : false;
  $ja_sojou = (isset($usuario) && $usuario) ? ja_reagiu($usuario->id, $coisa->id, $tipo, 'sojada') : false;
?>
  <div class="reajorDReagida">
	<div class="oReajorEmSi">
    <button onclick="mitar()" title="Mitar" id="mitar" <?= $ja_mitou ? "style='display: none;'" : "" ?>>
      <img src="/elementos/reajor_d_reagida/mitada_cinza.png" alt="mitada">
    </button>
    <button onclick="mitar()" title="Desmitar" id="desmitar" <?= $ja_mitou ? "" : "style='display: none;'" ?>>
      <img src="/elementos/reajor_d_reagida/mitada.png" alt="mitada">
    </button>
    <span id="mitadas_cnt"><?= $coisa->mitadas ?></span>
    <span>ou</span>
    <span id="sojadas_cnt"><?= $coisa->sojadas ?></span>
    <button onclick="sojar()" title="Sojar" id="sojar" <?= $ja_sojou ? "style='display: none;'" : "" ?>>
      <img src="/elementos/reajor_d_reagida/sojada_cinza.png" alt="sojada">
    </button>
    <button onclick="sojar()" title="Dessojar" id="dessojar" <?= $ja_sojou ? "" : "style='display: none;'" ?>>
      <img src="/elementos/reajor_d_reagida/sojada.png" alt="sojada">
    </button>
	</div>
  </div>

  <?php if (isset($usuario)) : ?>
    <script>
      var ja_mitou = <?= $ja_mitou ? 'true' : 'false' ?>;
      var ja_sojou = <?= $ja_sojou ? 'true' : 'false' ?>;

      function mitar() {
        var req = new XMLHttpRequest();
        req.addEventListener("load", function() {
          if (this.responseText == "null") {
            alert("Erro ao mitar");
          } else {
            mitadas_cnt.innerText = this.responseText;
            ja_mitou = !ja_mitou;
            if (ja_mitou) {
              document.getElementById("mitar").style.display = "none";
              document.getElementById("desmitar").style.display = "";
            } else {
              document.getElementById("mitar").style.display = "";
              document.getElementById("desmitar").style.display = "none";
            }
          }
        });
        var formData = new FormData();
        formData.append("tipo", "<?= $tipo ?>");
        formData.append("id", "<?= $coisa->id ?>");
        formData.append("reacao", "mitada");

        req.open("POST", "/elementos/reajor_d_reagida/reagir.php");
        req.send(formData);
      }

      function sojar() {
        var req = new XMLHttpRequest();
        req.addEventListener("load", function() {
          if (this.responseText == "-1") {
            alert("Erro ao sojar");
          } else {
            sojadas_cnt.innerText = this.responseText;
            ja_sojou = !ja_sojou;
            if (ja_sojou) {
              document.getElementById("sojar").style.display = "none";
              document.getElementById("dessojar").style.display = "";
            } else {
              document.getElementById("sojar").style.display = "";
              document.getElementById("dessojar").style.display = "none";
            }
          }
        });
        var formData = new FormData();
        formData.append("tipo", "<?= $tipo ?>");
        formData.append("id", "<?= $coisa->id ?>");
        formData.append("reacao", "sojada");

        req.open("POST", "/elementos/reajor_d_reagida/reagir.php");
        req.send(formData);
      }
    </script>
  <?php endif; ?>
<?php
}
