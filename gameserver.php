<?php
session_start();
if (!$_SESSION["sid"] || !$_SESSION["gid"]) {
    header("Location: cancelserver.php?redirect=true");
    exit();
} else {
    $group = json_decode(file_get_contents("groups/" . $_SESSION["gid"] . ".txt"), true);
    if (!$group["started"]) {
        $group["started"] = time();
    }
    $words = array("unstable", "fix", "need", "improper");
    if (!$group["current_word"]) {
        $group["current_speaker"] = array_rand($group["members"]);
        $group["current_word"] = $words[mt_rand(0, count($words) - 1)];
        $_SESSION["s_current"] = 0;
        // -1 means under embargo (currently being guessed), 0 means pass and 1 means correct
        $group["results"][] = array($group["current_word"], -1);
        $group["result_times"][time()] = count($group["results"]) - 1;
        $group["last_public_update"] = time();
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
        <script>
            var refcheck,
            timecount;
            function check() {
                $.post("refresh.php", { p: "gameserver" })
                .done(function (data) {
                    if (data) {
                        if (data[0] == -2) {
                            $("td").last().html("Game ended before answer.");
                        } else if (data[0] == -1) {
                            $("td").last().html("<i>Currently being guessed</i>");
                        } else if (data[0] == 1) {
                            $("td").last().html("Passed");
                        } else if (data[0] == 2) {
                            $("td").last().html("Guessed");
                        }
                        for (i = 1; i < count(data); i++) {
                            if (data[2] != -1) {
                                wordornot = data[1];
                            } else {
                                wordornot = "";
                            }
                            $("table").append('<tr><td class="round-count">Round ' + toString(parseInt($(".round-count").last().html()) + 1) + '</td><td class="word-cell">' + wordornot + '</td><td class="result-cell"></td></tr>');
                            if (data[2] == -2) {
                                $("td").last().html("Game ended before answer.");
                            } else if (data[2] == -1) {
                                $("td").last().html("<i>Currently being guessed</i>");
                            } else if (data[2] == 1) {
                                $("td").last().html("Passed");
                            } else if (data[2] == 2) {
                                $("td").last().html("Guessed");
                            }
                        }
                    }
                })
            }
            function countdown() {
                if (parseInt($(".countdown").html()) <= 0) {
                    clearInterval(timecount);
                    // do something when time runs out
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
        <h2 class="countdown">60</h2>
        <table>
            <tr>
                <th>Round</th>
                <th>Word</th>
                <th>Result</th>
            </tr>
            <?php foreach ($group["results"] as $round => $result) { ?>
            <tr>
                <td class="round-count">Round <?php echo $round + 1 ?></td>
                <td class="word-cell"><?php if ($result[1] != -1) { echo $result[0]; } ?></td>
                <td class="result-cell"><?php switch ($result[1]) {
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
