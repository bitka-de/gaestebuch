<?php

/**
 * Gibt ein Datum im Format "28. April 2025" zurück.
 *
 * @param string $isoDate ISO 8601 Datum (z. B. "2025-04-28" oder "2025-04-28 12:00:00")
 * @return string Formatiertes Datum oder leerer String bei Fehler
 */
function formatDate(string $isoDate): string
{
    $date = DateTime::createFromFormat('Y-m-d H:i:s', $isoDate)
        ?: DateTime::createFromFormat('Y-m-d', $isoDate);

    if (!$date) {
        return '';
    }

    $formatter = new IntlDateFormatter(
        'de_DE',
        IntlDateFormatter::LONG,
        IntlDateFormatter::NONE
    );

    return $formatter->format($date);
}


/**
 * Maskiert eine E-Mail-Adresse (z. B. ma..@..ple.de).
 *
 * @param string $email
 * @return string
 */
function maskEmail(string $email): string
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return '';
    }

    [$local, $domainFull] = explode('@', $email);
    $lastDotPos = strrpos($domainFull, '.');

    if ($lastDotPos === false) {
        return '';
    }

    $domainName = substr($domainFull, 0, $lastDotPos);
    $tld = substr($domainFull, $lastDotPos);

    return substr($local, 0, 2) . '..@..' . substr($domainName, -3) . $tld;
}


/**
 * Extrahiert die Domain aus einer URL.
 *
 * @param string $url
 * @return string
 */
function extractDomain(string $url): string
{
    $parsedUrl = parse_url($url);
    return $parsedUrl['host'] ?? '';
}


/**
 * Speichert einen neuen Gästebucheintrag als JSON-Zeile.
 *
 * @param string $name
 * @param string $email
 * @param string|null $domain
 * @param string $message
 * @return void
 */
function save_entry(string $name, string $email, ?string $domain, string $message): void
{
    $entry = [
        'date'    => date("Y-m-d H:i:s"),
        'name'    => htmlspecialchars($name),
        'email'   => htmlspecialchars($email),
        'domain'  => $domain ? htmlspecialchars($domain) : null,
        'message' => htmlspecialchars($message)
    ];

    $line = json_encode($entry) . PHP_EOL;
    $filePath = __DIR__ . '/../data/entries.txt';

    if ($file = fopen($filePath, 'a')) {
        flock($file, LOCK_EX);
        fwrite($file, $line);
        flock($file, LOCK_UN);
        fclose($file);
    }
}


/**
 * Lädt alle gespeicherten Gästebucheinträge.
 *
 * @return array
 */
function load_entries(): array
{
    $filePath = __DIR__ . '/../data/entries.txt';
    $entries = [];

    if (!file_exists($filePath)) {
        return get_default_entries();
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $data = json_decode($line, true);
        if (is_array($data)) {
            $entries[] = [
                'title'     => 'Eintrag vom ' . date('d.m.Y', strtotime($data['date'] ?? '')),
                'iso_date'  => $data['date'] ?? '',
                'author'    => $data['name'] ?? '',
                'email'     => $data['email'] ?? '',
                'homepage'  => $data['domain'] ?? '',
                'content'   => $data['message'] ?? '',
            ];
        }
    }

    return array_reverse($entries ?: get_default_entries());
}


/**
 * Gibt drei statische Standardeinträge zurück.
 *
 * @return array
 */
function get_default_entries(): array
{
    return [
        [
            'title'     => 'Willkommen im Gästebuch',
            'iso_date'  => '2025-04-19',
            'author'    => 'Admin',
            'email'     => 'admin@example.com',
            'homepage'  => 'https://example.com',
            'content'   => 'Dies ist ein Beispiel-Eintrag.',
        ],
        [
            'title'     => 'Schön, dass du da bist!',
            'iso_date'  => '2025-04-18',
            'author'    => 'Gast 1',
            'email'     => 'gast1@example.com',
            'homepage'  => 'https://gastseite.de',
            'content'   => 'Ich wollte einfach mal Hallo sagen!',
        ],
        [
            'title'     => 'Super Seite!',
            'iso_date'  => '2025-04-17',
            'author'    => 'Gast 2',
            'email'     => 'gast2@example.com',
            'homepage'  => '',
            'content'   => 'Tolles Gästebuch, gefällt mir sehr gut.',
        ]
    ];
}
