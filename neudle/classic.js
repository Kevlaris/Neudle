var nameInput;
var button;
var autocompleteList;

function toggleElement(id) {
	const element = document.getElementById('toggleObject' + id);
	const click = document.getElementById('toggleClick' + id);
	let txt;
	if (click) {
		txt = click.innerText.trim();
		if (txt[1] === ' ') txt = txt.substring(2);
	}
	if (element.style.display === 'none') {
		element.style.display = '';
		if (click) click.innerText = txt;
	}
	else {
		element.style.display = 'none';
		if (click) click.innerText = '▶ ' + txt;
	}
}
function playAudio(name) {
	var audio = new Audio('sounds/' + name);
	audio.play();
}

var tanarok;
var nevek;

function selectName(name) {
	console.log("select " + name);
	nameInput.value = name;
	autocompleteList.innerHTML = "";
}

$(document).ready(function(){

nameInput = document.getElementById('name');
button = document.getElementById('submit');
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

function loadNames(data, element) {
	element.innerHTML = "";
	if (!data) return null;
	let inner = "";
	data.forEach((item) => {
		inner += `
		<li tabindex="0" onkeydown="if (event.key === 'Enter') selectName(this.textContent);" onclick="selectName('${item}');">${item}</li>`;
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

// tab between list elements
const listItems = document.querySelectorAll('.autocomplete-list li');
listItems.forEach((item, index, array) => {
	item.addEventListener('keydown', (event) => {
		if (event.key === 'Tab') {
			event.preventDefault();
			array[(index + 1) % array.length].focus();
		}
	});
});

})