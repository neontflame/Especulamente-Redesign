// SEM shoutouts pra você

console.log("omg js!!!!! eu amo Js");

// Abrir e fechar o menu do header
var headerMenuAberto = false;

headerSeta.addEventListener("click", function (e) {
  e.stopPropagation();
  headerMenuAberto = !headerMenuAberto;
  headerSeta.classList.toggle("ativo", headerMenuAberto);
  headerMenu.classList.toggle("ativo", headerMenuAberto);
});

headerMenu.addEventListener("click", function (e) {
  e.stopPropagation();
});

// Isso faz o menu fechar se clicar fora dele
window.addEventListener("click", function () {
  if (headerMenuAberto) {
    headerMenuAberto = false;
    headerSeta.classList.remove("ativo");
    headerMenu.classList.remove("ativo");
  }
});

function postarComentario(tipo, id, ocomentario, thread) {
	var osNegocios = new FormData();
	
	osNegocios.append('tipo', tipo);
	osNegocios.append('id', id);
	osNegocios.append('comentario', ocomentario);
	osNegocios.append('fio', thread);
	
	const xhttp = new XMLHttpRequest();
	xhttp.open("POST", "/elementos/vedor_d_comentario/postarComentario.php", true);

	xhttp.onload = function() {
		carregarComentarios(tipo, id);
		document.getElementById("osComentario").value = '';
	}
	
	xhttp.send(osNegocios);
}

function carregarComentarios(tipo, id) {
	const xhttp = new XMLHttpRequest();
	xhttp.onload = function() {
		document.getElementById("osComentario").innerHTML = this.responseText;
	}
	xhttp.open("GET", "/elementos/vedor_d_comentario/obterComentarios.php?tipo=" + tipo + "&id=" + id, true);
	xhttp.send();
}

console.log(
  `%c
             ,´\`.
           ,'    '.
         ,'        '.
       ,'            '.     ei
     ,'                '.
   ,'                    '.
 ,'                        '.
<             :]             >
 '.                        ,'
   '.                    ,'
     '.                ,'
       '.            ,'      oqq tu pensa
         '.        ,'     q tá procurando?
           '.    ,'
             '.,'
`,
  "color: #375bfd"
);
