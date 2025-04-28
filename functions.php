<?php

/**
 * Formatierung eines Datums im ISO 8601-Format (Y-m-d) zu einem lokalisierten Datum.
 *
 * @param string $isoDate Das Datum im ISO-Format (z.B. "2025-04-28").
 * 
 * @return string Das formatierte Datum im lokalen Format (z.B. "28. April 2025") oder ein leerer String, wenn das Datum ungültig ist.
 */
function formatDate(string $isoDate): string
{
    // Erstellen eines DateTime-Objekts aus dem ISO 8601 Datum
    $date = DateTime::createFromFormat('Y-m-d', $isoDate);

    // Überprüfen, ob das Datum korrekt geparsed werden konnte
    if (!$date) {
        return ''; // Wenn das Datum ungültig ist, wird ein leerer String zurückgegeben
    }

    // Erstellen eines Formatierers für das lokale Datumsformat (Deutsch, lange Darstellung)
    $formatter = new IntlDateFormatter(
        'de_DE',               // Sprach-/Ländereinstellung (Deutsch)
        IntlDateFormatter::LONG, // Datumslänge (z.B. "28. April 2025")
        IntlDateFormatter::NONE  // Keine Zeitangabe
    );

    // Formatieren und Zurückgeben des Datums
    return $formatter->format($date);
}


/**
 * Maskiert eine E-Mail-Adresse, indem nur der erste Teil der Adresse und die letzten 3 Buchstaben der Domain sichtbar bleiben.
 * 
 * @param string $email Die zu maskierende E-Mail-Adresse.
 * 
 * @return string Die maskierte E-Mail-Adresse oder ein leerer String bei ungültiger E-Mail.
 */
function maskEmail(string $email): string
{
    // Überprüfen, ob die E-Mail-Adresse gültig ist
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ''; // Ungültige E-Mail-Adresse, daher leer zurückgeben
    }

    // E-Mail-Adresse in lokalen Teil und Domain aufteilen
    list($local, $domainFull) = explode('@', $email);

    // Aufteilen der Domain in Domainname und TLD (Top-Level-Domain)
    $lastDotPos = strrpos($domainFull, '.');
    if ($lastDotPos === false) {
        return ''; // Keine gültige Domain gefunden
    }

    $domainName = substr($domainFull, 0, $lastDotPos); // Domainname ohne TLD
    $tld = substr($domainFull, $lastDotPos); // TLD mit Punkt (z.B. ".de")

    // Erste 2 Zeichen des lokalen Teils der E-Mail
    $localPart = substr($local, 0, 2);

    // Letzte 3 Zeichen des Domainnamens vor der TLD
    $domainPart = substr($domainName, -3);

    // Maskierte E-Mail-Adresse zurückgeben
    return $localPart . '..@..' . $domainPart . $tld;
}


/**
 * Extrahiert die Domain aus einer URL.
 * 
 * @param string $url Die URL, aus der die Domain extrahiert werden soll.
 * 
 * @return string Die extrahierte Domain oder ein leerer String, wenn keine Domain gefunden wurde.
 */
function extractDomain(string $url): string
{
    // URL parsen und die 'host'-Komponente extrahieren
    $parsedUrl = parse_url($url);

    // Rückgabe der Domain (host), falls vorhanden, ansonsten ein leerer String
    return $parsedUrl['host'] ?? ''; // Wenn 'host' nicht existiert, wird ein leerer String zurückgegeben
}
