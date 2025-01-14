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
