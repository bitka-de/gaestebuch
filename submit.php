<?php
require_once 'functions.php';

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$domain = trim($_POST['domain'] ?? '');
$message = trim($_POST['message'] ?? '');

// Basisvalidierung
$errors = [];

if ($name === '' || $email === '' || $message === '') {
    $errors[] = 'Bitte fülle alle Pflichtfelder aus.';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Bitte gib eine gültige E-Mail-Adresse an.';
}

if ($domain !== '' && !filter_var($domain, FILTER_VALIDATE_URL)) {
    $errors[] = 'Bitte gib eine gültige URL an oder lass das Feld leer.';
}

if (!empty($errors)) {
    // Fehlermeldungen als Session oder Query-String zurückgeben
    $errorString = urlencode(implode(' ', $errors));
    header("Location: index.php?error=$errorString");
    exit;
}

// Speichern
save_entry($name, $email, $domain, $message);

header("Location: index.php?success=1");
exit;
