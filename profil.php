<?php
session_start();
include 'includes/database.php';

if (!isset($_SESSION['id'])) {
    header("Location: log.php");
    exit;
}

$user_id = $_SESSION['id'];


$q = $db->prepare("SELECT pseudo, email, points_totaux, role FROM users WHERE id = ?");
$q->execute([$user_id]);
$user = $q->fetch();


$q = $db->prepare("SELECT COUNT(*) as total FROM pronostics WHERE user_id = ? AND statut = 'actif'");
$q->execute([$user_id]);
$total_pronostics = $q->fetch()['total'];


$q = $db->prepare("
    SELECT 
        type_pronostic,
        COUNT(*) as nombre,
        SUM(points_obtenus) as points
    FROM pronostics 
    WHERE user_id = ? AND statut = 'actif'
    GROUP BY type_pronostic
");
$q->execute([$user_id]);
$stats_type = $q->fetchAll();


$q = $db->prepare("
    SELECT 
        p.id as pronostic_id,
        c.nom as course_nom,
        c.id as course_id,
        c.date_course,
        c.type_course,
        p.type_pronostic,
        p.points_obtenus,
        p.date_pronostic
    FROM pronostics p
    JOIN courses c ON p.course_id = c.id
    WHERE p.user_id = ? AND p.statut = 'actif'
    ORDER BY p.date_pronostic DESC
    LIMIT 10
");
$q->execute([$user_id]);
$derniers_pronos = $q->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - F1 Prono</title>
    <link href='https://cdn.boxicons.com/3.0.6/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/style_profil.css">
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

    <div class="container main-content">
        
        <h1 class="main-title">Mon Espace</h1>

        <div class="profile-grid">
            
            <div class="profile-card">
                <h2>Mes Infos</h2>
                <div class="user-info">
                    <p><span>Pseudo</span> <strong><?= htmlspecialchars($user['pseudo']) ?></strong></p>
                    <p><span>Email</span> <strong><?= htmlspecialchars($user['email']) ?></strong></p>
                    <p><span>Rôle</span> <strong><?= $user['role'] == 'admin' ? 'Administrateur' : 'Pilote' ?></strong></p>
                    <p><span>Inscrit depuis</span> <strong>-</strong></p> </div>
            </div>

            <div class="profile-card">
                <h2>Mes Statistiques</h2>
                
                <div class="stats-container">
                    <div class="stat-box">
                        <span class="stat-number"><?= $user['points_totaux'] ?></span>
                        <span class="stat-label">Points Totaux</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number"><?= $total_pronostics ?></span>
                        <span class="stat-label">Pronostics</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number">
                            <?= ($total_pronostics > 0) ? round($user['points_totaux'] / $total_pronostics, 1) : 0 ?>
                        </span>
                        <span class="stat-label">Moyenne</span>
                    </div>
                </div>

                <?php if (count($stats_type) > 0): ?>
                    <div class="stats-details">
                        <h3>Détail par catégorie</h3>
                        <?php foreach ($stats_type as $stat): ?>
                            <p style="margin-bottom: 5px; color:#666;">
                                <strong><?= ucfirst($stat['type_pronostic']) ?></strong> : 
                                <?= $stat['points'] ?> pts (<?= $stat['nombre'] ?> pronos)
                            </p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="profile-card history-section">
            <h2>Mes Derniers Pronostics</h2>
            
            <?php if (count($derniers_pronos) > 0): ?>
                <div style="overflow-x:auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Grand Prix</th>
                                <th>Type</th>
                                <th>Date pari</th>
                                <th>Résultat</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($derniers_pronos as $prono): ?>
                                <?php
                                $maintenant = new DateTime();
                                $date_course = new DateTime($prono['date_course']);
                                $course_passee = $maintenant > $date_course;
                                ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($prono['course_nom']) ?></strong></td>
                                    <td><?= ucfirst($prono['type_pronostic']) ?></td>
                                    <td><?= date('d/m H:i', strtotime($prono['date_pronostic'])) ?></td>
                                    <td>
                                        <?php if ($course_passee): ?>
                                            <span class="badge-points"><?= $prono['points_obtenus'] ?> pts</span>
                                        <?php else: ?>
                                            <span class="badge-pending">En attente</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="pronostics.php?course_id=<?= $prono['course_id'] ?>" class="btn-small">
                                            Détails
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if ($total_pronostics > 10): ?>
                    <p style="text-align:center; margin-top:15px; color:#888;">
                        ... et <?= $total_pronostics - 10 ?> autres anciens pronostics
                    </p>
                <?php endif; ?>
                
            <?php else: ?>
                <p style="padding: 20px; text-align: center;">Vous n'avez pas encore fait de pronostics.</p>
            <?php endif; ?>
        </div>

        <div class="actions-container">
            <a href="pronostics.php" class="btn-primary">Faire un prono</a>
            <?php if ($user['role'] == 'admin'): ?>
                <a href="admin_resultats.php" class="btn-secondary">Admin Results</a>
            <?php endif; ?>
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