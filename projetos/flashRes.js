var myTimeout = null;
var attempts = 10;

function resFlash(mode) {
	var flashio = null;
	var didItWork = false;
	if (mode == 'jg') {
		flashio = document.getElementsByClassName('jogo')[0].children[0];
	}
	
	if (mode == 'md') {
		flashio = document.getElementById("flashAtual");
	}
	
	clearTimeout(myTimeout);
	attempts++;

	setTimeout(function() {
		try {
			flashio.width = 620;
			flashio.height = parseInt(flashio.TGetProperty('/', 9) * (620 / flashio.TGetProperty('/', 8)));
			console.log('browser véio fix !');
			attempts = 0;
			didItWork = true;
			return;
		} catch (e) {
			try {
				var ruffleShit = flashio.ruffle().metadata;
				flashio.width = 620;
				flashio.height = Math.round(ruffleShit.height * (620 / ruffleShit.width));
				console.log('browser NOVO fix !');
				attempts = 0;
				didItWork = true;
				return;
			} catch(e) {
			}
		}
		
		if (!didItWork) {
			myTimeout = setTimeout(resFlash, 500, mode);
		}
	}, 500);
	
}

function refreshResFlashAtts() {
	attempts = 0;
}