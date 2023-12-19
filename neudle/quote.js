const green = "#32cd32";
const yellow = "#cda532";
const red = "#cd3232";
const gray = "#646464"

const maiKey = "s0BFWcPak50WsbZC4JkORamQ3nrc1QHL";

var gametable;
var nameInput;
var errorText;
var button;
var help1;
var help2;
var autocompleteList;

function selectName(name) {
	console.log("select " + name);
	nameInput.value = name;
	autocompleteList.innerHTML = "";
}

$( document ).ready(function() {
	
var tanarok;
var nevek;
let tippek = [];
let szabalyok;
let mai;

gametable = document.getElementById('gametable');
nameInput = document.getElementById('name');
errorText = document.getElementById('error');
button = document.getElementById('submit');
help1 = document.getElementById('help1');
help2 = document.getElementById('help2');
autocompleteList = document.getElementById('autocomplete-list');

let paddings = parseInt(window.getComputedStyle(button).getPropertyValue('margin-left')) + parseInt(window.getComputedStyle(nameInput).getPropertyValue('margin-right'));
nameInput.style.width = `calc(100% - ${button.offsetWidth}px - ${paddings}px)`;

function readJsonFile(file, callback) {
	$.get(file, function(data){
		callback(data);
	}, 'json');
}
//load tanarok
readJsonFile("tanarok.json", function(data){
	tanarok = data;

	nevek = Object.keys(tanarok);
	let idx = nevek.indexOf("proto");
	if (idx > -1) nevek.splice(idx, 1);
	nevek.sort();
});
//load szabalyok
readJsonFile("szabalyok.json", function(data){
	szabalyok = data;
	//mai = szabalyok.mai;
	if (szabalyok.tegnapi) {
		const tegnapi = document.getElementById('tegnapi');
		tegnapi.innerHTML = `<i>A tegnapi megfejtés: <u>${szabalyok.tegnapi}</u></i>`;
		tegnapi.style.display = 'initial';
	}
});

function readTextFile(file, callback) {
	$.get(file, function(text){
		callback(text);
	}, 'text');
}
readTextFile(maiKey + '.txt', function(text){
	mai = text.trim().split('\n')[1].trim();
	document.getElementById('quote').innerText = "„" + tanarok[mai].idezet + "”";
})

function playAudio(name) {
	var audio = new Audio('sounds/' + name);
	audio.play();
}

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
	playAudio('yippee.wav');
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
		playAudio('fart.mp3');
		return;
	} else if (!(nev in tanarok)) {
		errorText.innerHTML = "Ilyen tanár nincs a játékban!";
		errorText.style.display = "initial";
		playAudio('fart.mp3');
		return;
	} else {
		errorText.innerHTML = "";
		errorText.style.display = "none";
	}

	nameInput.value = "";

	let row = gametable.insertRow(4);

	var td = row.insertCell(-1);
	td.innerHTML = nev;
	td.className += "tanar";
	td.style.backgroundColor = nev == mai ? green : red;

	tippek.push(nev);
	if (nev == mai) solved();
	else {
		playAudio('fart.mp3');
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
	t1 = tanarok[nev1.trim()][tulajdonsag.trim()];
	t2 = tanarok[nev2.trim()][tulajdonsag.trim()];
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


});