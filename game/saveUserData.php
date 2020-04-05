<?php
    session_start();
    include "../phpconnect.php";

    $requestPayload = file_get_contents('php://input');
    $object = json_decode($requestPayload, true);

    var_dump($object);

    if(isset($_SESSION["id"])) {
        $user_id = $_SESSION["id"];
        $xPos = $object["xPos"];
        $yPos = $object["yPos"];
        echo $user_id." ".$xPos." ".$yPos." ";

        $statement = $pdo->prepare("UPDATE Favo_Eligored_test_user_pos SET xPos = :xPos, yPos = :yPos WHERE user_id = :user_id");
        $result = $statement->execute(array("xPos" => $xPos, "yPos" => $yPos, "user_id" => $user_id));
    }
?>
