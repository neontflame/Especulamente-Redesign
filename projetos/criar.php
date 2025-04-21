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

    if ($tipo == 'dl' || $tipo == 'md') {
      $arquivos = $_FILES['arquivos'];

      if (count($erro) == 0) {
        $projeto = criar_projeto($usuario->id, $nome, $descricao, $tipo, $arquivos, null, null);
        if (is_string($projeto)) {
          array_push($erro, $projeto);
        }
      }
    } else if ($tipo == 'jg') {
      $arquivoJogavel = $_FILES['arquivoJogavel'];
      $thumb = $_FILES['thumb'];
      $arquivos = $_FILES['arquivos'];

      if (count($erro) == 0) {
        $projeto = criar_projeto($usuario->id, $nome, $descricao, $tipo, $arquivos, $arquivoJogavel, $thumb);
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
        $projeto = criar_projeto($usuario->id, $nome, $descricao, $tipo, $pasta, null, $thumb);
        if (is_string($projeto)) {
          array_push($erro, $projeto);
        }
      }
    } else {
      array_push($erro, "Tipo de projeto inválido.");
    }

    if (count($erro) == 0) {
      header('Location: /projetos/ver.php?id=' . $projeto->id);
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
</style>

<div class="container">
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

  <div class="page_content" style="min-height: 486px">
    <?php if ($erro) : ?>
      <div class="erro" style="color: red; background: black; text-align: center;">
        <img src="/static/skull-and-cross.gif" width="24" height="24" />
        <?= $erro[0] ?>
        <img src="/static/skull-and-cross.gif" width="24" height="24" />
      </div>
    <?php endif; ?>

    <div class="inside_page_content" style="padding-right: 0px;">
      <?php if ($tipo != null) : ?>
        <a href="/projetos/criar.php"><img style="margin-left: -5px; margin-top: -5px;" src="/elementos/voltar.png"></a>
      <?php endif; ?>

      <?php if ($tipo == null) : ?>
        <img src="/elementos/pagetitles/projeto-criator.png" style="margin-top: -5px; margin-left: -5px; text-decoration: none;">
        <h1 style="text-align: center; font-style: italic; font-weight: normal;">O que você quer criar hoje...?</h1>

        <!-- Coiso Downloadável -->
        <a href="/projetos/criar.php?tipo=dl" style="margin-left: -5px; margin-right:-4px; text-decoration: none;">
          <img src="/elementos/projetos/dl.png" onmouseover="this.src='/elementos/projetos/dl-hover.png';" onmouseout="this.src='/elementos/projetos/dl.png';">
        </a>

        <a href="/projetos/criar.php?tipo=jg" style="margin-right:-4px; text-decoration: none;">
          <img src="/elementos/projetos/jogo.png" onmouseover="this.src='/elementos/projetos/jogo-hover.png';" onmouseout="this.src='/elementos/projetos/jogo.png';">
        </a>
        <a href="/projetos/criar.php?tipo=md" style="margin-right:-5px; text-decoration: none;">
          <img src="/elementos/projetos/midia.png" onmouseover="this.src='/elementos/projetos/midia-hover.png';" onmouseout="this.src='/elementos/projetos/midia.png';">
        </a>
        <a href="/projetos/criar.php?tipo=bg" style="margin-right:-4px; text-decoration: none;">
          <img src="/elementos/projetos/blog.png" onmouseover="this.src='/elementos/projetos/blog-hover.png';" onmouseout="this.src='/elementos/projetos/blog.png';">
        </a>
        <a href="/projetos/criar.php?tipo=rt" style="text-decoration: none;">
          <img src="/elementos/projetos/resto.png" onmouseover="this.src='/elementos/projetos/resto-hover.png';" onmouseout="this.src='/elementos/projetos/resto.png';">
        </a>
		
		<?php 
		$projetos = [];
		
		// grandes numeros para grandes coisas
		$projejos = coisos_tudo($projetos, 'projetos', 1, null, ' WHERE id_criador = ' . $usuario->id, 10000000);
		
		if (count($projetos) > 0) { ?>
		<style>
		.itemditavel {
			text-decoration: none; 
			font-size: 16px;
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
		<h2 style="color: #000000; text-align: center; font-style: italic; font-weight: normal;">...ou talvez editar?</h2>
		<?php foreach ($projetos as $projeto) :  ?>
			<a class="itemditavel tipo<?= $projeto->tipo ?>" href="<?= $config['URL'] ?>/projetos/editar.php?id=<?= $projeto->id ?>">[<?= strtoupper($projeto->tipo) ?>] <?= $projeto->nome ?></a>
			<br>
      <?php endforeach;
		} ?>
	  <?php endif; ?>

      <?php if ($tipo == 'bg') : ?>
        <h1 style="text-align: center; font-style: italic; font-weight: normal;">isso ainda nao existe :(</h1>
      <?php endif; ?>

      <?php if ($tipo == 'dl') : ?>
        <!-- Downloadável -->
        <h1 style="text-align: center; font-style: italic;">Downloadável!</h1>
        <p><i>Esse tipo de projeto oferece arquivos para descarga. Os usuários podem transferir as suas coisas para seus discos rígidos.</i></p>

        <form action="/projetos/criar.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="tipo" value="dl">

          <label for="nome" class="labelManeira">>> NOME</label>
          <input type="text" style="width: 97%" id="nome" name="nome" required value="<?= $nome ?? "" ?>">
          <br>

          <label for="descricao" class="labelManeira">>> DESCRIÇÃO</label>
          <textarea style="width: 97%" name="descricao" id="descricao"><?= $descricao ?? "" ?></textarea>
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

        <form action="/projetos/criar.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="tipo" value="jg">

          <label for="nome" class="labelManeira">>> NOME</label>
          <input type="text" style="width: 97%" id="nome" name="nome" required value="<?= $nome ?? "" ?>">
          <br>

          <label for="descricao" class="labelManeira">>> DESCRIÇÃO</label>
          <textarea style="width: 97%" name="descricao" id="descricao"><?= $descricao ?? "" ?></textarea>
          <br>
          <div class="separador"></div>
          <label for="arquivoJogavel" class="labelManeira">>> ARQUIVO PRA NAVEGADORES</label>
          <p>Esse arquivo deve ser:</p>
          <ul>
            <li>Um arquivo .swf/.sb/.sb2/.sb3 contendo seu jogo inteiro;</li>
            <li>OU um arquivo .zip com um index.html dentro que tenha o seu jogo;</li>
            <li>OU apenas um index.html</li>
          </ul>
          <p>Se o seu jogo não rodar em navegador, deixe em branco.</p>
          <input type="file" name="arquivoJogavel" id="arquivoJogavel" accept=".swf,.zip,.html,.sb,.sb2,.sb3">
          <p>Limite: <b>1GB</b></p>

          <div class="separador"></div>
          <label for="thumb" class="labelManeira">>> THUMBNAIL</label>
          <p>A resolução dessa imagem pode ser qualquer uma, mas preferencialmente 92x76!</p>
          <input type="file" name="thumb" id="thumb" accept=".png,.jpg,.jpeg,.gif,.bmp">
          <!-- ^ esse código tem ALMA -->

          <div class="separador"></div>

          <label for="arquivos" class="labelManeira">>> ARQUIVOS DOWNLOADÁVEIS</label>
          <p>CASO o seu jogo possa ser baixado, ou tenha versão baixável, ou seja apenas baixável, suba aqui. Caso contrário, deixe em branco</p>
          <div id="multiFileUploader" style="margin-bottom: 10px;">
            <ul class="files">

            </ul>
            <button class="coolButt grandissimo" type="button" onclick="addMais1()">+ Adicionar mais um</button>
          </div>
          <p>Limite: <b>continua 1GB</b></p>

          <button type="submit" class="coolButt verde grandissimo">Criar</button>
        </form>


      <?php endif; ?>
	  
      <?php if ($tipo == 'md') : ?>
        <!-- Downloadável -->
        <h1 style="text-align: center; font-style: italic;">Mídia!</h1>
        <p><i>Esse tipo de projeto oferece as imagens (dentre outras coisas) que você carregar aqui como se fosse um pequeno álbum!!</i></p>

        <form action="/projetos/criar.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="tipo" value="md">

          <label for="nome" class="labelManeira">>> NOME</label>
          <input type="text" style="width: 97%" id="nome" name="nome" required value="<?= $nome ?? "" ?>">
          <br>

          <label for="descricao" class="labelManeira">>> DESCRIÇÃO</label>
          <textarea style="width: 97%" name="descricao" id="descricao"><?= $descricao ?? "" ?></textarea>
          <br>
          <div class="separador"></div>
          <label for="arquivos" class="labelManeira">>> IMAGENS E VÍDEOS</label>
          <div id="multiFileUploader" style="margin-bottom: 10px;">
            <ul class="files">

            </ul>
            <button class="coolButt grandissimo" type="button" onclick="addMais1()">+ Adicionar mais um</button>
          </div>

          <button type="submit" class="coolButt verde grandissimo">Criar</button>
        </form>
      <?php endif; ?>
	  
      <?php if ($tipo == 'rt') : ?>
        <!-- JÓgos -->
        <h1 style="text-align: center; font-style: italic;">O Resto!</h1>
        <p><i>O resto é tipo o "geocities" (seja lá oq isso seja). Aqui vc pode criar e hospedar seus PRÓPRIOS sites {html, sem php!! (nem aspx (nem ruby)) :(}</i></p>

        <form action="/projetos/criar.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="tipo" value="rt">

          <label for="nome" class="labelManeira">>> NOME</label>
          <input type="text" style="width: 97%" id="nome" name="nome" required value="<?= $nome ?? "" ?>">
          <br>

          <label for=" descricao" class="labelManeira">>> DESCRIÇÃO</label>
          <textarea style="width: 97%" name="descricao" id="descricao"><?= $descricao ?? "" ?></textarea>
          <br>

          <label for="pasta" class="labelManeira">>> NOME DA PASTA</label>
          <p>Sem espaços nem acentos, nem qlq coisa esquisita.</p>
          <p>Seu site estará disponível na página /~[nome_da_pasta]</p>
          <input type="text" style="width: 97%" id="pasta" name="pasta" required pattern="[a-zA-Z0-9_-]+" value="<?= $pasta ?? "" ?>">
          <br>

          <div class="separador"></div>
          <label for="thumb" class="labelManeira">>> THUMBNAIL</label>
          <p>A resolução dessa imagem pode ser qualquer uma, mas preferencialmente 92x76!</p>
          <input type="file" name="thumb" id="thumb" accept=".png,.jpg,.jpeg,.gif,.bmp">
          <!-- ^ esse código tem ALMA -->

          <button type="submit" class="coolButt verde grandissimo">Criar</button>
        </form>


      <?php endif; ?>

      <div id="fileTemplate" style="display: none;">
        <li>
          <input type="file" name="arquivos[]" id="arquivos" required>
          <button type="button" class="coolButt vermelho" onclick="
            <?php if ($tipo != 'jg') : ?>
            if (this.parentElement.parentElement.children.length > 1) {
            <?php endif; ?>
              if (!confirm('Tem certeza que deseja remover este arquivo?')) return;
              this.parentElement.remove()
            <?php if ($tipo != 'jg') : ?>
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