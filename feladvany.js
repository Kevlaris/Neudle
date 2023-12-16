const green = "#32cd32";
const yellow = "#cda532";
const red = "#cd3232";
const gray = "#646464"

const maiKey = "s0BFWcPak50WsbZC4JkORamQ3nrc1QHL";
var tanarok;
var nevek;
let tippek = [];
let szabalyok;
let mai;

const gametable = document.getElementById('gametable');
const gametableHeader = document.getElementById('gametableHeader');
const nameInput = document.getElementById('name');
const errorText = document.getElementById('error');
const button = document.getElementById('submit');
const help1 = document.getElementById('help1');
const help2 = document.getElementById('help2');
const autocompleteList = document.getElementById('autocomplete-list');

gametableHeader.style.display = 'none';

let paddings = parseInt(window.getComputedStyle(button).getPropertyValue('margin-left')) + parseInt(window.getComputedStyle(nameInput).getPropertyValue('margin-right'));
nameInput.style.width = `calc(100% - ${button.offsetWidth}px - ${paddings}px)`;

function readTextFile(file, callback) {
	var rawFile = new XMLHttpRequest();
	rawFile.overrideMimeType("application/json");
	rawFile.open("GET", file, true);
	rawFile.onreadystatechange = function() {
		if (rawFile.readyState === 4 && rawFile.status == "200") {
			callback(rawFile.responseText);
		}
	}
	rawFile.send(null);
}
//load tanarok
readTextFile("tanarok.json", function(text){
	tanarok = JSON.parse(text);

	nevek = Object.keys(tanarok);
	let idx = nevek.indexOf("proto");
	if (idx > -1) nevek.splice(idx, 1);
	nevek.sort();
});
//load szabalyok
readTextFile("szabalyok.json", function(text){
	szabalyok = JSON.parse(text);
	//mai = szabalyok.mai;
	if (szabalyok.tegnapi) {
		const tegnapi = document.getElementById('tegnapi');
		tegnapi.innerHTML = `<i>A tegnapi megfejtés: <u>${szabalyok.tegnapi}</u></i>`;
		tegnapi.style.display = 'initial';
	}
});
readTextFile(maiKey + ".txt", function(text){
	mai = text;
	console.log(mai);
});

function loadNames(data, element) {
	element.innerHTML = "";
	if (!data) return null;
	let inner = "";
	data.forEach((item) => {
		inner += `
		<li onclick="selectName('${item}');">${item}</li>`;
	});
	element.innerHTML = inner;
	element.style.width = window.getComputedStyle(nameInput).width;
}

function selectName(name) {
	console.log("select " + name);
	nameInput.value = name;
	autocompleteList.innerHTML = "";
}

function filterNames(searchText) {
	if (!searchText || searchText.length === 0) return null;
	return nevek.filter((x) => x.toLowerCase().includes(searchText.toLowerCase())).filter((x) => !tippek.includes(x));
}

nameInput.addEventListener('input', function() {
	const filtered = filterNames(nameInput.value);
	loadNames(filtered, autocompleteList);
});

//amikor megoldottad
function solved() {
	nameInput.disabled = true;
	button.disabled = true;

	var p = document.createElement('p');
	p.innerHTML = `Gratulálunk, kitaláltad! A megfejtés <b>${mai}</b> volt, akit ${tippek.length} tipp után találtál ki.`;
	document.getElementsByTagName('main')[0].appendChild(p);
	p.scrollIntoView(true);
}

//amikor megnyomja a tipp gombot
function tipp() {
	let nev = nameInput.value;

	if (tippek.includes(nev)) {
		errorText.innerHTML = "Ezt már próbáltad!";
		errorText.style.display = "initial";
		return;
	} else if (!(nev in tanarok)) {
		errorText.innerHTML = "Ilyen tanár nincs a játékban!";
		errorText.style.display = "initial";
		return;
	} else {
		errorText.innerHTML = "";
		errorText.style.display = "none";
	}
	let tanar = tanarok[nev];

	gametableHeader.style.display = 'table-row';
	nameInput.value = "";

	let row = gametable.insertRow(4);

	var td = row.insertCell(-1);
	td.innerHTML = nev;
	td.style.backgroundColor = nev == mai ? green : red;

	td = row.insertCell(-1);
	td.innerHTML = tanar.nem;
	td.style.backgroundColor = kozosVonasok(nev, mai, "nem");

	td = row.insertCell(-1);
	td.innerHTML = tanar.hajszin;
	td.style.backgroundColor = kozosVonasok(nev, mai, "hajszin");

	td = row.insertCell(-1);
	td.innerHTML = tanar.jellem.join(', ');
	td.style.backgroundColor = kozosVonasok(nev, mai, "jellem");

	td = row.insertCell(-1);
	td.innerHTML = tanar.tantargy.join(', ');
	td.style.backgroundColor =kozosVonasok(nev, mai, "tantargy");

	tippek.push(nev);
	if (nev == mai) solved();
	else {
		if (tippek.length >= szabalyok.tippMegjelenites) {
			if (tippek.length < szabalyok.tipp1) {
				help1.parentElement.style.display = 'table-row';
				help1.innerHTML = "<b>1. segítség:</b> " + (szabalyok.tipp1 - tippek.length) + " kör múlva";
			}
			else if (tippek.length == szabalyok.tipp1) {
				help1.parentElement.style.display = 'table-row';
				help1.innerHTML = '<b>1. segítség:</b> "' + tanarok[mai].segitseg[0] + '"';
			}
			else if (tippek.length < szabalyok.tipp2) {
				help2.parentElement.style.display = 'table-row';
				help2.innerHTML = "<b>2. segítség:</b> " + (szabalyok.tipp2 - tippek.length) + " kör múlva";
			}
			else if (tippek.length == szabalyok.tipp2) {
				help2.parentElement.style.display = 'table-row';
				help2.innerHTML = '<b>2. segítség:</b> "' + tanarok[mai].segitseg[1] + '"';
			}
		}
	}
}

//-1 = piros, 0 = sárga, 1 = zöld
function kozosVonasok(nev1, nev2, tulajdonsag) {
	//console.log("called");
	t1 = tanarok[nev1][tulajdonsag];
	t2 = tanarok[nev2][tulajdonsag];
	let result = -1;
	if (typeof(t1) === 'object' && typeof(t2) === 'object') {
		let count = 0;
		t1.forEach(element => {
			if (t2.includes(element)) count++;
		});
		//console.log("count: " + count);
		if (count == 0) result = -1;
		else if (count == t1.length && count == t2.length) result = 1;
		else result = 0;
	} else {
		result = t1 == t2 ? 1 : -1
	}

	switch (result) {
		case 1: return green;
		case 0: return yellow;
		case -1: return red;
		default: return gray;
	}
}

button.addEventListener('click', tipp)