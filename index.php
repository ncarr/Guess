<?php
session_start();
if ($_GET["s"]) {
    if (!file_exists($_POST["s"] . ".txt")) {
        header("Location: joininvalid.html");
        exit();
    } else {
        $_SESSION["sid"] = $_GET["s"];
        file_put_contents($_SESSION["sid"] . ".txt", json_encode(array("results" => array())));
    }
} elseif (!$_SESSION["sid"]) {
    $_SESSION["sid"] = substr("abcdefghijklmnopqrstuvwxyz", mt_rand(0, 25), 1) . substr(md5(microtime()), 25);
    $_SESSION["newgroup"] = true;
    file_put_contents($_SESSION["sid"] . ".txt", json_encode(array("results" => array())));
}
?>
<!doctype html>
<html>
    <head>
        <title>Guess Word Game</title>
        <link href='style.css' rel='stylesheet' type='text/css'>
        <link href='//fonts.googleapis.com/css?family=Montserrat:400' rel='stylesheet' type='text/css'>
        <link href='//fonts.googleapis.com/css?family=Roboto:400,300,100' rel='stylesheet' type='text/css'><meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#ffffff">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <body>
        <h1>Guess Word Game</h1>
        <h2>Choose your device</h2>
        <a href="server.php" class="server">Screen</a>
        <a href="client.php" class="client">Player</a>
        <?php if ($_SESSION["newgroup"]) { ?><a href="joinhelp.html">Looking to join an existing group?</a><?php } else { ?><p>We have automatically created a group for you.</p><?php } ?>
    </body>
</html>
