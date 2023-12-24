<!DOCTYPE html>
<html lang="hu" prefix="og: https://ogp.me/ns#">
<head>
	<title>Neudle - A Neumannos Rejtvényoldal</title>
	<link rel="icon" href="images/logo.png">
	<link rel="stylesheet" href="style.css">

	<meta property="og:title" content="Neudle - A Neumannos Rejtvényoldal" />
	<meta property="og:description" content="Úgy gondolod, ismered a neumannos tanáraidat? Tedd próbára tudásod a 11.Italy Neudle játékában!" />
	<meta property="og:type" content="website" />
	<meta property="og:image" content="http://www.neudle.11italy.hu/images/logo.png" />
	<meta property="og:url" content="http://www.neudle.11italy.hu/" />
	<meta property="og:locale" content="hu_HU" />
	<meta property="og:site_name" content="11.Italy" />

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Marcellus&display=swap">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Caveat">

	<style>
		.start {
			background-color: rgb(20, 214, 20);
			font-family: Verdana, Geneva, Tahoma, sans-serif;
			font-weight: bold;
			color: white;
			font-size: 26px;
			padding: 10px;
			padding-left: 20px;
			padding-right: 20px;
			border: 0px;
			box-shadow: 0px;
			border-radius: 10px;
			/* margin: auto;
			display: block; */
			margin-left: 25px;
			margin-right: 25px;
		}
			.start:hover {
				background-color: rgb(17, 182, 17);
			}
			.start:active {
				background-color: rgb(10, 100, 10);
			}
	</style>
</head>
	<body>
		<div id="window">
			<?php include("header.html") ?>
			<main>
				<p><strong>Bongiorno!</strong></p>
				<p>Úgy gondolod, ismered a tanáraidat? Szeretnéd próbára tenni a tudásod? Ez a játék a <a href="https://www.nytimes.com/games/wordle/" target="_blank">Wordle</a> és a <a href="https://loldle.net/" target="_blank">LoLdle</a> neumanni ötvözete, viszont ezúttal a játékkarakterek és mindenféle bugyuta szavak helyett a kedvenc tanáraitokat kell kitalálnotok! Sok sikert, jó szórakozást!</p>
				<p>Minden nap új feladványokkal készülünk nektek, ne maradjatok le! Megéri mindet kitalálni, mert a végén értékes nyeremény ütheti a markod!</p>
				<p>Ha tetszett a játék, akkor ne felejtsd el megmutatni haverjaidnak és osztálytársaidnak, de a legfontosabb, hogy <strong style="font-size: larger; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif">szavazz a 11.I-re!</strong></p>

				<div class="split">
					<button class="start growOnHover" onclick="document.location='./classic'">▶ KLASSZIKUS</button>
					<button class="start growOnHover" onclick="document.location='./quote'">▶ IDÉZETES</button>
				</div>
				
			</main>
			<?php include("footer.html") ?>
		</div>
	</body>
</html>