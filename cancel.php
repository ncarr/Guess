<?php
session_start();
if ($_SESSION["gid"]) {
    $g = true;
    if ($_SESSION["sid"]) {
        $s = true;
        $group = json_decode(file_get_contents("groups/" . $_SESSION["gid"] . ".txt"), true);
        unset($group["members"][$_SESSION["sid"]]);
        file_put_contents("groups/" . $_SESSION["gid"] . ".txt", json_encode($group));
    }
}
session_destroy();
?>
<!doctype html>
<html>
    <head>
        <title>Cancel Group</title>
        <link href='style.css' rel='stylesheet' type='text/css'>
        <link href='//fonts.googleapis.com/css?family=Montserrat:400' rel='stylesheet' type='text/css'>
        <link href='//fonts.googleapis.com/css?family=Roboto:400,300,100' rel='stylesheet' type='text/css'>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#ffffff">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
    <body>
        <h1>Guess Word Game</h1>
        <?php if (!$g && !$s) { ?>
        <p>Nothing to cancel.</p>
        <?php } else { ?>
        <p>Cancelled.</p>
        <?php } ?>
    </body>
</html>
