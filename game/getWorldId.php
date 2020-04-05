<?php
    session_start();
    include "../phpconnect.php";

  	class retObj {
    	var $status;
    	var $value;

    	function __construct($st, $v=null){
      		$this->status = $st;
      		$this->value = $v;
   		}
	}

	if(isset($_SESSION["id"])) {
        $id = $_SESSION["id"];
    }

	if (isset($a[$id])) {
    	echo json_encode(new retObj('Success', $id));
    } else {
		echo json_encode(new retObj('Failure'));
    }

?>
