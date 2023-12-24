<?php include("db.php") ?>
<!DOCTYPE html>
<html lang="hu">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>

	<script>
		function togglePwd() {
			let x = document.getElementById("password");
			if (x.type === "password") {
				x.type = "text";
			} else {
				x.type = "password";
			}
		}
	</script>
</head>
<body>
	<form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
		<h1>Welcome to Fakebok!</h1>
		<label for="username">username:</label><br>
		<input type="text" name="username"><br>
		<label for="password">password:</label><br>
		<input type="password" name="password" id="password"> (show <input type="checkbox" onclick="togglePwd()">)<br>
		<label for="password2">password again:</label><br>
		<input type="password" name="password2"><br><br>
		<input type="submit" value="Register now">
	</form>
</body>
</html>
<?php
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
		$password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
		$password2 = filter_input(INPUT_POST, "password2", FILTER_SANITIZE_SPECIAL_CHARS);

		if (empty($username)) echo "Please enter a username!<br>";
		elseif (empty($password)) echo "Please enter a password!<br>";
		elseif (empty($password2)) echo "Please enter your password again!<br>";
		elseif ($password != $password2) echo "Your passwords do not match!<br>";
		elseif ($password == $username) echo "Your password and your username cannot match!";
		else {
			$hash = password_hash($password, PASSWORD_DEFAULT);
			$sql = "INSERT INTO users (username, password)
					VALUES ('$username', '$hash')";
			try {
				mysqli_query($db_conn, $sql);
				echo "You are now registered!";
			} catch (mysqli_sql_exception) {
				echo "Couldn't register you :c";
			}
			
		}
		$_POST = array();
	}

	mysqli_close($db_conn);
?>