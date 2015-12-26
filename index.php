<?php
session_start();
if ($_GET["g"]) {
    if (!file_exists("groups/" . $_GET["g"] . ".txt")) {
        header("Location: joininvalid.html");
        exit();
    } else {
        $_SESSION["gid"] = $_GET["g"];
        $group = json_decode(file_get_contents("groups/" . $_SESSION["gid"] . ".txt"), true);
        $_SESSION["sid"] = substr("abcdefghijklmnopqrstuvwxyz", mt_rand(0, 25), 1) . substr(md5(microtime()), 25);
        $group["members"][$_SESSION["sid"]] = $_SESSION["sid"];
        file_put_contents("groups/" . $_SESSION["gid"] . ".txt", json_encode($group));
    }
}
?>
<!doctype html>
<html>
    <head>
        <title>Guess Word Game</title>
        <link href='style.css' rel='stylesheet' type='text/css'>
        <link href='//fonts.googleapis.com/css?family=Montserrat:400' rel='stylesheet' type='text/css'>
        <link href='//fonts.googleapis.com/css?family=Roboto:400,300,100' rel='stylesheet' type='text/css'>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#ffffff">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <body>
        <h1>Guess Word Game</h1>
        <h2>Welcome to the game</h2>
        <?php if ($_SESSION["gid"] && $_SESSION["sid"]) { ?>
        <p>You're all set! Come back to this page when the game starts and it will be ready.</p>
        <a href="cancel.php" class="cancel">Cancel</a>
        <a href="serverhelp.html">Looking to start your own game?</a>
        <?php } else { ?>
        <p>If you came here to join a group, then please go to the group link given to you or ask anyone playing or organizing this game to give it to you.</p>
        <p>If you came here to start a game or add another display, please set up one computer as a display which anyone can see, then go to <a href="server.php">https://pencilcase.cf/game/server.php</a> on that device. After that anyone can join from the link shown on-screen.</p>
        <?php } ?>
    </body>
</html>
