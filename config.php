<?php

// ** Sprach- und Metadaten für die Seite **
// Die folgenden Variablen definieren grundlegende Informationen und Einstellungen für die Seite:

$lang = 'de';  // Sprache der Seite (Deutsch)
$title = 'Gästebuch - Webmasters-Europe';  // Titel der Seite
$creator = 'Jan P. Behrens';  // Ersteller der Seite
$name = 'Gästebuch';  // Name des Gästebuchs
$new_entry = 'Neuer Eintrag';  // Text für neuen Eintrag im Gästebuch


// ** Gästebuch Einträge **
// Die folgende Struktur wird für jeden Gästebucheintrag verwendet. Jeder Eintrag enthält:
// - 'title' => (string): Titel des Eintrags
// - 'content' => (string): Inhalt des Eintrags
// - 'author' => (string): Name des Autors
// - 'email' => (string): E-Mail des Autors
// - 'homepage' => (string, optional): URL der Homepage des Autors
// - 'iso_date' => (string): Datum des Eintrags im ISO 8601 Format

$guestbook_entries = [
    [
        'title' => 'Tolles Gästebuch!',
        'content' => 'Ich bin begeistert von eurem schönen Gästebuch. Macht weiter so!',
        'author' => 'Anna Müller',
        'email' => 'anna.mueller@example.com',
        'homepage' => 'https://annaswelt.de',
        'iso_date' => '2025-04-27',
    ],
    [
        'title' => 'Viele Grüße',
        'content' => 'Liebe Grüße aus dem hohen Norden. Die Seite gefällt mir richtig gut!',
        'author' => 'Jonas Schmidt',
        'email' => 'jonas.schmidt@example.com',
        'homepage' => 'https://jonasblog.net',
        'iso_date' => '2025-04-26',
    ],
    [
        'title' => 'Super Auftritt!',
        'content' => 'Tolle Arbeit! Die Gestaltung und Inhalte sind wirklich beeindruckend.',
        'author' => 'Laura Becker',
        'email' => 'laura.becker@example.com',
        'homepage' => 'https://laurabecker.de',
        'iso_date' => '2025-04-25',
    ]
];
