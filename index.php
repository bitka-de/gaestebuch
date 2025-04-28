<?php
// Jan P. Behrens | Webmasters-Europe
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
?>

<!DOCTYPE html>
<html lang="<?= $lang ?? 'en'; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'No Title'; ?></title>
    <link rel="stylesheet" href="./assets/style.css">
</head>

<body class="gb">

    <header class="gb-header">
        <section class="boxed flex-between">
            <h1 class="gb-header__logo">
                <svg viewBox="0 0 256 256">
                    <path d="M166 128a6 6 0 0 1-6 6H96a6 6 0 0 1 0-12h64a6 6 0 0 1 6 6Zm-6 26H96a6 6 0 0 0 0 12h64a6 6 0 0 0 0-12Zm54-114v160a30 30 0 0 1-30 30H72a30 30 0 0 1-30-30V40a6 6 0 0 1 6-6h26V24a6 6 0 0 1 12 0v10h36V24a6 6 0 0 1 12 0v10h36V24a6 6 0 0 1 12 0v10h26a6 6 0 0 1 6 6Zm-12 6h-20v10a6 6 0 0 1-12 0V46h-36v10a6 6 0 0 1-12 0V46H86v10a6 6 0 0 1-12 0V46H54v154a18 18 0 0 0 18 18h112a18 18 0 0 0 18-18Z" />
                </svg>
                <?= $name ?? 'No Name'; ?>
            </h1>
            <button class="button">
                <svg viewBox="0 0 256 256">
                    <path d="M224 128a8 8 0 0 1-8 8h-80v80a8 8 0 0 1-16 0v-80H40a8 8 0 0 1 0-16h80V40a8 8 0 0 1 16 0v80h80a8 8 0 0 1 8 8Z" />
                </svg>
                <?= $new_entry; ?></button>
        </section>
    </header>

    <section class="content boxed">

        <?php foreach ($guestbook_entries as $entry): ?>

            <article class="gb-entry">
                <header class="gb-entry__header">
                    <h3 class="gb-entry__title"><?= $entry['title'] ?? 'No Title'; ?></h3>
                    <time class="gb-entry__date" datetime="<?= $entry['iso_date'] ?? '2025-04-19'; ?>"><?= formatDate($entry['iso_date']) ?? formatDate('2025-04-19'); ?></time>
                </header>
                <p class="gb-entry__content"><?= $entry['content'] ?? 'No Content'; ?></p>
                <section class="gb-entry__meta">
                    <span class="gb-entry__author"><?= $entry['author'] ?? 'No Author'; ?></span> |
                    <a href="mailto:{{Email}}" class="gb-entry__email"><?= maskEmail($entry['email']) ?? maskEmail('nomail@example.com'); ?></a> |
                    <a href="<?= $entry['homepage'] ?? 'https://example.com'; ?>" class="gb-entry__homepage" target="_blank" rel="noopener"><?= extractDomain($entry['homepage']) ?? 'example.com'; ?></a>
                </section>
            </article>

        <?php endforeach; ?>



    </section>

    <footer class="gb-footer">
        <section class="boxed">
            <div class="gb-footer__copyright">
                &copy <?= date('Y'); ?> by <?= $creator ?? 'Somebody'; ?>
            </div>
        </section>
    </footer>

</body>

</html>