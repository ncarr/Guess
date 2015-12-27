<?php
session_start();
if (!$_SESSION["sid"] || !$_SESSION["gid"]) {
    header("Location: cancel.php?redirect=true");
    exit();
} else {
    $group = json_decode(file_get_contents("groups/" . $_SESSION["gid"] . ".txt"), true);
    if ($_GET["correct"] || $_GET["pass"]) {
        $words = array("unstable", "fix", "need", "improper");
        $group["results"][count($group["results"]) - 1][1] = ($_GET["correct"]) ? 1 : 0;
        $group["current_speaker"] = array_rand($group["members"]);
        $group["current_word"] = $words[mt_rand(0, count($words) - 1)];
        // -1 means under embargo (currently being guessed), 0 means pass and 1 means correct
        $group["results"][] = array($group["current_word"], -1);
        $group["last_public_update"] = time();
        file_put_contents("groups/" . $_SESSION["gid"] . ".txt", json_encode($group));
        header("Location: gameclient.php");
        exit();
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
            timecount;
            function check() {
                $.post("refresh.php", { p: "gameclient" })
                .done(function (data) {
                    if (data) {
                        if (data == 1) {
                            $(".word").html("Please wait for next question");
                        } else if (data == 2) {
                            $(".word").html("Someone else is speaking. Go help them by guessing.");
                        } else if (data) {
                            $(".word").html(data);
                            $(".controls").slideDown();
                        }
                    }
                })
            }
            function countdown() {
                if (parseInt($(".countdown").html()) <= 0) {
                    clearInterval(timecount);
                    document.location = "roundsummary.php?reset=1";
                } else {
                    $(".countdown").html(parseInt($(".countdown").html()) - 1);
                }
            }
            $(document).ready(function () {
                refcheck = setInterval(check, 5000);
                timecount = setInterval(countdown, 1000);
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
        <h2 class="countdown"><?php echo 60 - time() + $group["started"]; ?></h2>
        <h1 class="word"><?php if ($group["current_word"]) { if ($group["current_speaker"] == $_SESSION["sid"]) { echo $group["current_word"]; } else { echo "Someone else is speaking. Go help them by guessing."; } } else { echo "Please wait for next question"; } ?></h1>
        <p class="debug"></p>
        <div class="controls" <?php if ($group["current_speaker"] != $_SESSION["sid"]) { echo 'style="display: none;"'; } ?>>
            <a href="?correct=1">Correct</a>
            <a href="?pass=1">Pass</a>
        </div>
        <a href="#" onclick="$('.links').slideDown();">Get link</a>
    </body>
</html>
