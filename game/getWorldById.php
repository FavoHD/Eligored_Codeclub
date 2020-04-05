<?php
    session_start();
    include "../phpconnect.php";

	/*class retObj {
		var $status;
		var $value;

		function __construct($st, $v=null){
			$this->status = $st;
			$this->value = $v;
		}
	}*/

	$requestPayload = file_get_contents('php://input');
    $object = json_decode($requestPayload, true);
	var_dump($object);
	/*
	if ((isset($_SESSION["id"]) && ($_SESSION["id"]>0))) {
        $user_id = $_SESSION["id"];
        $world_id = $object["world_id"];

		$world_file = file_get_contents("worlds/".$world_id.".json");


		if (isset($world_file)) {
			$toSend = array(
		    	'status' => 'success',
		        'world' => file_get_contents("worlds/".$world_id.".json")
			);

	    	echo json_encode($toSend);
	    } else {
			$toSend = array(
		    	'status' => 'fail'
			);

			echo json_encode($toSend);
	    }
    } else {
		$toSend = array(
			'status' => 'fail'
		);

		echo json_encode($toSend);
    }*/

?>
