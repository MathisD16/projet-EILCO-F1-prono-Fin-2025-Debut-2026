<?php
session_start(); 
include "includes/database.php"; 
global $db;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Découvrir la F1 - F1 Prono</title>
    <link href='https://cdn.boxicons.com/3.0.6/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/style_decouverte.css?v=<?= time(); ?>">
</head>
<body>

    <header>
        <div class="nav container">
            <a href="index.php" class="logo">F1 Prono<span>.</span></a>
            <div class="navbar">
                <a href="index.php" class="nav-link">Accueil</a>
                <a href="classement.php" class="nav-link">Classement</a>
                <a href="decouverte.php" class="nav-link" style="color:white;">Découvrir la F1</a>
                <a href="pronostics.php" class="nav-link">Prono</a>

            </div>
            <div class="nav-icons">
                <?php if(isset($_SESSION['id'])): ?>
                    <a href="profil.php" title="Mon Profil"><i class='bx bx-user'></i></a>
                    <a href="includes/logout.php" title="Se déconnecter"><i class='bxr bx-arrow-out-right-square-half'></i></a>
                <?php else: ?>
                    <a href="log2.php" title="Se connecter"><i class='bxr bx-arrow-in-left-square-half'></i></a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <?php
    // --- RÉCUPÉRATION DES DONNÉES DEPUIS LA BDD ---
    
    $q = $db->query("SELECT * FROM pilotes ORDER BY  titres DESC, victoires DESC, gp_disputes ASC");
    $pilotes = $q->fetchAll();


    $q = $db->query("SELECT * FROM equipes ORDER BY titres DESC, victoires DESC");
    $equipes = $q->fetchAll();


    $q = $db->query("SELECT * FROM circuits ORDER BY annee_apparition ASC");
    $circuits = $q->fetchAll();
    ?>

    <div class="container" style="margin-top: 20px;">
        
        <h1 class="main-title">Découvrir la Formule 1</h1>

        <div class="rank-card intro-card">
            <h2>🏁 Qu'est-ce que la Formule 1 ?</h2>
            <div class="intro-content">
                <p>La <strong>Formule 1</strong> est la catégorie reine du sport automobile, alliant technologie de pointe et performance humaine.</p>
                <h3 class="intro-subtitle">🏆 Deux Championnats</h3>
                <ul class="intro-list">
                    <li><strong>Pilotes :</strong> La gloire individuelle pour celui qui marque le plus de points.</li>
                    <li><strong>Constructeurs :</strong> La récompense pour l'écurie (la somme des points des deux voitures).</li>
                </ul>
                <h3 class="intro-subtitle">🚀 L'Ère 2026</h3>
                <p>Voici les forces en présence pour la saison révolutionnaire 2026 (Nouvelles règles, Hamilton chez Ferrari, arrivée d'Audi & Cadillac).</p>
            </div>
        </div>

        <section class="discovery-grid">
            
            <div class="rank-card box-full">
                <h2>🏎️ Le Plateau Pilotes 2026</h2>
                <table cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Pilote</th>
                            <th>Écurie</th>
                            <th class="text-center">Âge</th>
                            <th class="text-center">Exp.</th>
                            <th>Palmarès</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($pilotes as $p): 
                            $img_name = strtolower(str_replace(' ', '_', $p['nom'])) . '.png';
                            $img_path = "img/drivers/" . $img_name;
                            if (!file_exists($img_path)) { $img_path = "img/drivers/default.png"; }
                        ?>
                        <tr>
                            <td>
                                <div class="img-cell-container">
                                    <img src="<?= $img_path ?>" alt="<?= $p['nom'] ?>" class="driver-photo">
                                    <div>
                                        <span class="flag"><?= $p['nationalite'] ?></span>
                                        <span class="driver-number">#<?= $p['numero'] ?></span>
                                        <strong><?= htmlspecialchars($p['nom']) ?></strong>
                                    </div>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($p['ecurie']) ?></td>
                            <td class="text-center"><?= $p['age'] ?></td>
                            <td class="text-center"><?= $p['gp_disputes'] ?> GP</td>
                            <td>
                                <?php if($p['titres'] > 0): ?>
                                    <span class="stat-highlight"><?= $p['titres'] ?> Titres</span><br>
                                <?php endif; ?>
                                <?php if($p['victoires'] > 0): ?>
                                    <?= $p['victoires'] ?> Victoires
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="rank-card box-full">
                <h2>🏗️ Les Constructeurs & leurs Histoires</h2>
                <table cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Écurie</th>
                            <th>Pays</th>
                            <th class="text-center">Début F1</th>
                            <th class="text-center">Victoires</th>
                            <th class="text-center">Championnats</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($equipes as $e): 
                             $logo_name = strtolower(str_replace(' ', '_', $e['nom'])) . '.png';
                             $logo_path = "img/teams/" . $logo_name;
                             if (!file_exists($logo_path)) { $logo_path = "img/teams/default.png"; }
                        ?>
                        <tr>
                            <td>
                                <div class="img-cell-container">
                                    <img src="<?= $logo_path ?>" alt="<?= $e['nom'] ?>" class="team-logo-small">
                                    <strong><?= htmlspecialchars($e['nom']) ?></strong>
                                </div>
                            </td>
                            <td><?= $e['pays'] ?></td>
                            <td class="text-center"><?= $e['annee_debut'] ?></td>
                            <td class="text-center"><?= $e['victoires'] ?></td>
                            <td class="text-center">
                                <?php if($e['titres'] > 0): ?>
                                    <span class="stat-highlight">🏆 <?= $e['titres'] ?></span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="rank-card box-full">
                <h2>🌍 Les Circuits</h2>
                <table cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Circuit</th>
                            <th>Lieu</th>
                            <th>Longueur</th>
                            <th>Virages</th>
                            <th>1ère Apparition</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($circuits as $c): ?>
                        <tr>
                            <td>
                                <span class="flag"><?= $c['drapeau'] ?></span>
                                <strong><?= htmlspecialchars($c['nom']) ?></strong>
                            </td>
                            <td><?= htmlspecialchars($c['lieu']) ?></td>
                            <td><?= $c['longueur'] ?></td>
                            <td><?= $c['virages'] ?></td>
                            <td><?= $c['annee_apparition'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </section>
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