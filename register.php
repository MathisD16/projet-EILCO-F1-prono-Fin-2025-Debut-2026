<?php
// ----- CONFIG -----
session_start();
include 'includes/signin.php';
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription - F1 Prono</title>
    <link href='https://cdn.boxicons.com/3.0.6/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/style_register.css">
</head>
<body>

    <header>
        <div class="nav container">
            <a href="index.php" class="logo">F1 Prono<span>.</span></a>

            <div class="navbar">
                <a href="index.php" class="nav-link">Accueil</a>
                <a href="classement.php" class="nav-link">Classement</a>
                <a href="decouverte.php" class="nav-link">Découvrir la F1</a>
                <a href="pronostics.php" class="nav-link">Prono</a>
            </div>
            
            <div class="nav-icons">
                <?php if(isset($_SESSION['id'])): ?>
                    <a href="profil.php" title="Mon Profil">
                        <i class='bx bx-user'></i>
                    </a>
                    <a href="includes/logout.php" title="Se déconnecter">
                        <i class='bxr bx-arrow-out-right-square-half'></i>
                    </a>
                <?php else: ?>
                    <a href="log.php" title="Se connecter">
                        <i class='bxr bx-arrow-in-left-square-half'></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main>
        <?php 
            include_once 'includes/database.php';
        ?>

        <div class="login-box">
            <div class="login-header">
                <header>Inscription</header>
            </div>

            <?php if(isset($message_erreur)): ?>
                <div class="error-msg">
                    <?= $message_erreur; ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="input-box">
                    <input class="input-field" type="text" name="pseudo" id="pseudo" placeholder="Choisissez un pseudo" value="<?= isset($pseudo) ? htmlspecialchars($pseudo) : '' ?>" required>
                </div>

                <div class="input-box">
                    <input class="input-field" type="email" name="email" id="email" placeholder="Entrez votre email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required>
                </div>

                <div class="input-box input-box-tight">
                    <input class="input-field" type="password" name="password" id="password" placeholder="Mot de passe" required>
                </div>
                
                <div class="password-info">
                    <small>Min. 10 caractères dont une majuscule, un chiffre et un symbole spécial (!@#$...).</small>
                </div>

                <div class="input-box">
                    <input class="input-field" type="password" name="cpassword" id="cpassword" placeholder="Confirmez le mot de passe" required>
                </div>

                <div class="input-submit">
                    <button class="submit-btn" type="submit" name="formsend" id="formsend"></button>
                    <label for="formsend">S'inscrire</label>
                </div>

                <div class="sign-up-link">
                    Déjà inscrit ? <a href="log.php">Connectez-vous ici</a>
                </div>
            </form>

        </div>
    </main>

</body>
</html>