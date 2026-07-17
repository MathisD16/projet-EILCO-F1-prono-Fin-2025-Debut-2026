<?php
// ----- CONFIG -----
session_start();
include 'includes/login.php';
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion - F1 Prono</title>
    <link href='https://cdn.boxicons.com/3.0.6/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/style_log.css?v=<?= time(); ?>">
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
        <div class="login-box">
            <div class="login-header">
                <header>Login</header>
            </div>

            <?php if(isset($message_erreur)): ?>
                <div class="error-msg">
                    <?= $message_erreur; ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="input-box">
                    <input class="input-field" type="email" name="lemail" id="lemail" placeholder="Entrez votre email" value="<?= isset($lemail) ? htmlspecialchars($lemail) : '' ?>" required>
                </div>
                
                <div class="input-box">
                    <input class="input-field" type="password" name="lpassword" id="lpassword" placeholder="Entrez le mot de passe" required>
                </div>

                <div class="forgot">
                    <section>
                        <a href="#">Mot de passe oublié ?</a>
                    </section>
                </div>

                <div class="input-submit">
                    <button class="submit-btn" type="submit" name="formlogin" id="formlogin"></button>
                    <label for="formlogin">Login</label>
                </div>

                <div class="sign-up-link">
                    Pas encore inscrit ? <a href="register.php">Inscrivez-vous ici</a>
                </div>
            </form>
        </div>
        
    </main>

</body>
</html>