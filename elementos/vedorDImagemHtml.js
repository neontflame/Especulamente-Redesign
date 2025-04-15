var projid = 0;
var imagens = ["img1.png", "img2.png", "img3.png"];

function fazONegocioDasImagens() {
	for (var imgcoiso = 0; imgcoiso < imagens.length; imgcoiso++) {
		var img = document.createElement("img");
		img.src = "/static/projetos/" + projid + "/" + imagens[imgcoiso];
		img.className = "imagemCoiso";
		img.id = imgcoiso;
		img.onclick = function(){		
			var quiamsas = document.getElementsByClassName('outrasImagens')[0].children;
			
			for (var kid = 0; kid < quiamsas.length; kid++) {
				quiamsas[kid].className = "imagemCoiso";
			}
			clicCoiso(this.id)
			this.className = "imagemCoiso desopaco";
		};
		document.getElementsByClassName("outrasImagens")[0].appendChild(img);
	}
}

function clicCoiso(id) {
	console.log('clic');
	document.getElementById("imagemAtual").src = "/static/projetos/" + projid + "/" + imagens[id];
}

document.getElementById("imagemAtual").src = imagens[0];

function carregarImagens(id) {
  var xhttp = new XMLHttpRequest();
  xhttp.onload = function () {
	projid = id;
    imagens = this.responseText.split("\n");
	fazONegocioDasImagens();
  };
  xhttp.open(
    "GET",
    "/projetos/vedorDImagem.php?id=" +
      id + "&modo=internal",
    true
  );
  xhttp.send();
}