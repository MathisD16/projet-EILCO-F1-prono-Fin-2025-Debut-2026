<?php
// ----- CONFIG & AUTH -----
session_start();
include 'includes/database.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

// ----- DATA COURSES -----
$q = $db->prepare("SELECT * FROM courses ORDER BY date_course");
$q->execute();
$all_courses = $q->fetchAll();

// Organisation par GP
$gps_organises = [];
foreach ($all_courses as $c) {
    $parts = explode(' - ', $c['nom'], 2);
    
    if (count($parts) == 2) {
        $type_label = $parts[0];
        $gp_name = $parts[1];
    } else {
        $gp_name = $c['nom'];
        $type_label = ucfirst($c['type_course']);
    }

    $gps_organises[$gp_name][] = [
        'id' => $c['id'],
        'label' => $type_label,
        'date' => $c['date_course'],
        'statut' => $c['statut']
    ];
}

// ----- DATA PILOTES -----
$q = $db->prepare("SELECT * FROM pilotes ORDER BY nom");
$q->execute();
$pilotes = $q->fetchAll();

// ----- COURSE ACTIVE -----
$course_id = null;

if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];
} elseif (!empty($all_courses)) {
    foreach($all_courses as $c) {
        if(new DateTime($c['date_course']) > new DateTime()) {
            $course_id = $c['id'];
            break;
        }
    }
    if(!$course_id) $course_id = end($all_courses)['id'];
}

// Trouver GP parent
$selected_gp_name = "";
if ($course_id) {
    foreach($gps_organises as $gp_name => $sessions) {
        foreach($sessions as $s) {
            if ($s['id'] == $course_id) {
                $selected_gp_name = $gp_name;
                break 2;
            }
        }
    }
}

$course = null;
$resultats_existants = [];

// Chargement résultats
if ($course_id) {
    $q = $db->prepare("SELECT * FROM courses WHERE id = ?");
    $q->execute([$course_id]);
    $course = $q->fetch();
    
    if ($course) {
        $q = $db->prepare("
            SELECT r.type_resultat, r.position_reelle, r.pilote_id, pil.nom as pilote_nom
            FROM resultats r
            JOIN pilotes pil ON r.pilote_id = pil.id
            WHERE r.course_id = ?
            ORDER BY r.type_resultat, r.position_reelle
        ");
        $q->execute([$course_id]);
        $resultats = $q->fetchAll();
        
        foreach ($resultats as $row) {
            $resultats_existants[$row['type_resultat']][$row['position_reelle']] = [
                'pilote_id' => $row['pilote_id'],
                'pilote_nom' => $row['pilote_nom']
            ];
        }
    }
}

// ----- SAUVEGARDE RESULTATS -----
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sauvegarder_resultats'])) {
    $p_course_id = $_POST['course_id'];
    $p_course_type = $_POST['course_type'];
    
    try {
        $db->beginTransaction();
        
        $q = $db->prepare("DELETE FROM resultats WHERE course_id = ?");
        $q->execute([$p_course_id]);
        
        function insertResults($db, $cid, $type, $data) {
            if(isset($data) && is_array($data)) {
                foreach ($data as $pos => $pid) {
                    if (!empty($pid)) {
                        $stmt = $db->prepare("INSERT INTO resultats (course_id, type_resultat, pilote_id, position_reelle) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$cid, $type, $pid, $pos]);
                    }
                }
            }
        }

        if ($p_course_type == 'grille') insertResults($db, $p_course_id, 'grille', $_POST['grille'] ?? []);
        if ($p_course_type == 'course') insertResults($db, $p_course_id, 'course', $_POST['course'] ?? []);
        if ($p_course_type == 'sprint') insertResults($db, $p_course_id, 'sprint', $_POST['sprint'] ?? []);
        
        $db->commit();
        header("Location: admin_resultats.php?course_id=$p_course_id&success=saved");
        exit;
        
    } catch (Exception $e) {
        $db->rollBack();
        $message_erreur = "❌ Erreur : " . $e->getMessage();
    }
}

// ----- CALCUL POINTS -----
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['calculer_points'])) {
    $p_course_id = $_POST['course_id'];
    try {
        calculerPointsCourse($p_course_id);
        header("Location: admin_resultats.php?course_id=$p_course_id&success=calculated");
        exit;
    } catch (Exception $e) {
        $message_erreur = "❌ Erreur calcul : " . $e->getMessage();
    }
}

if (isset($_GET['success'])) {
    if ($_GET['success'] == 'saved') $message_success = "✅ Résultats sauvegardés avec succès !";
    if ($_GET['success'] == 'calculated') $message_success = "✅ Points calculés et classement mis à jour !";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Résultats - F1 Prono</title>
    <link href='https://cdn.boxicons.com/3.0.6/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/style_admin.css?v=<?= time(); ?>">
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
                <a href="profil.php" title="Mon Profil"><i class='bx bx-user'></i></a>
                <a href="includes/logout.php" title="Se déconnecter"><i class='bxr bx-arrow-out-right-square-half'></i></a>
            </div>
        </div>
    </header>

    <div class="container main-content">

        <h1 class="main-title">Administration Résultats</h1>

        <?php if (isset($message_success)): ?>
            <div class="alert alert-success"><?= $message_success ?></div>
        <?php endif; ?>
        
        <?php if (isset($message_erreur)): ?>
            <div class="alert alert-error"><?= $message_erreur ?></div>
        <?php endif; ?>

        <?php if (count($gps_organises) > 0): ?>
            <div class="course-selector-wrapper">
                <div style="display:flex; flex-wrap:wrap; gap:20px; justify-content:center; align-items:center;">
                    
                    <div style="flex:1; min-width:280px;">
                        <h3 style="font-size:1rem; margin-bottom:5px;">Grand Prix</h3>
                        <select id="gp_select" class="styled-select" style="width:100%;">
                            <?php foreach($gps_organises as $gp_name => $sessions): ?>
                                <option value="<?= htmlspecialchars($gp_name) ?>" <?= $gp_name == $selected_gp_name ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($gp_name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div style="flex:1; min-width:280px;">
                        <h3 style="font-size:1rem; margin-bottom:5px;">Session à arbitrer</h3>
                        <select id="session_select" class="styled-select" style="width:100%;" onchange="if(this.value) window.location='admin_resultats.php?course_id='+this.value">
                            <option value="">-- Choisir une session --</option>
                        </select>
                    </div>
                </div>
            </div>

            <script>
                const gpData = <?= json_encode($gps_organises) ?>;
                const currentCourseId = "<?= $course_id ?>";
                
                const gpSelect = document.getElementById('gp_select');
                const sessionSelect = document.getElementById('session_select');

                function updateSessions() {
                    const selectedGP = gpSelect.value;
                    const sessions = gpData[selectedGP];
                    
                    sessionSelect.innerHTML = ''; 

                    const defaultOption = document.createElement('option');
                    defaultOption.text = "-- Choisir une session --";
                    defaultOption.value = "";
                    sessionSelect.appendChild(defaultOption);

                    sessions.forEach(session => {
                        const option = document.createElement('option');
                        option.value = session.id;
                        
                        const dateObj = new Date(session.date);
                        const dateStr = dateObj.toLocaleDateString('fr-FR', {day:'2-digit', month:'2-digit'}) + ' ' + dateObj.toLocaleTimeString('fr-FR', {hour:'2-digit', minute:'2-digit'});
                        
                        const statusText = (session.statut === 'termine') ? ' (✅ Validé)' : '';

                        option.text = session.label + ' - ' + dateStr + statusText;
                        
                        if(session.id == currentCourseId) {
                            option.selected = true;
                        }
                        sessionSelect.appendChild(option);
                    });
                }

                gpSelect.addEventListener('change', updateSessions);
                updateSessions(); 
            </script>
        <?php else: ?>
            <div class="alert alert-error">Aucune course disponible.</div>
        <?php endif; ?>

        <?php if ($course): ?>
            <div class="form-card">
                <h2><?= htmlspecialchars($course['nom']) ?></h2>
                <p style="margin-bottom: 20px; color:#555;">
                    Type : <strong><?= ucfirst($course['type_course']) ?></strong> | 
                    Date : <?= date('d/m/Y à H:i', strtotime($course['date_course'])) ?>
                </p>

                <form method="POST">
                    <input type="hidden" name="course_id" value="<?= $course_id ?>">
                    <input type="hidden" name="course_type" value="<?= $course['type_course'] ?>">
                    
                    <?php 
                    $configs = [];
                    if ($course['type_course'] == 'grille') $configs[] = ['label' => 'Grille de Départ (Top 10)', 'field' => 'grille', 'limit' => 10];
                    if ($course['type_course'] == 'course') $configs[] = ['label' => 'Course Principale (Top 10)', 'field' => 'course', 'limit' => 10];
                    if ($course['type_course'] == 'sprint') $configs[] = ['label' => 'Course Sprint (Top 5)', 'field' => 'sprint', 'limit' => 5];
                    
                    // ----- BOUCLE : CHAMPS -----
                    foreach($configs as $conf):
                    ?>
                        <h3><?= $conf['label'] ?></h3>
                        <div class="form-grid">
                            <?php for ($position = 1; $position <= $conf['limit']; $position++): ?>
                                <div class="input-group">
                                    <label>Position <?= $position ?></label>
                                    <select name="<?= $conf['field'] ?>[<?= $position ?>]">
                                        <option value="">-- Pilote --</option>
                                        <?php foreach($pilotes as $pilote): ?>
                                            <option value="<?= $pilote['id'] ?>"
                                                <?= (isset($resultats_existants[$conf['field']][$position]) && $resultats_existants[$conf['field']][$position]['pilote_id'] == $pilote['id']) ? 'selected' : '' ?>>
                                                <?= $pilote['nom'] ?> #<?= $pilote['numero'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (isset($resultats_existants[$conf['field']][$position])): ?>
                                        <small>Actuel: <?= $resultats_existants[$conf['field']][$position]['pilote_nom'] ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php endfor; ?>
                        </div>
                    <?php endforeach; ?>
                    
                    <div class="btn-group">
                        <button type="submit" name="sauvegarder_resultats" class="btn-save">
                            <i class='bx bx-save'></i> Sauvegarder les résultats
                        </button>
                        
                        <button type="submit" name="calculer_points" class="btn-calc" onclick="return confirm('⚠️ Attention : Cela va mettre à jour les points de tous les utilisateurs pour cette course. Continuer ?')">
                            <i class='bx bx-calculator'></i> Calculer & Attribuer Points
                        </button>
                    </div>

                </form>

                <?php if (!empty($resultats_existants)): ?>
                    <div class="results-summary">
                        <h3>📋 Résultats enregistrés en base</h3>
                        <?php foreach ($resultats_existants as $type => $positions): ?>
                            <h4><?= ucfirst($type) ?></h4>
                            <?php ksort($positions); ?>
                            <?php foreach ($positions as $position => $data): ?>
                                <div class="result-line">
                                    <strong>P<?= $position ?></strong> : <?= $data['pilote_nom'] ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>

    <footer class="eilco-footer">
        <div class="container">
            <p><strong>F1 Prono</strong> - Projet Étudiant <a href="https://eilco.univ-littoral.fr/" target="_blank">EILCO</a></p>
            <p style="font-size: 0.8rem; opacity: 0.7;">Réalisé par DELAUX Clarence , DIDIER Paul, DUMONDEL Mathis</p>
        </div>
    </footer>

</body>
</html>

<?php
// ----- FONCTION CALCUL -----
function calculerPointsCourse($course_id) {
    global $db;
    $db->beginTransaction();
    try {
        $q = $db->prepare("SELECT p.id as pronostic_id, p.user_id, p.type_pronostic, pred.position as position_predite, pred.pilote_id as pilote_predite FROM pronostics p JOIN predictions pred ON p.id = pred.pronostic_id WHERE p.course_id = ? AND p.statut = 'actif'");
        $q->execute([$course_id]);
        $pronostics = $q->fetchAll();
        
        $q = $db->prepare("SELECT type_resultat, pilote_id, position_reelle FROM resultats WHERE course_id = ?");
        $q->execute([$course_id]);
        $resultats = $q->fetchAll();
        
        $resultats_organises = [];
        foreach ($resultats as $r) {
            $resultats_organises[$r['type_resultat']][$r['pilote_id']] = $r['position_reelle'];
        }
        
        $points_par_pronostic = [];
        
        foreach ($pronostics as $pronostic) {
            $type = $pronostic['type_pronostic'];
            $pronostic_id = $pronostic['pronostic_id'];
            $pilote_predite = $pronostic['pilote_predite'];
            $position_predite = $pronostic['position_predite'];
            
            if (isset($resultats_organises[$type][$pilote_predite])) {
                $position_reelle = $resultats_organises[$type][$pilote_predite];
                $ecart = abs($position_predite - $position_reelle);
                
                $points = 0;
                if ($ecart == 0) $points = 10;
                elseif ($ecart == 1) $points = 4;
                elseif ($ecart == 2) $points = 1;
                
                if (!isset($points_par_pronostic[$pronostic_id])) $points_par_pronostic[$pronostic_id] = 0;
                $points_par_pronostic[$pronostic_id] += $points;
            }
        }
        
        foreach ($points_par_pronostic as $pronostic_id => $points) {
            $q = $db->prepare("UPDATE pronostics SET points_obtenus = ? WHERE id = ?");
            $q->execute([$points, $pronostic_id]);
        }
        
        $q = $db->prepare("SELECT user_id, SUM(points_obtenus) as total_points FROM pronostics WHERE statut = 'actif' GROUP BY user_id");
        $q->execute();
        $totaux = $q->fetchAll();
        
        foreach ($totaux as $t) {
            $q = $db->prepare("UPDATE users SET points_totaux = ? WHERE id = ?");
            $q->execute([$t['total_points'], $t['user_id']]);
        }
        
        $q = $db->prepare("UPDATE courses SET statut = 'termine' WHERE id = ?");
        $q->execute([$course_id]);
        
        $db->commit();
    } catch (Exception $e) {
        $db->rollBack();
        throw $e;
    }
}
?>