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

// Wenn das Limit erreicht wurde, abbrechen
if (count($_SESSION['submit_times']) >= $maxSubmits) {
    header("Location: index.php?error=" . urlencode("Bitte warte einen Moment, bevor du erneut etwas einträgst."));
    exit;
}

// -------------------------------------------------------------
// Verarbeitung der Formulardaten
// -------------------------------------------------------------
extract(array_map('trim', $_POST), EXTR_PREFIX_ALL, 'form');
$name    = $form_name ?? '';
$email   = $form_email ?? '';
$domain  = $form_domain ?? '';
$message = $form_message ?? '';

// -------------------------------------------------------------
// Validierung
// -------------------------------------------------------------
$errors = [];

$requiredFields = compact('name', 'email', 'message');
$requiredFields = array_combine(['Name', 'E-Mail', 'Nachricht'], $requiredFields);

$errors = array_map(
    fn($label, $value) => trim($value) === '' ? "Bitte fülle das Feld „{$label}“ aus." : null,
    array_keys($requiredFields),
    $requiredFields
);

$errors = array_filter($errors);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Bitte gib eine gültige E-Mail-Adresse an.';
}

if ($domain !== '' && !filter_var($domain, FILTER_VALIDATE_URL)) {
    $errors[] = 'Bitte gib eine gültige URL an oder lass das Feld leer.';
}

if (!empty($errors)) {
    $errorString = urlencode(implode(' ', $errors));
    header("Location: index.php?error={$errorString}");
    exit;
}

// -------------------------------------------------------------
// Daten escapen & speichern
// -------------------------------------------------------------
[$nameEsc, $emailEsc, $domainEsc, $messageEsc] = array_map(
    fn($value) => htmlspecialchars($value, ENT_QUOTES, 'UTF-8'),
    [$name, $email, $domain !== '' ? $domain : '', $message]
);

save_entry($nameEsc, $emailEsc, $domainEsc, $messageEsc);

// Timestamp für Rate-Limit speichern
$_SESSION['submit_times'][] = time();

// Erfolgreiche Weiterleitung
header("Location: index.php?success=1");
exit;
