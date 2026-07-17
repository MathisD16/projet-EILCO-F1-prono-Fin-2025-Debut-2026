<?php

if(isset($_POST['formsend'])) {
    

    extract($_POST);

 
    if(!empty($pseudo) && !empty($email) && !empty($password) && !empty($cpassword)) {
 
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message_erreur = "Le format de l'adresse email n'est pas valide.";
        }
   
        elseif($password != $cpassword) {
            $message_erreur = "Les mots de passe ne correspondent pas.";
        }

        elseif(strlen($password) < 10) {
            $message_erreur = "Le mot de passe doit faire au moins 10 caractères.";
        }

        elseif(!preg_match('/[^a-zA-Z0-9]/', $password)) {
            $message_erreur = "Le mot de passe doit contenir au moins un caractère spécial (!, @, #, $, etc.).";
        }

        elseif(!preg_match('/[0-9]/', $password)) {
            $message_erreur = "Le mot de passe doit contenir au moins un chiffre.";
        }
 
        elseif(!preg_match('/[A-Z]/', $password)) {
            $message_erreur = "Le mot de passe doit contenir au moins une majuscule.";
        }
        else {
 
            
            $options = ['cost' => 12];
            $hashpassword = password_hash($password, PASSWORD_BCRYPT, $options);

            include_once 'database.php';
            global $db;


            $checkemail = $db->prepare("SELECT email FROM users WHERE email = :email");
            $checkemail->execute(['email' => $email]);
            
 
            $checkpseudo = $db->prepare("SELECT pseudo FROM users WHERE pseudo = :pseudo");
            $checkpseudo->execute(['pseudo' => $pseudo]);

            if($checkemail->rowCount() == 0 && $checkpseudo->rowCount() == 0){

                $q = $db->prepare("INSERT INTO users(pseudo,email,password) VALUES(:pseudo, :email, :password)");
                $q->execute([
                    'pseudo' => $pseudo,
                    'email' => $email,
                    'password' => $hashpassword,
                ]);
                
                $_SESSION['id'] = $db->lastInsertId();
                $_SESSION['pseudo'] = $pseudo;
                $_SESSION['email'] = $email;
                $_SESSION['role'] = 'user';
                header("Location: index.php");
                exit();
            
            } else {
                if ($checkemail->rowCount() != 0) {
                    $message_erreur = "Cet email est déjà utilisé par un autre compte.";
                } elseif ($checkpseudo->rowCount() != 0) {
                    $message_erreur = "Ce pseudo est déjà utilisé.";
                }
            }
        }
    } else {
        $message_erreur = "Veuillez remplir tous les champs.";
    }
}
?>