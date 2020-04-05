<?php
    session_start();
    include "../phpconnect.php";

	$requestPayload = file_get_contents('php://input');
    $object = json_decode($requestPayload, true);

    var_dump($object);

	if ((isset($_SESSION["id"]) && ($_SESSION["id"]>0))) {
        $user_id = $_SESSION["id"];
        $world_id = $object["world_id"];

		$world_json = file_get_contents("worlds/"+$world_id+".json");
		$world_object = json_decode($world_json, true);



		class retObj {
	    	var $status;
	    	var $value;

	    	function __construct($st, $v=null){
	      		$this->status = $st;
	      		$this->value = $v;
	   		}
		}

		if (isset($world_object)) {
			$toSend = array(
		    	'status' => 'success',
		        'world_id' => $world_object
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

    }

?>
