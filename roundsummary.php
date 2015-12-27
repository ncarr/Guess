<?php
session_start();
if (!$_SESSION["sid"] || !$_SESSION["gid"]) {
    header("Location: cancelserver.php?redirect=true");
    exit();
} else {
    $group = json_decode(file_get_contents("groups/" . $_SESSION["gid"] . ".txt"), true);
    if ($group["started"]) {
        if ($_GET["reset"]) {
            $group["started"] = false;
            $group["results"][count($group["results"]) - 1][1] = -2;
            $group["current_word"] = "";
            $group["current_speaker"] = "";
            $group["last_public_update"] = time();
            file_put_contents("groups/" . $_SESSION["gid"] . ".txt", json_encode($group));
            header("Location: roundsummary.php");
        } else {
            header("Location: gameclient.php");
        }
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
        <script>
            var refcheck,
            found;
            function check() {
                $.post("refresh.php", { p: "index" })
                .done(function (data) {
                    if (data == "1") {
                        window.location = "gameclient.php";
                        clearInterval(refcheck);
                    }
                })
            }
            $(document).ready(function () {
                refcheck = setInterval(check, 5000);
            });
            $([window, document]).blur(function () {
                clearInterval(refcheck);
            }).focus(function () {
                check();
                refcheck = setInterval(check, 5000);
            });
        </script>
    <body>
        <h1>Guess Word Game</h1>
        <h2>Time's up! Here's how you did:</h2>
        <table>
            <tr>
                <th>Round</th>
                <th>Word</th>
                <th>Result</th>
            </tr>
            <?php foreach ($group["results"] as $round => $result) { ?>
            <tr>
                <td>Round <?php echo $round + 1 ?></td>
                <td><?php if ($result[1] != -1) { echo $result[0]; } ?></td>
                <td><?php switch ($result[1]) {
                    case -2:
                        echo "Game ended before answer";
                        break;
                    case -1:
                        echo "<i>Currently being guessed</i>";
                        break;
                    case 0:
                        echo "Passed";
                        break;
                    case 1:
                        echo "Guessed";
                        break;
                } ?></td>
            </tr>
            <?php } ?>
        </table>
    </body>
</html>
