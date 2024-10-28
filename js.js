// shoutout pro Mausoleum e Gideon no stackoverflow
var charsArray = [
    'footer/chars/padudu.png',
    'footer/chars/queixao.png',
    'footer/chars/sushi.png'
];
var randomNumber = Math.floor(Math.random()*charsArray.length);

console.log("omg js!!!!! eu amo Js");

document.getElementById('character').setAttribute('src', charsArray[randomNumber]);