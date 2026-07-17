<?php
// ----- SESSION -----
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - F1 Prono</title>
    <link href='https://cdn.boxicons.com/3.0.6/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/style_index.css?v=<?= time(); ?>">
</head>
<body>

    <header>
        <div class="nav container">
            <a href="index.php" class="logo">F1 Prono<span>.</span></a>

            <div class="navbar">
                <a href="index.php" class="nav-link" style="color:white;">Accueil</a>
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

    <section class="home" id="home">
        <div class="home-content container">
            <div class="image-wrapper">
                <img src="img/homef1.avif" alt="F1 Ferrari Home" class="car-img">
                
                <div class="image-overlay-text">
                    <h1>F1 PRONO 2026</h1>
                    <p class="hero-subtitle">Défiez vos amis sur chaque Grand Prix</p>
                </div>

                <a href="#head" class="btn-start">En savoir plus</a>
            </div>
        </div>
    </section>

    <section class="heading-section" id="head">
        <div class="heading">
            <div class="content">
                <img src="img/home3.jpg" alt="Règles F1 Prono">
                
                <div class="content-text">
                    <h2>Comment ça marche ?</h2>
                    <p>
                        À chaque Grand Prix, pronostiquez le Top 10 de la qualification, de la course mais aussi de la course Sprint ! </p>
                        <p>Plus vos prédictions sont précises, plus vous marquez de points et grimpez au classement général.
                    </p>
                    
                    <ul class="rules-list">
                        <li>
                            <i class='bx bxs-trophy'></i> 
                            <strong>Position Exacte :</strong> 10 Points
                        </li>
                        <li>
                            <i class='bx bx-radio-circle-marked'></i> 
                            <strong>1 place d'écart :</strong> 4 Points
                        </li>
                        <li>
                            <i class='bx bx-radio-circle-marked'></i> 
                            <strong>2 places d'écart :</strong> 1 Point
                        </li>
                    </ul>

                    <p style="font-size: 0.9rem; margin-top: 10px; font-style: italic;">
                        Exemple : Si vous pronostiquez Verstappen P1 et qu'il finit P2, vous gagnez 4 points.
                    </p>

                    <a href="pronostics.php" class="btn">Faire un pronostique</a> 
                </div>
            </div>
        </div>
    </section>

    <footer class="eilco-footer">
        <div class="container">
            <p>
                <strong>F1 Prono</strong> - Projet Étudiant 
                <a href="https://eilco.univ-littoral.fr/" target="_blank">EILCO</a>
            </p>
            <p style="font-size: 0.8rem; opacity: 0.7;">
                Réalisé par DELAUX Clarence , DIDIER Paul, DUMONDEL Mathis
            </p>
        </div>
    </footer>
</body>
</html>