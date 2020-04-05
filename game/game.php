<?php
    session_start();
    include "../phpconnect.php";
    include "../lib/permissionLib.php";
?>

<?php
    if(isset($_SESSION["name"])) {
        $name = $_SESSION["name"];
    }else{
        $name = "Guest";
    }

    if(isset($_SESSION["id"])) {
        $id = $_SESSION["id"];
    }else{
        $id = 0;
    }
?>

<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=0">
        <title>Eligored</title>

        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		<link rel="stylesheet" href="styles/game.css">
		<link rel="stylesheet" href="../lib/hideWebhostBanner.css">


        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

        <script type="text/javascript" src="../lib/collisionLib.min.js"></script>
        <script type="text/javascript" src="../lib/keyEventsLib.min.js"></script>
        <script type="text/javascript" src="../lib/mouseEventsLib.min.js"></script>
    </head>
    <body>
        <?php
            function redirect($url) {
                $string = "<script type='text/javascript'>";
                $string.= "    window.location = '".$url."';";
                $string.= "</script>";

                echo $string;
            }


            $content = " ";

            $content.= "<div id='site' class='site'>
                            <b>Name: ".$name."</b><br>
                            <b>Id: ".$id."</b><br>
                            <canvas id='canvas' width='1600' height='900' class='center' overflow='hidden' oncontextmenu='return false;'>
                                Wird nicht von ihrem Browser unterstützt
                            </canvas>
                        </div>
						<script type='text/javascript' src='game.js'></script>
                        ";

            echo $content;
        ?>
    </body>
</html>


<!--https://html-online.com/articles/javascript-variable-php-mysql-ajax-post-json/-->
