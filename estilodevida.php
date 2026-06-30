<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';

$meta["titulo"] = "[Clima & Horóscopo <> PORTAL ESPECULAMENTE]";
$meta["descricao"] = "Como você está se sentindo hoje? Está bem? Está com náusea? Aqui você vai descobrir o motivo de todos os seus problemas! Agradeça ao PORTAL ESPECULAMENTE, e proteja-se.";

?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<style>
/* FREAKING!!!! CLIMA!!!!! */
.climacio {
  width: 300px;
  height: 90px;
  margin-bottom: 12px;
}

.climacio .fuckingClima {
  display: table;
}

.climacio img {
  float:left;
}

.climacio .oRestoDoClima {
  float:right;
}

.climacio .oRestoDoClima .titulo {
  background-image: url("/elementos/clima/FuckingFundodoFuckingClimaFuck.png");
  width: 190px;
  font-weight: bold;
  padding: 2px;
  padding-left: 5px;
  color: white;
  display: table;
}

.climacio .oRestoDoClima .tempBig {
  font-size: 24px;
  font-weight: bold;
  float:left;
  margin-right: 6px;
}

.climacio .oRestoDoClima .tempSmall {
  font-size: 10px;
  display: block;
}

.climacio .oRestoDoClima .tempDesc {
  font-size: 10px;
  color: gray;
}

.climacio .descClima {
  color: gray;
}

/* FREAKING!!!! HOROSCOPO!!!!! */
.hoscopo {
  min-height: 110px;
}
.hoscopo img {
  float:left;
  margin-right: 8px;
}

.hoscopo h2 {
  color: gold;
  font-style: italic;
  font-size: 24px;
  margin: 0px;
  margin-bottom: -10px;
}
</style>
<div class="container">
	<img src="/elementos/pagetitles/climaEHoroscop.png" class="inside_page_content" style="padding: 0px; margin-bottom: 7px;">

	<div>
		<div class="inside_page_content" style="max-width: 621px;">
			<img src="/elementos/principaltitles/clima.png" style="margin-left: -5px; margin-bottom: 5px;">
			
			<div class="osClimas">
				<?php
					$cidadelist = [
						"Leiria",
						"São Paulo",
						"Valença",
						"Sorocaba",
						"Meu peanits né galera",
						"Sul",
						"Curitiba",
						"A Casa do Fupi",
						"Scratchin'",
						"Ilha do Especulapenguin",
						"Mansão do Hawnt", 
						"Brazil com Z",
						"Dimensão Hawnt",
						"Sala da Caveira", 
						"Mundo de Palitos",
						"Estados Unidos",
						"Estados Fudidos",
						"Estados Corridos",
						"Estados Cozidos",
						"Japão °❀⋆.ೃ࿔", //pq o henry gosta
						"Portugal",
						"Oceano Pacífico",
						"Vilhena",
						"Cidade do Sushi"
					];
					
					$climalist = [
						["Não tem", "44.gif", "eu comi tudo :(", [0, 0]],
						["Azul", "azul.png", "porque voce ta azul nao fique assim >:(", [10, 15]],
						["Verde", "verde.png", "porque voce ta verde", [15, 24]],
						["Chuva forte", "chuvaForte.png", "Lembre-se de usar um guarda-chuva quando sair!", [0, 13]],
						["Cuspe", "cuspe.png", "Lembre-se de usar um guarda-cuspe quando sair! Que nojeira :s", [12, 43]],
						["Maujoa juice", "maujoaJuice.png", "Lembre-se de usar um guarda-joa quando sair! Cool!", [19, 91]],
						["Nevasca", "neve.png", "Onde você pensa que está? Não neva no Brasil. A não ser que você esteja na Ilha do Especulapenguin. Pinguinando!", [-20, 0]],
						["Nublado", "nuvens.png", "Não consigo ver nada... o_o", [10, 19]],
						["Raptura", "raptura.png", "O clima pode estar ruim mas pelo menos temos bons salários", ["§", "deus"]],
						["Ensolarado", "sol.png", "Um dia ensolarado para ensolarar... ensolaroreia-os todos... :D", [20, 30]],
						["Ventania", "vento.png", "Cuidado pra não deixar seus papéis voarem por aí!", [15, 20]]
					];
					
					shuffle($cidadelist);
					
					renderar_merda($cidadelist[0], $climalist[array_rand($climalist)], 'left');
					renderar_merda($cidadelist[1], $climalist[array_rand($climalist)], 'right');
					renderar_merda($cidadelist[2], $climalist[array_rand($climalist)], 'left');
					renderar_merda($cidadelist[3], $climalist[array_rand($climalist)], 'right');
					renderar_merda($cidadelist[4], $climalist[array_rand($climalist)], 'left');
					renderar_merda($cidadelist[5], $climalist[array_rand($climalist)], 'right');
				?>
			</div>
			
			<img src="/elementos/principaltitles/hororscopo.png" style="margin-left: -5px; margin-bottom: 5px;">
			
			<div class="horoscopo">
				<?php

				$hoscopos = [
					"Hoje o mar está pra peixe! Vá pescar... ou morra tentando.",
					"Hoje o ar está pra peixe! Lembre-se de levar um guarda-peixe.",
					"Há a chance de um cara aleatório tentar bater em você atrás de um mcdonalds. Prossiga com cautela.",
					"Você perdeu o jogo.",
					"Você ganhou a chance de participar no jogo denovo. Eba!",
					"Seu futuro está repleto de nozes.",
					"Hoje é o dia do seu #amorficamente se realizar! Não fumbleie a bag. Por favor. Eu vou ficar tao desapontado se voce fumblear a bag cara por favor",
					"Você será crucificado. No take backsies.",
					"Você tem aids.",
					"Quando você encontrar um dinheiro no chão, cuspa nele. Confia em mim.",
					"Algo bom vai acontecer. Confia que o pai ta brabo hj",
					"Isso é um sonho. Acorde.",
					"Vinho, tainha e muito sexo.",
					"Sinta-se livre para socar uma pessoa na rua.",
					"O que você faria se um seguidor seu trocasse de conta? Pense nisso.",
					"O que você faria se os seus amigos inúteis ficassem jogando Roblox ao invés de darem sugestões pro horóscopo? Pense nisso.",
					"Você vai virar um tiozão que gosta de futebol em 10 anos.",
					"Confie nas variáveis."
				];
				
				?>
				<?php 
					foreach ($generos as $genero) {
						if (count($genero) > 2) {
							$oFuckingHoscopo = $genero[2][array_rand($genero[2])];
						} else {
							$oFuckingHoscopo = $hoscopos[array_rand($hoscopos)];
						}
					?>
				<div class="hoscopo" id="<?= substr($genero[1], 0, -4) ?>">
						<img src="/elementos/horoscopo/osHoscopos/<?= $genero[1] ?>">
						<h2><?= $genero[0] ?></h2>
						<p><?= $oFuckingHoscopo ?></p>
				</div>
					<?php
					}
				?>
				<div class="hoscopo" id="sushi">
						<img src="/elementos/horoscopo/osHoscopos/sushi.png">
						<h2>Sushi</h2>
						<p><?= $hoscopos[array_rand($hoscopos)]; ?></p>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; 

function renderar_merda($cidade, $AFuckingArrayFuck, $floatOnde) { 
	$oFuckingNumeroDaTemp = "";
	if (is_string($AFuckingArrayFuck[3][0])) {
		$oFuckingNumeroDaTemp = $AFuckingArrayFuck[3][1];
	} else {
		$oFuckingNumeroDaTemp = rand($AFuckingArrayFuck[3][0], $AFuckingArrayFuck[3][1]);
	}
?>
	<div class="climacio" style="float:<?= $floatOnde ?>;">
		<div class="fuckingClima">
			<img src="/elementos/clima/climas/<?= $AFuckingArrayFuck[1] ?>">
			<div class="oRestoDoClima">
				<span class="titulo"><?= $cidade ?></span>
				<span class="climaInfo">
					<span class="tempBig"><?= $oFuckingNumeroDaTemp ?>º</span>
					<div>
						<span class="tempDesc"><?= $AFuckingArrayFuck[0] ?></span>
						<span class="tempSmall"><?= $AFuckingArrayFuck[3][0] ?>º - <?= $AFuckingArrayFuck[3][1] ?>º</span>
					</div>
				</span>
			</div>
		</div>
		<span class="descClima"><?= $AFuckingArrayFuck[2] ?></span>
	</div>
<?php
}
?>