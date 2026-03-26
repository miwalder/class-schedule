# HumHub Class Schedule (Stundenplan) 🏫

*A comprehensive timetable and lesson planning module for HumHub. Features include global school year management, space-level class schedules, granular visibility permissions for teacher teams, and a safe presentation mode.*

Das **Class Schedule** Modul verwandelt HumHub in eine vollwertige, digitale Planungsplattform für Schulen und Bildungseinrichtungen. Es ermöglicht Lehrkräften, Stundenpläne direkt in den Klassenzimmer-Spaces zu verwalten, den Unterricht im Team kollaborativ vorzubereiten und Termine nahtlos mit dem HumHub-Kalender zu synchronisieren.

---

## ✨ Hauptfunktionen

* **Globale Strukturdaten:** Zentrale Verwaltung von Unterrichtszeiten (Raster), Schuljahren und Ferienblöcken durch den Netzwerk-Administrator.
* **Space-spezifische Stundenpläne:** Jede Klasse (bzw. jeder Space) erhält einen eigenen Stundenplan.
* **Kollaborative Unterrichtsvorbereitung:** Lehrpersonen können zu jeder Lektion tagesaktuelle Planungen, Notizen und Hausaufgaben hinterlegen.
* **Smarte Kalender-Integration:** Das Modul integriert sich nativ in das HumHub `calendar` Modul. Es berechnet automatisch die aktuelle Schulwoche (SW) sowie die Kalenderwoche (KW) und trägt Ferien als Ganztages-Events ein.
* **Granulare Sichtbarkeiten:** Über die Space-Einstellungen lässt sich genau definieren, wer die Unterrichtsplanung sehen darf (z. B. nur die Gruppe "Lehrpersonen"). Schüler sehen im Kalender weiterhin die Ferien und Schulwochen, aber keine internen Lehrer-Notizen.
* **📽️ Beamer-Modus:** Ein innovativer, serverseitiger Präsentationsmodus. Mit einem Klick auf den schwebenden Button im Kalender werden alle Unterrichtsfächer sofort ausgeblendet. Der Kalender kann so sicher an die Wand projiziert werden, ohne sensible Vorbereitungsdaten zu leaken.

---

## ⚙️ Installation

1. Lade das Modul herunter oder klone das Repository.
2. Verschiebe den Ordner `class-schedule` in das Modul-Verzeichnis deiner HumHub-Installation:  
   `protected/modules/class-schedule`
3. Logge dich als Administrator in HumHub ein.
4. Navigiere zu **Administration -> Module**.
5. Klicke beim Modul "Class Schedule" auf **Installieren** und anschließend auf **Aktivieren**.

---

## 🚀 Erste Schritte (Quick Start)

### 1. Globale Einrichtung (Admin)
Bevor das Modul in den Spaces genutzt werden kann, müssen die globalen Rahmenbedingungen geschaffen werden:
* Gehe zu **Administration -> Stundenplan**.
* Lege mindestens ein **Schuljahr** an (Start- und Enddatum).
* Definiere die **Unterrichtszeiten** (z. B. 1. Lektion: 07:30 - 08:15).
* (Optional) Trage globale **Ferien** ein.

### 2. Space-Einrichtung (Lehrkraft / Space-Admin)
* Gehe in den gewünschten Space (z. B. "Klasse 8b").
* Aktiviere das Modul unter **Space -> Module**.
* Klicke im Stundenplan oben rechts auf das **Zahnrad-Symbol** (Konfigurieren). Wähle hier die Benutzergruppe aus, die berechtigt ist, den Unterricht im Kalender zu sehen.

### 3. Stundenplan befüllen
* Wähle im Dropdown das aktuelle Schuljahr aus.
* Klicke in den leeren Feldern auf **Fach eintragen** und definiere Fachname und Farbe.

---

## 💡 Der Beamer-Modus
Wenn du den Space-Kalender aufrufst, erscheint unten links ein schwebender Button (**Beamer: AUS**). 
Ein Klick darauf aktiviert den Modus für die aktuelle Sitzung. Das Modul blockiert nun serverseitig die Übertragung aller Unterrichts-Lektionen an den Kalender. Du kannst den Kalender nun bedenkenlos über einen Beamer mit der Klasse teilen. Ein weiterer Klick deaktiviert den Modus wieder.

---

## 🛠️ Systemvoraussetzungen
* HumHub Version 1.15 oder höher
* Aktiviertes offizielles HumHub `calendar` Modul
* PHP 8.0 oder höher

---

## 📝 Lizenz
Dieses Modul ist Open-Source und steht unter der [General Public License]
