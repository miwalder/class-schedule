<?php

namespace humhub\modules\class_schedule\models;

// Das ist der "Ausweis", den der Kalender sehen will!
use humhub\modules\calendar\interfaces\event\CalendarEventIF;
use DateTime;

class LessonCalendarItem extends \yii\base\BaseObject implements CalendarEventIF
{
    public $id;
    public $title;
    public $start;
    public $end;
    public $color;
    public $timetable_entry_id; 
    public $dateString;         
    public $space;              
    public $allDay = false;

    // --- Die Pflicht-Funktionen (Getter) für den HumHub-Kalender ---

    public function getId() {
        return $this->id;
    }

    public function getUid() {
        // Eine eindeutige ID für Exporte (z.B. CalDAV)
        return 'class_schedule_' . $this->id; 
    }

    public function getTitle() {
        return $this->title;
    }

    public function getStartDateTime() {
        return $this->start;
    }

    public function getEndDateTime() {
        return $this->end;
    }

    public function getTimezone() {
        // Gibt die Standard-Zeitzone deines Servers zurück (meist 'Europe/Zurich' oder 'Europe/Berlin')
        return date_default_timezone_get();
    }

    public function isAllDay() {
        return $this->allDay; 
    }

    public function getColor() {
        return $this->color;
    }

    public function getUrl() {
        // NEU: Nur verlinken, wenn es wirklich ein Fach (timetable_entry_id) ist!
        if ($this->space && $this->timetable_entry_id) {
            return $this->space->createUrl('/class-schedule/timetable/plan', [
                'entry_id' => $this->timetable_entry_id,
                'date' => $this->dateString
            ]);
        }
        return null; // Ferien und Schulwochen sind nicht anklickbar
    }
    
    public function getDescription() {
        return '';
    }

    // --- Die folgenden 7 Methoden wurden hinzugefügt, um das CalendarEventIF zu erfüllen ---

    public function getEventType() {
        // Gibt einen Dummy-Aufruf auf den Kalendertypen zurück
        return new \humhub\modules\calendar\models\CalendarEntryType([
            'id' => 'class-schedule', 
            'name' => \Yii::t('ClassScheduleModule.base', 'Timetable'), 
            'color' => $this->color
        ]);
    }

    public function getEndTimezone() {
        return $this->getTimezone();
    }

    public function getLocation() {
        return null;
    }

    public function getBadge() {
        return null;
    }

    public function getCalendarOptions() {
        return [
            'classNames' => ['cs-hide-in-beamer', 'cs-hide-time'],
            'className' => 'cs-hide-in-beamer cs-hide-time',
            'displayEventTime' => false
        ];
    }

    public function getLastModified() {
        return null;
    }

    public function getSequence() {
        return 0;
    }
}