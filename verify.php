<?php
    session_start();
    include "phpconnect.php";
?>

<?php
    function redirect($url) {
        $string = "<script type='text/javascript'>";
        $string.= "    window.location = '".$url."';";
        $string.= "</script>";
            
        echo $string;
    }
    
    
    if(isset($_GET["email"]) && !empty($_GET["email"]) AND isset($_GET["hash"]) && !empty($_GET["hash"])){
        $email = $_GET["email"];
        $hash = $_GET["hash"];
        
        $statement = $pdo->prepare("SELECT * FROM Favo_Eligored-users WHERE email = :email");
        $result = $statement->execute(array("email" => $email));
        $user = $statement->fetch();
        
        if($user){
            $statement = $pdo->prepare("UPDATE Favo_Eligored-users SET email_verification_status = :email_verification_status WHERE email = :email");  
            $result = $statement->execute(array("email_verification_status" => 1, "email" => $email));
            
            $statement = $pdo->prepare("UPDATE Favo_Eligored-user_role SET role_id = :role_id WHERE user_id = :user_id");  
            $result = $statement->execute(array("role_id" => 2, "user_id" => $user["id"]));
            
            redirect("index.php");
        }
    }else{
        echo "Ungültige Werte";
    }
?>