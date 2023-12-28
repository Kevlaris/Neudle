<!DOCTYPE html>
<html lang="hu">
<head>
	<title>Neudle - Idézet</title>
	<link rel="icon" href="/images/logo.png">
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="quote.css">

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Marcellus&display=swap">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Caveat">

	<script src="/jquery-3.7.1.js"></script>
	<script src="quote.js"></script>
</head>
	<body>
		<div id="window">
			<?php include("header.html") ?>
			<main>
				<h1 style="text-align: center; margin-top: 8px;">Idézetes feladvány</h1>
				<p style="font-size: 18px;">A klasszikus játékhoz hasonlóan, az idézetes játékban is egy szeretett tanárra kell rájönnöd. Ezúttal viszont egyetlen idézet áll a rendelkezésre, ennek segítségével kell találgatnod. Nem egy könnyű feladat, de egy igazi neumannos számára ez sem okozhat fejtörést! A klasszikus játékmód segítségei itt is elérhetőek.</p>
				<p id="tegnapi" style="display: none;"></p>	
				<p style="font-size: 18px;"><b>Sok sikert, jó szórakozást!</b></p>

				<table id="gametable">
					<tr class="noborder">
						<td id="quote"></td>
					</tr>
					<tr class="noborder">
						<td colspan="6" style="position: relative; height: fit-content; z-index: 80;">
							<div style="margin-bottom: 0px; height: fit-content;">
								<form>
									<input type="text" name="name" id="name" required autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" placeholder="Kezdd el gépelni egy tanár nevét..." />
									<input type="button" id="submit" value="Tippelj!">
									<ul id="autocomplete-list">

									</ul>
								</form>
							</div>
							
							<p id="error"></p>
						</td>
					</tr>
					<tr class="noborder" style="display: none;">
						<td id="help1" style="height: 2em;"><b>1. segítség:</b> a</td>
					</tr>
					<tr class="noborder" style="display: none;">
						<td id="help2" style="height: 2em;"><b>1. segítség:</b> a</td>
					</tr>
				</table>
			</main>
			<?php include("footer.html") ?>
		</div>
	</body>
</html>