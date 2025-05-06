<?php
// Jan P. Behrens | Webmasters-Europe
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

// Gästebucheinträge laden
$entries = load_entries();
?>

<!DOCTYPE html>
<html lang="<?= $lang ?? 'de'; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Mein Gästebuch'); ?></title>
    <link rel="stylesheet" href="./assets/style.css">
</head>

<body class="gb">

    <!-- Erfolgs- oder Fehlermeldung als Toast anzeigen -->
    <?php if (!empty($_GET['success']) || !empty($_GET['error'])): ?>
        <?php 
            $toastType = !empty($_GET['success']) ? 'success' : 'error';
            $toastMessage = !empty($_GET['success']) 
                ? 'Vielen Dank! Dein Eintrag wurde gespeichert.' 
                : htmlspecialchars($_GET['error']);
        ?>
        <div id="toast" class="toast <?= $toastType ?>">
            <span><?= $toastMessage ?></span>
            <button onclick="closeToast()">×</button>
        </div>
    <?php endif; ?>

    <!-- Kopfbereich mit Logo und Button -->
    <header class="gb-header">
        <section class="boxed flex-between">
            <h1 class="gb-header__logo">
                <svg viewBox="0 0 256 256">
                    <path d="M166 128a6 6 0 0 1-6 6H96a6 6 0 0 1 0-12h64a6 6 0 0 1 6 6Zm-6 26H96a6 6 0 0 0 0 12h64a6 6 0 0 0 0-12Zm54-114v160a30 30 0 0 1-30 30H72a30 30 0 0 1-30-30V40a6 6 0 0 1 6-6h26V24a6 6 0 0 1 12 0v10h36V24a6 6 0 0 1 12 0v10h36V24a6 6 0 0 1 12 0v10h26a6 6 0 0 1 6 6Zm-12 6h-20v10a6 6 0 0 1-12 0V46h-36v10a6 6 0 0 1-12 0V46H86v10a6 6 0 0 1-12 0V46H54v154a18 18 0 0 0 18 18h112a18 18 0 0 0 18-18Z" />
                </svg>
                <?= htmlspecialchars($name ?? 'Gästebuch'); ?>
            </h1>
            <button type="button" class="button" popovertarget="entry-popover" popovertargetaction="show">
                <svg viewBox="0 0 256 256">
                    <path d="M224 128a8 8 0 0 1-8 8h-80v80a8 8 0 0 1-16 0v-80H40a8 8 0 0 1 0-16h80V40a8 8 0 0 1 16 0v80h80a8 8 0 0 1 8 8Z" />
                </svg>
                <?= htmlspecialchars($new_entry ?? 'Neuer Eintrag'); ?>
            </button>
        </section>
    </header>

    <!-- Hauptbereich -->
    <section class="content boxed">

        <!-- Formular für neue Gästebucheinträge -->
        <div id="entry-popover" popover class="form-popover">
            <form action="submit.php" method="POST" class="guestbook-form">
                <h2>Neuer Gästebucheintrag</h2>

                <label for="name">Name</label>
                <input type="text" name="name" id="name" placeholder="Dein Name" required>

                <label for="email">E-Mail-Adresse</label>
                <input type="email" name="email" id="email" placeholder="z. B. max@example.com" required>

                <label for="domain">Webseite (optional)</label>
                <input type="url" name="domain" id="domain" placeholder="https://deine-seite.de">

                <label for="message">Nachricht</label>
                <textarea name="message" id="message" rows="5" placeholder="Deine Nachricht" required></textarea>

                <div class="form-actions">
                    <button type="button" class="button-cancel" popovertarget="entry-popover" popovertargetaction="hide">Abbrechen</button>
                    <button type="submit" class="button send">Absenden</button>
                </div>

                <div class="honeypot" style="position: absolute; left: -9999px; width: 1px; height: 1px; overflow: hidden;">
                    <label for="website">Honeypod</label>
                    <input type="text" id="website" name="website" autocomplete="off">
                </div>
            </form>
        </div>


        <!-- Ausgabe der Gästebucheinträge -->
        <section class="guestbook-entries">

            <?php if (empty($entries)): ?>
                <p>Es sind noch keine Einträge vorhanden.</p>
            <?php else: ?>
                <h2><?= count($entries) === 1 ? '1 Eintrag' : count($entries) . ' Einträge'; ?></h2>

                <?php foreach ($entries as $entry): ?>
                    <article class="gb-entry">
                        <header class="gb-entry__header">
                            <h3 class="gb-entry__title"><?= htmlspecialchars($entry['title'] ?? $entry['name'] ?? 'Kein Titel'); ?></h3>
                            <time class="gb-entry__date" datetime="<?= htmlspecialchars($entry['iso_date'] ?? $entry['date'] ?? '2025-04-19'); ?>">
                                <?= formatDate($entry['iso_date'] ?? $entry['date'] ?? '2025-04-19'); ?>
                            </time>
                        </header>
                        <p class="gb-entry__content"><?= nl2br(htmlspecialchars($entry['content'] ?? $entry['message'] ?? 'Kein Inhalt')) ?></p>
                        <section class="gb-entry__meta">
                            <span class="gb-entry__author"><?= htmlspecialchars($entry['author'] ?? $entry['name'] ?? 'Unbekannt'); ?></span> |
                            <a href="mailto:<?= htmlspecialchars($entry['email'] ?? 'nomail@example.com'); ?>" class="gb-entry__email">
                                <?= maskEmail($entry['email'] ?? 'nomail@example.com'); ?>
                            </a> |
                            <a href="<?= htmlspecialchars($entry['homepage'] ?? $entry['domain'] ?? 'https://example.com'); ?>" class="gb-entry__homepage" target="_blank" rel="noopener">
                                <?= extractDomain($entry['homepage'] ?? $entry['domain'] ?? 'https://example.com'); ?>
                            </a>
                        </section>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>


    </section>

    <!-- Footer -->
    <footer class="gb-footer">
        <section class="boxed">
            <div class="gb-footer__copyright">
                &copy; <?= date('Y'); ?> by <?= htmlspecialchars($creator ?? 'Unbekannt'); ?>
            </div>
        </section>
    </footer>

    <!-- JS zur Toaststeuerung -->
    <script src="/assets/script.js" defer></script>
</body>

</html>