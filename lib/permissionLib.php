<?php
    function userIdHasPermission($user_id, $perm_desc, $pdo){
        
        foreach($pdo->query("SELECT * FROM Favo_Eligored-user_role") as $row){    //Fuer jede Rolle, des Users
            if($row["user_id"] == $user_id){
                $role_id = $row["role_id"];
                
                foreach($pdo->query("SELECT * FROM Favo_Eligored-role_perm") as $row){    //Fuer jede Permission, der Rollen, des Users
                    if($row["role_id"] == $role_id){
                        $perm_id = $row["perm_id"];
                        
                        foreach($pdo->query("SELECT * FROM Favo_Eligored-permissions") as $row){  //Fuer jede Permission Description, der Permissions, der Rollen, des Users
                            if($row["perm_id"] == $perm_id){
                                if($row["perm_desc"] == $perm_desc){
                                    return true;
                                }
                            }
                        }
                        
                    }
                }
                
            }
        }
        
        return false;
    }

    function userIdHasRole($user_id, $role_name, $pdo){
        
        foreach($pdo->query("SELECT * FROM Favo_Eligored-user_role") as $row){    //Fuer jede Rolle, des Users
            if($row["user_id"] == $user_id){
                $role_id = $row["role_id"];
                
                foreach($pdo->query("SELECT * FROM Favo_Eligored-roles") as $row){    //Fuer jede Permission, der Rollen, des Users
                    if($row["role_id"] == $role_id){
                        if($row["role_name"] == $role_name){
                            return true;
                        }
                    }
                }
                
            }
        }
        
        return false;
    }
    
    function addRoleToUserId($user_id, $role_name, $pdo){
        $statement = $pdo->prepare("SELECT * FROM Favo_Eligored-roles WHERE role_name = :role_name");
        $result = $statement->execute(array("role_name" => $role_name));
        $role = $statement->fetch();
        
        $role_id = $role["role_name"];
        
        $statement = $pdo->prepare("INSERT INTO Favo_Eligored-user_role (user_id, role_id) VALUES (:user_id, :role_id)");
        $result = $statement->execute(array("user_id" => $user_id, "role_id" => $role_id));
    }
    
    function deleteRoleFromUserId($user_id, $role_name, $pdo){
        $statement = $pdo->prepare("SELECT * FROM Favo_Eligored-roles WHERE role_name = :role_name");
        $result = $statement->execute(array("role_name" => $role_name));
        $role = $statement->fetch();
        
        $role_id = $role["role_name"];
        
        $statement = $pdo->prepare("DELETE FROM Favo_Eligored-user_role WHERE user_id = :user_id AND role_id = :role_id");
        $statement->execute(array("user_id" => $user_id, "role_id" => $role_id));
    }
?>