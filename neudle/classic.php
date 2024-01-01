<?php
	session_start();
	
	function get_json($filename) {
		$myfile = fopen($filename, "r") or die("Unable to open {$filename}!");
		$obj = json_decode(fread($myfile, filesize($filename)), true);
		fclose($myfile);
		return $obj;
	}
	$tanarok = get_json("tanarok.json");

	function kozos_vonas($nev1, $nev2, $tulajdonsag) {
		global $tanarok;
		$t1 = $tanarok[trim($nev1)][trim($tulajdonsag)];
		$t2 = $tanarok[trim($nev2)][trim($tulajdonsag)];
		$result = null;

		if (gettype($t1) == "array" && gettype($t2) == "array") {
			$count = 0;
			foreach ($t1 as $attr) {
				if (in_array($attr, $t2)) $count++;
			}
			if ($count == 0) $result = -1;
			elseif ($count == sizeof($t1) && $count == sizeof($t2)) $result = 1;
			else $result = 0;
		} else {
			if ($t1 == $t2) $result = 1;
			else $result = -1;
		}

		switch ($result) {
			case 1: return "#32cd32";
			case 0: return "#cda532";
			case -1: return "#cd3232";
			default: return "#646464";
		}
	}

	# handle guesses
	parse_str(file_get_contents("php://input"),$_POST);
	$guesses = null;
	if (isset($_SESSION["guesses"])) $guesses = $_SESSION["guesses"];
	else $guesses = array();
	$guess = $_POST["guess"];

	$code = "empty";
	if (!empty($guess)) {
		$continue = true;
		if ($guess == "reset") {
			$guesses = array();
			$continue = false;
			session_unset();
		}
		elseif (isset($_SESSION["previous"])) {
			# ignore if already tried
			if ($guess == $_SESSION["previous"]) $continue = false;
		}

		if ($continue) {
			if (!array_key_exists($guess, $tanarok)) $code = "notfound";
			elseif (in_array($guess, $guesses)) $code = "tried";
			else {
				array_push($guesses, $_POST["guess"]);
				$code = "ok";
			}
			$_SESSION["previous"] = $guess;
		}
		else $code = "ignore";
		unset($continue);
	}
	
	$_SESSION["guesses"] = $guesses;
	# let the js script know what the guesses are
	if (sizeof($guesses) > 0) echo "<script>const tippek = ['" . join("', '", $guesses) . "']</script>";
?>

<!DOCTYPE html>
<html lang="hu">
<head>
	<title>Neudle - Klasszikus</title>
	<link rel="icon" href="/images/logo.png">
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="classic.css">

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Marcellus&display=swap">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Caveat">

	<script src="/jquery-3.7.1.js"></script>
	<script src="classic.js"></script>
</head>
	<body>
		<div id="window">
			<?php include("header.html") ?>
			<main>
				<h1 style="text-align: center; margin-top: 8px;">Klasszikus feladvány</h1>
				<p style="font-size: 18px;">A klasszikus játék menete a következő: adott az iskola egy tanára, akire a tulajdonságai alapján kell rájönnöd, találgatásos alapon. A próbálkozásaidra színekkel válaszol a program, a jelmagyarázatot oldalt találod. Ezen kívül a program időnként segítségekkel lát el, amelyek általában idézetek, vagy az adott személyhez kapcsolható dolgok. A lenti szövegdobozba gépelve meg fognak jelenni a választható tanárok nevei. Ajánlott a listából választani, mert így kiesik annak az esélye, hogy esetleg elgépeled. Minden tanárra csak egyszer tippelhetsz.</p>
				<p id="tegnapi" style="display: none;"></p>
				<h2 id="toggleClick1" class="clickableText" style="margin-bottom: 0px;" onclick="toggleElement(1);">Tulajdonságok</h2>
				<ul id="toggleObject1" style="margin-top: 0px;">
					<li><b>Név:</b> a tanár becses neve</li>
					<li><b>Nem:</b> férfi / nő</li>
					<li><b>Hajszín:</b> a szeretett tanár hajának színe</li>
					<li><b>Szakmacsoport:</b> a tanár szakmacsoportjai, vesszőkkel tagolva</li>
					<li><b>Tantárgy:</b> a tanár által tanított tárgyak, vesszőkkel tagolva</li>
					<li><b>Osztályfőnök?:</b> igen / nem érték, "igen" esetén az évfolyamot is feltüntetjük</li>
				</ul>
				
				<p style="font-size: 18px;"><b>Sok sikert, jó szórakozást!</b></p>
				
				<table id="colorHelp">
					<tr class="noborder">
						<th colspan="3" style="padding-top: 0px; padding-bottom: 0px; height: 2em;"><h2>Jelmagyarázat:</h2></th>
					</tr>
					<tr>
						<td style="background-color: #32cd32;">teljesen megegyezik</td>
						<td style="background-color: #cda532;">részben megegyezik</td>
						<td style="background-color: #cd3232;">egyáltalán nem egyezik meg</td>
					</tr>
				</table>

				<table id="gametable">
					<tr class="noborder">
						<td colspan="6" style="position: relative; height: fit-content; z-index: 80;">
							<div style="margin-bottom: 0px; height: fit-content;">
								<form method="post" action="classic">
									<input type="text" name="guess" id="name" required autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" placeholder="Kezdd el gépelni egy tanár nevét..." />
									<input type="submit" id="submit" value="Tippelj!">
									<ul id="autocomplete-list">

									</ul>
								</form>
							</div>
							<?php
								switch ($code) {
									case "notfound":
										echo "<p id='error' style='display: block'>Ilyen tanár nincs a játékban! Kérlek, válassz a listából!</p>";
										break;
									case "tried":
										echo "<p id='error' style='display: block'>Őt már próbáltad!</p>";
										break;
									default: break;
								}
							?>
						</td>
					</tr>
					<tr class="noborder" style="display: none;">
						<td id="help1" colspan="6" style="height: 2em;"><b>1. segítség:</b> a</td>
					</tr>
					<tr class="noborder" style="display: none;">
						<td id="help2" colspan="6" style="height: 2em;"><b>1. segítség:</b> a</td>
					</tr>
					<tr id="gametableHeader" style="display: <?php if (sizeof($guesses) > 0) echo 'table-row'; else echo 'none' ?>;">
						<th style="width: 20%;">Név</th>
						<th style="width: 10%;">Nem</th>
						<th style="width: 15%;">Hajszín</th>
						<th style="width: 20%;">Szakmacsoport</th>
						<th style="width: 20%;">Tantárgy</th>
						<th style="width: 15%;">Osztályfőnök?</th>
					</tr>
					<?php
					$mai = "Kelemen Tünde";
						foreach (array_reverse($guesses) as $g) {
							$tanar = $tanarok[$g];
							echo "<tr>";

							$egyezik = $g == $mai;
							$color = null;
							if ($egyezik) $color = "#32cd32";
							else $color = "#cd3232";

							echo "<td style='background-color: {$color}'>" . $g . "</td>";
							echo "<td style='background-color: " . kozos_vonas($g, $mai, "nem") . "'>" . $tanar["nem"] . "</td>";
							echo "<td style='background-color: " . kozos_vonas($g, $mai, "hajszin") . "'>" . $tanar["hajszin"] . "</td>";
							echo "<td style='background-color: " . kozos_vonas($g, $mai, "szakmacsoport") . "'>" . join(", ", $tanar["szakmacsoport"]) . "</td>";
							echo "<td style='background-color: " . kozos_vonas($g, $mai, "tantargy") . "'>" . join(", ", $tanar["tantargy"]) . "</td>";
							echo "<td style='background-color: " . kozos_vonas($g, $mai, "of") . "'>" . join(", ", $tanar["of"]) . "</td>";

							echo "</tr>";

							if ($g == $guess) {
								if ($egyezik) echo "<script>playAudio('yippee.wav')</script>";
								else echo "<script>playAudio('fart.mp3')</script>";
							}
						}
					?>
				</table>
				
			</main>
			<?php include("footer.html") ?>
		</div>
	</body>
</html>