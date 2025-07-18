// SEM shoutouts pra você

console.log("omg js!!!!! eu amo Js");

// Abrir e fechar o menu do header
var headerMenuAberto = false;

window.headerSeta &&
  headerSeta.addEventListener("click", function (e) {
    e.stopPropagation();
    headerMenuAberto = !headerMenuAberto;
    headerSeta.classList.toggle("ativo", headerMenuAberto);
    headerMenu.classList.toggle("ativo", headerMenuAberto);
  });

window.headerMenu &&
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

// comentario negocios
function postarComentario(tipo, id, ocomentario, thread, that, respondido) {
  var osNegocios = new FormData();

  var bototes = that.parentElement.getElementsByTagName("button");

  for (var i = 0; i < bototes.length; i++) {
    bototes[i].disabled = true;
  }

  osNegocios.append("tipo", tipo);
  osNegocios.append("id", id);
  osNegocios.append("comentario", ocomentario);
  osNegocios.append("fio", thread);
  osNegocios.append("respondido", respondido);

  var xhttp = new XMLHttpRequest();
  xhttp.open(
    "POST",
    "/elementos/vedor_d_comentario/postarComentario.php",
    true
  );

  xhttp.onload = function () {
    if (xhttp.responseText == 'yeah') {
	  moeda(5);
    }
  
    carregarComentarios(tipo, id);
    that.parentElement.getElementsByTagName("textarea")[0].value = "";

    for (var i = 0; i < bototes.length; i++) {
      bototes[i].disabled = false;
    }
  };

  xhttp.send(osNegocios);
}

function carregarComentarios(tipo, id) {
  var xhttp = new XMLHttpRequest();
  xhttp.onload = function () {
    document.getElementById("osComentario").innerHTML = this.responseText;
  };
  xhttp.open(
    "GET",
    "/elementos/vedor_d_comentario/obterComentarios.php?tipo=" +
      tipo +
      "&id=" +
      id,
    true
  );
  xhttp.send();
}

function deletarComentario(tipo, id_projeto, id_comentario, that) {
  if (confirm("Tem certeza que quer deletar esse comentário??")) {
    var bototes = that.parentElement.getElementsByTagName("button");
    for (var i = 0; i < bototes.length; i++) {
      bototes[i].disabled = true;
    }

    var osNegocios = new FormData();
    osNegocios.append("id", id_comentario);

    var xhttp = new XMLHttpRequest();
    xhttp.open(
      "POST",
      "/elementos/vedor_d_comentario/deletarComentario.php",
      true
    );

    xhttp.onload = function () {
      carregarComentarios(tipo, id_projeto);
    };

    xhttp.send(osNegocios);
  }
}

function desesconderResposting(id) {
  document.getElementById("respondedor_" + id).style.display = "block";
  var textarea = document
    .getElementById("respondedor_" + id)
    .getElementsByTagName("textarea")[0];

  var length = textarea.value.length;
  textarea.focus();
  textarea.setSelectionRange(length, length);
}

var chromeVeio = ["8", "9", "10", "11", "12", "13", "14", "15", "16"];

function chromeCheck(ver, index, element) {
	if (window.navigator.userAgent.indexOf('Chrome/' + ver + '.') != -1) {
		document.getElementById("headerSeta").style.margin = '-16px 0px 0px 0px'
	}
}

chromeVeio.forEach(chromeCheck);

/*
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
 // suporte pra Browser Véio falou mais forte ...


%%##*+-:::...........=%@@@@@@@@@@@@@@@@@@@%##..-%++*+==:............::::::=+*###
%%#*+=-::.........:-+#*#@@@@@@@@@@@@@@@@@@@@%@=:#@@@@@%+-:..........::::::-=+*##
%##*+=:.........:+##@@@@@@@@@@@@@@@@@@@@@@@@@@%+%@@@@@@@#+::.......:::::::-=+*##
%##*+-:......:=:+%@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@%@@@@@@@@@#+-......:::::::-=+*##
%##*+-:....:==#@%@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@%*:.....:::::::-==+*#
%##*+-::..:=#%#@@@@@@@@@%@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*.....:::::::-==+*#
##*+==-:..:*@@@@@@@@@@@@@@%@@@@@%###%@@@@@@@@@@@@@@@@@@@@@@@@%=....:::::::--=+**
##*+=-::...=#@@@@%%@@%#%%@@@:...........=@@@@@@@@@@%%%%@@@@@@%*:...:::::::-==+**
##*+=-:....:+%@@@@%#*#@@%#................=@@@@@@@@@@@%*:+@@@@%:.......:::-==+**
##*+=-:....::+##@@@@%*##*.................:-%@@@@@@@@*+--*@@@@%=........::-==+**
#**+==-...:=-:=**+##@@@%+..................:@#@@%###**+-:-@@@@%+=-===::..:-==++*
##*++=--..:=**+::=+++**%+..............:::--#%=++++***=-::+@@@%+##*====::--==++*
##*++=--:..-+*+......:=@+-:::-----#@=+++++++#@===++*#*+-:.:@@@%*#*==+++-:-===++*
##*++=-:....-+=....::::@%+*****+=:+#*===+***%@--=+%@@@@#-..+@@@*#+-=+++=:===+++*
#***+=-.....:-+....::::+@####*+++*####*****#@*:-#@@@@@@@#-.-@@@@+=+=+*+--===++**
##**+=-...::.:=:...:---:#@%##***###########%*:-@@@@@@@@@%%::#@@@+*#***+--==+++**
##**++=-.::::::+...=@@@@==#@%############*=::-@@@@@@@::::-::+@@@#=*#*+=-===+++**
##***+==:.:::.:*..:#@@@@@@=:.+##%%##%#*:.:::-@@@@@@#=::::--::%@@@*+#*+-===++++**
##***++==:.::::=-.*#@@@@@@@@:::.......::::::%%%%@@*.:-:::-=-::#@@@***=====++++**
###***++==..::.+*...:.:+####%%:.:::::::::.:%#%%@@%-..=-::-==-...@@#==-====+++***
###***+++==..:::*:..:::-...+****:.::.::::-@@#::::::..==:=+**=:...-%--====++++***
####***++===-..:*+...::-....:*@%%@%:::::=@@@+-.....:=#**%%%@*=:....-====+++++***
%###***+++===-.:+=...::::.........::::-=*%%@@%*+++*%@@@@%%@@@=-:...-====++++****
%###****+++===-.=....:::+:.......:+-=+##%%%@@@@@@@@@@@%%%@@@@%=-:..-====++++****
%####****+++===:.....:+##%#=::::+##=+*%%%%@@@@@@@@@@%%##@@@@@@*-::.---===+++****
%####****++++==-...-@*+*##%%%%%%%#-::-=++**##%@@@@@@%%@@@@@@@@*-::..::-===++****
%####****++++==-:..*@@@%***####*:.::+*#%%%%#**+*@@@@@@@@@@@@@#::::....:--==+++**
%####*****+++===-..#@@@@@@##**:..:=*#%%%%%%%##+=-%@@@@@@@@@%:::::.......:-==++**
%%####***++++====:.=@@@@@@@@@:..:=*********###*+-:@@@@@@@@@-::::.........::-==++
%#####***++++===--..-##@@@@@@..:-==========+++++=-*@@@@%@@:::::+............::-=
%#####***+++==--::....:%@@@@*..:::::::::::::---=--*@@@+:::::::-%:..............:
%####***++==-::........:##***.::::::::::::::::::::#%@:.:::::::#@--::............
%###**++=-::.........==:.::+*-.::::.......:::::::*#*:::::::--+%@=*::............
###*++=-............-**=#:.:::=.............:::::::::::::---=*@@+*-::...........
#*++-:.............-#*==@#-::..:............:::::::::::--::-+*@@#+=:::..........
+=:...............-##==+@%=::::..:::.....::::::::::::--::::-+#@%%+=--::.........
.................:%#+--*@@+:....:::::::::....:::::::::::::-=*%@@#+==---:::....:.
.................%%*=--*@@#-:.....:::::.......:::::::..:::-+@@@@#++====---:::.::
................#%#=---#@@@+:........+::.....::::::....::-#@@@@@*++=+++++++==-=-
......:........%@%+----*@@@@=......:%%%%%%%%%%...::....:-@@@@@@@*+++++******++*+
....:::.......=%%*=----+@@@@#:....+@%%%%%%%%%%%*......:#@@@*@@@%+===+***********
..:::--:=-.-::@@#+-:--==@@@%@*:..+##%%%%%%%%%%@@#....=%@@%=*@@@*==--==++********
.:::...-=-=*.@@@#=:::--=#@@%%#+-===+*%%%%%%%%@*+++::+%%@#==%@@%+=--------=+#%#*+

meu... ascii art lindo.
*/
