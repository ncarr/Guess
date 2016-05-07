<?php
session_start();
$group = json_decode(file_get_contents("groups/" . $_SESSION["gid"] . ".txt"), true);
if ($_GET["wipe"]) {
    unlink("groups/" . $_SESSION["gid"] . ".txt");
    session_destroy();
} elseif ($_GET["reset"]) {
    file_put_contents("groups/" . $_SESSION["gid"] . ".txt", json_encode(array("results" => array(), "members" => $group["members"], "displays" => $group["displays"])));
    header("Location: gameserver.php");
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
        <?php if ($_GET["wipe"]) { ?>
        <p>Successfully ended game.</p>
        <?php } else { ?>
        <h2>Join link for players: https://pencilcase.cf/game/?g=<?php echo $_SESSION["gid"]; ?></h2>
        <a href="resetserver.php?reset=1" class="start">Start New Round</a>
        <a href="resetserver.php?wipe=1" class="cancel">End Game</a>
        <?php if ($_SESSION["newgroup"]) { ?><a href="joinhelpserver.php">We've created this group for you automatically. Looking to add another display?</a><?php } else { ?><p>You have successfully added a display to this group.</p><?php } } ?>
    </body>
</html>
