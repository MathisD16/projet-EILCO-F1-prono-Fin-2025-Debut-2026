<?php

    include_once 'database.php';
    global $db;

    if(isset($_POST['formlogin'])) {
        extract($_POST);

        if(!empty($lemail) && !empty($lpassword)){


            $q = $db->prepare("SELECT * FROM users WHERE email = :email");
            $q->execute(['email' => $lemail]);
            $result = $q->fetch();

            if($result == true){

                $hpassword = $result['password'];
                if(password_verify($lpassword, $hpassword)){

                    $_SESSION['id'] = $result['id'];
                    $_SESSION['pseudo'] = $result['pseudo'];
                    $_SESSION['email'] = $result['email'];
                    $_SESSION['role'] = $result['role'];
                    header("Location: index.php");
                    exit();

                } else {
                    $message_erreur = "Email ou mot de passe incorrect.";
                }
            } else {
                $message_erreur = "Email ou mot de passe incorrect.";
            }

        } else {
            $message_erreur = "Veuillez remplir tous les champs.";
        }
    }
?>