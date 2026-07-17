<?php
// ----- CONFIG & SÉCURITÉ -----
session_start();
include 'includes/database.php';

if (!isset($_SESSION['id'])) {
    header("Location: log.php");
    exit;
}

// ----- DATA COURSES -----
$q = $db->prepare("SELECT * FROM courses ORDER BY date_course");
$q->execute();
$all_courses = $q->fetchAll();

// ----- TRI PAR GP -----
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

// -----DATA PILOTES -----
$q = $db->prepare("SELECT * FROM pilotes ORDER BY nom");
$q->execute();
$pilotes = $q->fetchAll();

// ----- COURSE ACTIVE -----
$course_id = null;
if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];
} elseif (!empty($all_courses)) {
    // Défaut : Prochaine
    foreach($all_courses as $c) {
        if(new DateTime($c['date_course']) > new DateTime()) {
            $course_id = $c['id'];
            break;
        }
    }
    // Sinon : Dernière
    if(!$course_id) $course_id = end($all_courses)['id'];
}

// ----- GP PARENT -----
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
$pronostics_existants = [];
$course_passee = false;

// ----- CHARGEMENT INFOS -----
if ($course_id) {
    $q = $db->prepare("SELECT * FROM courses WHERE id = ?");
    $q->execute([$course_id]);
    $course = $q->fetch();
    
    if ($course) {
        $maintenant = new DateTime();
        $date_course = new DateTime($course['date_course']);
        $course_passee = $maintenant > $date_course;
        
        $q = $db->prepare("
            SELECT p.type_pronostic, pred.position, pred.pilote_id, pil.nom as pilote_nom
            FROM pronostics p
            JOIN predictions pred ON p.id = pred.pronostic_id
            JOIN pilotes pil ON pred.pilote_id = pil.id
            WHERE p.user_id = ? AND p.course_id = ? AND p.statut = 'actif'
            ORDER BY p.type_pronostic, pred.position
        ");
        $q->execute([$_SESSION['id'], $course_id]);
        $resultats = $q->fetchAll();

        foreach ($resultats as $row) {
            $pronostics_existants[$row['type_pronostic']][$row['position']] = [
                'pilote_id' => $row['pilote_id'],
                'pilote_nom' => $row['pilote_nom']
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Pronostics - F1 Prono</title>
    <link href='https://cdn.boxicons.com/3.0.6/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/style_prono.css?v=<?= time(); ?>">
</head>
<body>

    <header>
        <div class="nav container">
            <a href="index.php" class="logo">F1 Prono<span>.</span></a>
            <div class="navbar">
                <a href="index.php" class="nav-link">Accueil</a>
                <a href="classement.php" class="nav-link">Classement</a>
                <a href="decouverte.php" class="nav-link">Découvrir la F1</a>
                <a href="pronostics.php" class="nav-link" style="color:white;">Prono</a>
            </div>
            <div class="nav-icons">
                <?php if(isset($_SESSION['id'])): ?>
                    <a href="profil.php" title="Mon Profil"><i class='bx bx-user'></i></a>
                    <a href="includes/logout.php" title="Se déconnecter"><i class='bxr bx-arrow-out-right-square-half'></i></a>
                <?php else: ?>
                    <a href="log.php" title="Se connecter"><i class='bxr bx-arrow-in-left-square-half'></i></a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="container main-content">

        <h1 class="main-title">Espace Pronostics</h1>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">✅ Vos pronostics ont été sauvegardés avec succès !</div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error">❌ Erreur lors de la sauvegarde. Veuillez réessayer.</div>
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
                        <h3 style="font-size:1rem; margin-bottom:5px;">Session</h3>
                        <select id="session_select" class="styled-select" style="width:100%;" onchange="if(this.value) window.location='pronostics.php?course_id='+this.value">
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

                    // Option défaut
                    const defaultOption = document.createElement('option');
                    defaultOption.text = "-- Choisir une session --";
                    defaultOption.value = "";
                    sessionSelect.appendChild(defaultOption);

                    sessions.forEach(session => {
                        const option = document.createElement('option');
                        option.value = session.id;
                        
                        const dateObj = new Date(session.date);
                        const dateStr = dateObj.toLocaleDateString('fr-FR', {day:'2-digit', month:'2-digit'}) + ' ' + dateObj.toLocaleTimeString('fr-FR', {hour:'2-digit', minute:'2-digit'});
                        
                        const now = new Date();
                        const suffix = (now > dateObj) ? ' (Terminée)' : '';

                        option.text = session.label + ' - ' + dateStr + suffix;
                        
                        // Sélection active
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
            
            <div class="course-info-card">
                <h2><?= htmlspecialchars($course['nom']) ?></h2>
                <p>
                    <strong>Date :</strong> <?= date('d/m/Y à H:i', strtotime($course['date_course'])) ?> | 
                    <strong>Type :</strong> 
                    <?php 
                        if ($course['type_course'] == 'grille') echo 'Qualifications';
                        elseif ($course['type_course'] == 'course') echo 'Course Principale';
                        elseif ($course['type_course'] == 'sprint') echo 'Course Sprint';
                    ?>
                </p>

                <?php if ($course_passee): ?>
                    <span class="status-badge status-closed">Course terminée (Lecture seule)</span>
                <?php else: ?>
                    <span class="status-badge status-open">Pronostics ouverts</span>
                <?php endif; ?>

                <?php if (isset($pronostics_existants[$course['type_course']]) && !$course_passee): ?>
                    <p style="margin-top:10px; color:#047857; font-size:0.9rem;">
                        <i class='bx bx-check-circle'></i> Pronostics déjà enregistrés (Modifiable)
                    </p>
                <?php endif; ?>
            </div>

            <div class="form-card">
                
                <?php if ($course_passee): ?>
                    <h3>Vos Pronostics pour cette course</h3>
                    <div class="form-grid">
                        <?php 
                        $limit = ($course['type_course'] == 'sprint') ? 5 : 10;
                        $type = ($course['type_course'] == 'grille') ? 'grille' : (($course['type_course'] == 'sprint') ? 'sprint' : 'course');
                        
                        for ($position = 1; $position <= $limit; $position++): ?>
                            <div class="input-group">
                                <label>Position <?= $position ?></label>
                                <div style="padding:10px; background:#f9f9f9; border-radius:8px; border:1px solid #eee;">
                                    <?php if (isset($pronostics_existants[$type][$position])): ?>
                                        <strong><?= $pronostics_existants[$type][$position]['pilote_nom'] ?></strong>
                                    <?php else: ?>
                                        <span style="color:#999;">Pas de pronostic</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                    <p style="text-align:center; color:#666; font-size:0.9rem;">Les pronostics sont clos pour cet événement.</p>

                <?php else: ?>
                    
                    <form action="includes/traitement_pronostic.php" method="POST" id="pronosticForm">
                        <input type="hidden" name="course_id" value="<?= $course_id ?>">
                        <input type="hidden" name="course_type" value="<?= $course['type_course'] ?>">

                        <?php 
                            if ($course['type_course'] == 'grille') {
                                echo '<h3>Grille de Départ (Top 10)</h3>';
                                $limit = 10;
                                $field_name = 'grille';
                            } elseif ($course['type_course'] == 'course') {
                                echo '<h3>Résultat Course (Top 10)</h3>';
                                $limit = 10;
                                $field_name = 'course';
                            } elseif ($course['type_course'] == 'sprint') {
                                echo '<h3>Résultat Sprint (Top 5)</h3>';
                                $limit = 5;
                                $field_name = 'sprint';
                            }
                        ?>
                        
                        <div class="form-grid">
                            <?php // ----- BOUCLE : POSITIONS ----- ?>
                            <?php for ($position = 1; $position <= $limit; $position++): ?>
                                <div class="input-group">
                                    <label>Position <?= $position ?> :</label>
                                    <select name="<?= $field_name ?>[<?= $position ?>]" required class="driver-select">
                                        <option value="">-- Choisir un pilote --</option>
                                        <?php foreach($pilotes as $pilote): ?>
                                            <option value="<?= $pilote['id'] ?>" 
                                                <?= (isset($pronostics_existants[$field_name][$position]) && $pronostics_existants[$field_name][$position]['pilote_id'] == $pilote['id']) ? 'selected' : '' ?>>
                                                <?= $pilote['nom'] ?> (<?= $pilote['numero'] ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    
                                    <?php if (isset($pronostics_existants[$field_name][$position])): ?>
                                        <small class="current-selection">
                                            Actuel: <?= $pronostics_existants[$field_name][$position]['pilote_nom'] ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                            <?php endfor; ?>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-submit">
                                <?= (isset($pronostics_existants[$course['type_course']])) ? 'Modifier mes choix' : 'Valider mes pronostics' ?>
                            </button>
                            
                            <button type="button" class="btn-reset" onclick="resetForm()">
                                Réinitialiser
                            </button>
                        </div>
                    </form>

                    <script>
                    // ----- RESET FORM -----
                    function resetForm() {
                        if (confirm('Voulez-vous effacer votre sélection actuelle ?')) {
                            const selects = document.querySelectorAll('select[name^="grille"], select[name^="course"], select[name^="sprint"]');
                            selects.forEach(select => {
                                select.value = "";
                            });
                            const labels = document.querySelectorAll('.current-selection');
                            labels.forEach(l => l.style.display = 'none');
                        }
                    }

                    // ----- ANTI DOUBLON -----
                    document.getElementById('pronosticForm').addEventListener('submit', function(e) {
                        const selects = document.querySelectorAll('.driver-select');
                        const values = new Set();
                        let hasDuplicate = false;

                        selects.forEach(select => {
                            if (select.value) {
                                if (values.has(select.value)) {
                                    hasDuplicate = true;
                                }
                                values.add(select.value);
                            }
                        });

                        if (hasDuplicate) {
                            e.preventDefault(); // Stop envoi
                            alert('⚠️ Impossible de sélectionner le même pilote plusieurs fois !');
                        }
                    });
                    </script>

                <?php endif; ?>
            </div>

        <?php elseif ($course_id && !$course): ?>
            <div class="alert alert-error">Course introuvable.</div>
        <?php endif; ?>

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