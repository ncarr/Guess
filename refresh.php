<?php
session_start();
$group = json_decode(file_get_contents("groups/" . $_SESSION["gid"] . ".txt"), true);
if ($_POST["p"] == "index") {
    if ($group["started"]) {
        echo 1;
    }
} elseif ($_POST["p"] == "gameclient" && $_SESSION["last_checked"] < $group["last_public_update"]) {
    if ($group["current_word"]) {
        if ($group["current_speaker"] == $_SESSION["sid"]) {
            echo $group["current_word"];
        } else {
            echo 2;
        }
    } else {
        echo 1;
    }
}
} elseif ($_POST["p"] == "gameserver" && $_SESSION["last_checked"] < $group["last_public_update"]) {
    $new = array_slice($group["results"], $_SESSION["s_current"]);
    $_SESSION["s_current"] = count($group["results"] - 1);
    foreach ($new as &$i) {
        if ($i[0] == 0) {
            $i[0] = 1;
        }
            if ($i[0] == 1) {
                $i[0] = 2;
            }
    }
    echo json_encode($new);
}
$_SESSION["last_checked"] = time();
die();
?>