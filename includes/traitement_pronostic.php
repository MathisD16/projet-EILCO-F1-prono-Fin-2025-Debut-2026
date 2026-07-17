<?php
session_start();
include 'database.php';

if (!isset($_SESSION['id'])) {
    header("Location: ../log.php");
    exit;
}

$user_id = $_SESSION['id'];
$course_id = $_POST['course_id'] ?? null;
$course_type = $_POST['course_type'] ?? null;

if (!$course_id || !$course_type) {
    header("Location: ../pronostics.php?error=donnees_manquantes");
    exit;
}


$q = $db->prepare("SELECT date_course FROM courses WHERE id = ?");
$q->execute([$course_id]);
$course = $q->fetch();

if (!$course) {
    header("Location: ../pronostics.php?error=course_non_trouvee");
    exit;
}

if (time() >= strtotime($course['date_course'])) {
    header("Location: ../pronostics.php?course_id=$course_id&error=course_commencee");
    exit;
}


if ($course_type == 'grille' && isset($_POST['grille'])) {
    $donnees_pronostics = $_POST['grille'];
}
elseif ($course_type == 'course' && isset($_POST['course'])) {
    $donnees_pronostics = $_POST['course'];
}
elseif ($course_type == 'sprint' && isset($_POST['sprint'])) {
    $donnees_pronostics = $_POST['sprint'];
}
else {
    header("Location: ../pronostics.php?course_id=$course_id&error=aucune_donnee");
    exit;
}

try {
    $db->beginTransaction();


    $q = $db->prepare("SELECT id FROM pronostics WHERE user_id = ? AND course_id = ? AND type_pronostic = ? AND statut = 'actif'");
    $q->execute([$user_id, $course_id, $course_type]);
    $pronostic_existant = $q->fetch();

    if ($pronostic_existant) {
        $pronostic_id = $pronostic_existant['id'];

        $q = $db->prepare("DELETE FROM predictions WHERE pronostic_id = ?");
        $q->execute([$pronostic_id]);
    } else {

        $q = $db->prepare("INSERT INTO pronostics (user_id, course_id, type_pronostic) VALUES (?, ?, ?)");
        $q->execute([$user_id, $course_id, $course_type]);
        $pronostic_id = $db->lastInsertId();
    }


    foreach ($donnees_pronostics as $position => $pilote_id) {
        if (!empty($pilote_id)) {
            $q = $db->prepare("INSERT INTO predictions (pronostic_id, position, pilote_id) VALUES (?, ?, ?)");
            $q->execute([$pronostic_id, $position, $pilote_id]);
        }
    }

    $db->commit();
    header("Location: ../pronostics.php?course_id=$course_id&success=1");
    exit;

} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    header("Location: ../pronostics.php?course_id=$course_id&error=sauvegarde");
    exit;
}
?>