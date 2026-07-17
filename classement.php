<?php
// ----- SECURITE -----
session_start(); 

if (!isset($_SESSION['id'])) {
    header("Location: log.php");
    exit;
}
include "includes/database.php";
global $db;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classement - F1 Prono</title>
    <link href='https://cdn.boxicons.com/3.0.6/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/style_classement.css?v=<?= time(); ?>">
</head>
<body>

    <header>
        <div class="nav container">
            <a href="index.php" class="logo">F1 Prono<span>.</span></a>

            <div class="navbar">
                <a href="index.php" class="nav-link">Accueil</a>
                <a href="classement.php" class="nav-link" style="color:white;">Classement</a>
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

    <?php
    // ----- DATA CLASSEMENT -----
    $q = $db->query("SELECT id, pseudo, points_totaux FROM users WHERE role='user' ORDER BY points_totaux DESC");
    $users = $q->fetchAll();
    
    $ma_position = null;
    $mes_points = 0;
    $mon_pseudo = $_SESSION['pseudo'];
    
    foreach ($users as $index => $user) {
        if ($user['pseudo'] == $mon_pseudo) {
            $ma_position = $index + 1;
            $mes_points = $user['points_totaux'];
            break;
        }
    }
    ?>

    <div class="container" style="margin-top: 20px;">
        
        <h1 class="main-title">Tableau des Scores</h1>

        <section class="personal-section">
            <h2>Mon Classement Personnel</h2>
            <table cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th style="text-align:center;">Pseudo</th>
                        <th style="text-align:center;">Position</th>
                        <th style="text-align:center;">Points</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="is-me">
                        <td style="text-align:center;"><?= htmlspecialchars($mon_pseudo) ?></td>
                        <td style="text-align:center;"><?= $ma_position ?> ème</td>
                        <td style="text-align:center;"><?= $mes_points ?> pts</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="ranking-grid">
            
            <div class="rank-card box-full">
                <h2>Classement Complet</h2>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Pilote (Joueur)</th>
                            <th>Points</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($users as $index => $user): ?>
                        <tr class="<?= ($user['pseudo'] == $mon_pseudo) ? 'is-me' : '' ?>">
                            <td><?= $index+1 ?></td>
                            <td>
                                <?= htmlspecialchars($user['pseudo']) ?> 
                                <?= ($user['pseudo'] == $mon_pseudo) ? '(Moi)' : '' ?>
                            </td>
                            <td><strong><?= $user['points_totaux'] ?></strong></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="rank-card box-top3">
                <h2>Podium 🏆</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Pos</th>
                            <th>Pseudo</th>
                            <th>Pts</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for($i=0; $i<min(3, count($users)); $i++): ?>
                        <tr>
                            <td>
                                <?php if($i == 0): ?><span class="medal">🥇</span>
                                <?php elseif($i == 1): ?><span class="medal">🥈</span>
                                <?php elseif($i == 2): ?><span class="medal">🥉</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($users[$i]['pseudo']) ?></strong>
                            </td>
                            <td><?= $users[$i]['points_totaux'] ?></td>
                        </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            </div>

        </section>

        <div class="cta-container">
            <a href="pronostics.php" class="btn-action">Faire mes pronostics</a>
        </div>

    </div>

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