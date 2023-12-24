<?php
    $db_server = "localhost";
    $db_user = "funoro";
    $db_pwd = "\$h0gun07kakiVille";
    $db_name = "testdb";
    $conn = "";

    try {
        $db_conn = mysqli_connect($db_server, $db_user, $db_pwd, $db_name);
    } catch (mysqli_sql_exception) {
        echo "Couldn't connect to DB!";
    }
?>