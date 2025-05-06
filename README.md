# 📘 Gästebuch (Dateibasierte Lösung)

Ein elegantes, sicheres und responsives Gästebuch, entwickelt im Rahmen der Webmasters-Europe Abschlussprüfung – vollständig ohne Datenbank.

---

## ✅ Features

- ✍️ Einträge mit Name, E-Mail, Webseite (optional) und Nachricht
- 📄 Speicherung als JSON-Zeilen in `entries.txt`
- 🧠 Sicherheitsfunktionen:
  - Honeypot-Feld gegen Bots
  - Session-basiertes Rate-Limit
  - HTML-Escaping aller Eingaben
- 📬 E-Mail-Adressen werden maskiert (z. B. ma..@..ple.de)
- 🌍 Verlinkte Webseiten mit Domain-Anzeige
- 🖥️ Responsives Design mit Popover-Formular und Toast-Meldungen
- 🔢 Dynamische Eintragszählung
- 🧰 Sauber strukturierter, wartbarer Code (OOP-nah)

---

## 🚀 So funktioniert’s

1. Projekt in eine PHP-Umgebung legen (z. B. XAMPP, MAMP, Laravel Valet)
2. Im Browser öffnen (z. B. `http://localhost/guestbook/`)
3. Formular ausfüllen – Eintrag erscheint sofort
4. Die Datei `data/entries.txt` wird automatisch erstellt und genutzt

---

## 🔐 Sicherheit

- Keine externen Dienste oder Cookies
- Keine Client-Skripte notwendig
- Keine Datenbank – DSGVO-konform und lokal
- Alle Eingaben werden `htmlspecialchars()`-gesichert
- Schutz vor Bots und Missbrauch

---

## 📁 Projektstruktur

```text
guestbook/
├── assets/                  # Statische Assets (CSS, JS)
│   ├── base.css             # Reset & Basis-Styling
│   ├── script.js            # JS für Toast-Handling
│   └── style.css            # Hauptlayout & Komponenten
│
├── data/                    # Persistente Daten
│   └── entries.txt          # Einträge als JSON-Zeilen
│
├── functions.php            # Hilfsfunktionen (Escaping, Maskierung etc.)
├── config.php               # Sprach- & Textkonfiguration
├── index.php                # Hauptseite mit Anzeige & Formular
├── submit.php               # Validierung & Speicherung der Einträge
├── README.md                # Dokumentation des Projekts
└── .htaccess (optional)     # Für lokale Server oder Rewrite-Regeln
```

---

## 📈 Zukunftsausblick (Optionale Erweiterungen)

- 📬 Double-Opt-In für E-Mail (Datenschutzfreundlich)
- 🧾 Druckfreundliche Ansicht (via `@media print`)
- 🔍 Eintragsfilterung oder Suchfunktion
- 🧼 Auto-Cleanup für alte Einträge (z. B. älter als X Tage)
- 🧩 Exportfunktion als CSV oder JSON
- 🌐 Admin-Modus zum Löschen/Verwalten von Einträgen
- 🌙 Dark-Mode oder Farbschema-Umschaltung

---

## 👨‍💻 Entwickler

Jan P. Behrens  
Webmasters Europe – Abschlussprüfung 2025  
Lizenzfrei & lokal einsetzbar

---
