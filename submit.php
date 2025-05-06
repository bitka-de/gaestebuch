<?php

/**
 * Verarbeitet die Gästebucheinträge aus dem Formular (index.php).
 * 
 * Validiert Benutzereingaben (Name, E-Mail, Domain, Nachricht) und speichert sie,
 * wenn sie korrekt sind. Bei Fehlern erfolgt eine Weiterleitung mit Meldung.
 */

require_once 'functions.php';
session_start();

// -------------------------------------------------------------
// Bot-Schutz: Honeypot prüfen
// -------------------------------------------------------------
if (!empty($_POST['website'])) {
    // Bot erkannt
    header("Location: index.php?error=" . urlencode("Deine Eingabe wurde als Spam erkannt."));
    exit;
}

// -------------------------------------------------------------
// Bot-Schutz: Session Rate-Limit
// -------------------------------------------------------------
$maxSubmits = 3;
$windowSeconds = 300;

if (!isset($_SESSION['submit_times'])) {
    $_SESSION['submit_times'] = [];
}

// Alte Zeitstempel entfernen
$_SESSION['submit_times'] = array_filter(
    $_SESSION['submit_times'],
    fn($ts) => $ts > time() - $windowSeconds
);

if (count($_SESSION['submit_times']) >= $maxSubmits) {
    header("Location: index.php?error=" . urlencode("Bitte warte einen Moment, bevor du erneut etwas einträgst."));
    exit;
}



// -------------------------------------------------------------
// Verarbeitung der Formulardaten aus index.php
// -------------------------------------------------------------

// Eingaben bereinigen
$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$domain  = trim($_POST['domain'] ?? '');
$message = trim($_POST['message'] ?? '');

// -------------------------------------------------------------
// Validierung der Benutzereingaben
// -------------------------------------------------------------
$errors = [];

// Pflichtfelder prüfen (dynamisch mit Trim)
$requiredFields = [
    'Name'    => $name,
    'E-Mail'  => $email,
    'Nachricht' => $message
];

foreach ($requiredFields as $label => $value) {
    if (trim($value) === '') {
        $errors[] = "Bitte fülle das Feld „{$label}“ aus.";
    }
}

// E-Mail prüfen
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Bitte gib eine gültige E-Mail-Adresse an.';
}

// Domain prüfen (wenn angegeben)
if ($domain !== '' && !filter_var($domain, FILTER_VALIDATE_URL)) {
    $errors[] = 'Bitte gib eine gültige URL an oder lass das Feld leer.';
}

// -------------------------------------------------------------
// Bei Fehlern zurück zur Startseite mit Fehlermeldung
// -------------------------------------------------------------
if (!empty($errors)) {
    $errorString = urlencode(implode(' ', $errors));
    header("Location: index.php?error={$errorString}");
    exit;
}

// -------------------------------------------------------------
// Daten speichern & Weiterleitung bei Erfolg
// -------------------------------------------------------------
save_entry($name, $email, $domain, $message);
$_SESSION['submit_times'][] = time();
header("Location: index.php?success=1");
exit;
