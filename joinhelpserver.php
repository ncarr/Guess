<?php
session_start();
?>
<!doctype html>
<html>
    <head>
        <title>Add a display</title>
        <link href='style.css' rel='stylesheet' type='text/css'>
        <link href='//fonts.googleapis.com/css?family=Montserrat:400' rel='stylesheet' type='text/css'>
        <link href='//fonts.googleapis.com/css?family=Roboto:400,300,100' rel='stylesheet' type='text/css'>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#ffffff">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
    <body>
        <h1>Add a display</h1>
        <p>The link to add a display is similar to the link to add a player, but it has "server.php" between the "/" and the "?". Your display link for this session is https://pencilcase.cf/game/server.php?g=<?php echo $_SESSION["gid"]; ?></p>
    </body>
</html>
