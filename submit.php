<?php
/**
 * Verarbeitet die Gästebucheinträge aus dem Formular (index.php).
 * 
 * Validiert Benutzereingaben (Name, E-Mail, Domain, Nachricht) und speichert sie,
 * wenn sie korrekt sind. Bei Fehlern erfolgt eine Weiterleitung mit Meldung.
 */

require_once 'functions.php';

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

header("Location: index.php?success=1");
exit;
