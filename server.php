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
        $group["displays"][$_SESSION["sid"]] = $_SESSION["sid"];
        file_put_contents("groups/" . $_SESSION["gid"] . ".txt", json_encode($group));
    }
} elseif (!$_SESSION["sid"] && !$_SESSION["gid"]) {
    $_SESSION["sid"] = substr("abcdefghijklmnopqrstuvwxyz", mt_rand(0, 25), 1) . substr(md5(microtime()), 25);
    $_SESSION["newgroup"] = true;
    $_SESSION["gid"] = substr("abcdefghijklmnopqrstuvwxyz", mt_rand(0, 25), 1) . substr(md5(microtime()), 25);
    file_put_contents("groups/" . $_SESSION["gid"] . ".txt", json_encode(array("results" => array(), "members" => array(), "displays" => array($_SESSION["sid"] => $_SESSION["sid"]))));
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
        <h2>Join link for players: https://pencilcase.cf/game/?g=<?php echo $_SESSION["gid"]; ?></h2>
        <a href="gameserver.php" class="start">Start Game</a>
        <a href="cancelserver.php" class="cancel">Cancel</a>
        <?php if ($_SESSION["newgroup"]) { ?><a href="joinhelpserver.php">We've created this group for you automatically. Looking to add another display?</a><?php } else { ?><p>You have successfully added a display to this group.</p><?php } ?>
    </body>
</html>
