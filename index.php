<?php
    session_start();
    include "phpconnect.php";
    include "lib/permissionLib.php";
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
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Eligored</title>


        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="index.css">
        <link rel="stylesheet" href="lib/hideWebhostBanner.css">

		<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

        <script type="text/javascript" src="index.js"></script>
    </head>
    <body>
        <?php
            function redirect($url) {
                $string = "<script type='text/javascript'>";
                $string.= "    window.location = '".$url."';";
                $string.= "</script>";

                echo $string;
            }

            if(isset($_GET["register"])) {
                $error = false;
                $email = $_POST["email"];
                $password = $_POST["password"];
                $password2 = $_POST["password2"];
                $email_verification_hash = md5(rand(0,1000));

				try {
					$statement = $pdo->prepare("SELECT * FROM Favo_Eligored_users WHERE email = :email");
					$result = $statement->execute(array("email" => $email));
					$user = $statement->fetch();
				} catch (Exception $e) {
					$error_log = fopen("/log.txt", "w") or die("Unable to open file!");
			        $error = $e->getMessage();
			        $error = "phpconnect.php: ".$error;
			        fwrite($error_log, $error);
			        fclose($error_log);
				}

                if($user !== false) { //User mit dieser Email ist schon vorhanden
                    $error = true;
                }

                if(!$error) {
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);

					try {
						$statement = $pdo->prepare("INSERT INTO Favo_Eligored_users (email, email_verification_hash, password) VALUES (:email, :email_verification_hash, :password)");    //Set Email, Email_Verification_Hash, Password_Hash
						$result = $statement->execute(array("email" => $email, "email_verification_hash" => $email_verification_hash, "password" => $password_hash));
					} catch (Exception $e) {
						$error_log = fopen("/log.txt", "w") or die("Unable to open file!");
				        $error = $e->getMessage();
				        $error = "phpconnect.php: ".$error;
				        fwrite($error_log, $error);
				        fclose($error_log);
					}

					try {
						$statement = $pdo->prepare("SELECT * FROM Favo_Eligored_users WHERE email = :email");     //Generate Name
						$result = $statement->execute(array("email" => $email));
						$user = $statement->fetch();
						$new_name = "User".$user["id"];
					} catch (Exception $e) {
						$error_log = fopen("/log.txt", "w") or die("Unable to open file!");
				        $error = $e->getMessage();
				        $error = "phpconnect.php: ".$error;
				        fwrite($error_log, $error);
				        fclose($error_log);
					}

					try {
						$statement = $pdo->prepare("UPDATE Favo_Eligored_users SET name = :name WHERE email = :email");      //Set Name
						$result = $statement->execute(array("name" => $new_name, "email" => $email));
					} catch (Exception $e) {
						$error_log = fopen("/log.txt", "w") or die("Unable to open file!");
				        $error = $e->getMessage();
				        $error = "phpconnect.php: ".$error;
				        fwrite($error_log, $error);
				        fclose($error_log);
					}

					try {
						$statement = $pdo->prepare("INSERT INTO Favo_Eligored_user_role (user_id, role_id) VALUES (:user_id, :role_id)");     //Set Default Role for User
						$result = $statement->execute(array("user_id" => $user["id"], "role_id" => 1));
					} catch (Exception $e) {
						$error_log = fopen("/log.txt", "w") or die("Unable to open file!");
				        $error = $e->getMessage();
				        $error = "phpconnect.php: ".$error;
				        fwrite($error_log, $error);
				        fclose($error_log);
					}

                    //Email Start
                    /*$to      = $email;
                    $subject = "Signup | Verification";
                    $message = "

                    Schön, dass sie sich bei Eligored registriert haben!
                    Ihr Account wurde erstellt und muss jetzt nur noch verifiziert werden

                    ------------------------
                    Email: ".$email."
                    Password: ".$password."
                    ------------------------

                    Bitte drücken sie diesen Link, um ihren Account zu verifizieren:
                    https://eligored.tk/verify.php?email=".$email."&hash=".$email_verification_hash."

                    ";

                    $headers = "From:noreply@eligored.tk" . "\r\n";
                    mail($to, $subject, $message, $headers);*/
                    //Email End

                    redirect("index.php");
                }
            }

            if(isset($_GET["login"])) {
                $email = $_POST["email"];
                $password = $_POST["password"];

				try {
	                $statement = $pdo->prepare("SELECT * FROM Favo_Eligored_users WHERE email = :email");
	                $result = $statement->execute(array("email" => $email));
	                $user = $statement->fetch();
				} catch (Exception $e) {
					$error_log = fopen("/log.txt", "w") or die("Unable to open file!");
			        $error = $e->getMessage();
			        $error = "phpconnect.php: ".$error;
			        fwrite($error_log, $error);
			        fclose($error_log);
				}

                if ($user == true && password_verify($password, $user["password"])) {
                    $_SESSION["id"] = $user["id"];
                    $_SESSION["name"] = $user["name"];

                    redirect("index.php");
                }else if($user == true && !password_verify($password, $user["password"])) {
                    echo "Falsches Passwort";
                }else if($user == false) {
                    echo "Kein User unter dieser Email";
                }
            }

            if(isset($_GET["logout"])) {
                session_destroy();
                redirect("index.php");
            }


            $content = " ";

            $content.= "<div class='site d-flex w-100 h-100 p-3 mx-auto flex-column'>
                            <header role='banner' class='header mb-auto'>
                                <div class='inner'>
                                    <h3 class='brand'>Eligored</h3>
                                    <nav class='nav nav-masthead justify-content-end'>
                                    ";
                                        if(isset($_SESSION["name"])){
            $content.= "                    <ul class='nav'>
                                                <li class='active'><a class='nav-link' href='index.php'>Home</a></li>
                                                <li class='nav-item dropdown'>
                                                    <a class='nav-link dropdown-toggle' href='#' id='navbarDropdownMenuUser' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>".$name."</a>
                                                    <div class='dropdown-menu' aria-labelledby='navbarDropdownMenuUser'>
                                                        <a class='dropdown-item' href='user/user.php?settings=1'>Account Settings</a>
                                                    </div>
                                                </li>
                                            ";
                                            if(userIdHasPermission($id, "show_admin_nav", $pdo)){
            $content.= "                        <li class='nav-item dropdown'>
                                                    <a class='nav-link dropdown-toggle' href='#' id='navbarDropdownMenuAdmin' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Admin</a>
                                                    <div class='dropdown-menu' aria-labelledby='navbarDropdownMenuAdmin'>
                                                        <a class='dropdown-item' href='admin/admin.php?useradministration=1'>User Administration</a>
                                                        <a class='dropdown-item' href='#'>bla1</a>
                                                        <a class='dropdown-item' href='#'>bla2</a>
                                                    </div>
                                                </li>
                                                ";
                                            }
            $content.= "                        <li><a class='nav-link' href='index.php?logout=1'>Logout</a></li>
                                            ";
                                        }else{
            $content.= "                        <li><a class='nav-link' href='index.php?register_form=1'>Register</a></li>
                                                <li><a class='nav-link' href='index.php?login_form=1'>Login</a></li>
                                            ";
                                        }
            $content.= "                    </ul>
                                    </nav>
                                </div>
                            </header>

                            <main role='main' class='main'>
                                <div class='inner'>
                                    <h1 class='main-header'>Lorem ipsum</h1>
                                    <p class='main-text'>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam</p><br>
                                    <a href='game/game.php'>Start Game</a>
                                </div>
                            </main>

                            <footer role='contentinfo' class='footer mt-auto'>
                                <div class='inner'>
                                    <p>©2020 Eligored All Rights Reserved, by <a href='https://twitter.com/Favo_Gaming'>@Favo</a>  ||  <a href='https://github.com/FavoHD/Eligored_Codeclub'>GitHub Rep</a> </p>
                                </div>
                            </footer>
                        </div>
                        ";

            if(isset($_GET["register_form"])){
                ?>                  <!--Notlösung, da es sonst nicht klappt START-->
                <div id='transparentBackground'></div>
                <form class='form-signup' action='?register=1' method='post' oninput='password2.setCustomValidity(password2.value != password.value ? "Passwörter stimmen nicht überein" : "")'>
                    <h1 class='h3 mb-3 font-weight-normal'>Please register</h1>

                    <label for='email' class='sr-only'>Email address</label>
                    <input type='email' id='email' class='form-control' name="email" placeholder='Email address' required autofocus>

                    <label for='password1' class='sr-only'>Password</label>
                    <input type='password' id='password1' class='form-control' name='password' placeholder='Password' required>

                    <label for='password2' class='sr-only'>Confirm password</label>
                    <input type='password' id='password2' class='form-control' name='password2' placeholder='Password'>

                    <button class='btn btn-lg btn-primary btn-block' type='submit'>Sign up</button>
                    <a href='index.php'>Back</a>
                </form>
                <?php               //Notlösung, da es sonst nicht klappt ENDE
            }else if(isset($_GET["login_form"])){
                $content.= "<div id='transparentBackground'></div>
                            <form class='form-signin' action='?login=1' method='post'>
                                <h1 class='h3 mb-3 font-weight-normal'>Please login</h1>

                                <label for='email' class='sr-only'>Email address</label>
                                <input type='email' id='email' class='form-control' name='email' placeholder='Email address' required autofocus>

                                <label for='password' class='sr-only'>Password</label>
                                <input type='password' id='password' class='form-control' name='password' placeholder='Password' required>

                                <button class='btn btn-lg btn-primary btn-block' type='submit'>Sign in</button>
                                <a href='index.php'>Back</a>
                            </form>
                            ";
            }

            echo $content;
        ?>
    </body>
</html>
