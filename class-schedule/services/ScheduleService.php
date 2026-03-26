<?php

namespace humhub\modules\class_schedule\services;

use DateTime;
use humhub\modules\class_schedule\models\SchoolYear;
use humhub\modules\class_schedule\models\Holiday;

class ScheduleService
{
    /**
     * 1. Findet das aktive Schuljahr für ein bestimmtes Datum
     */
    public static function getSchoolYearByDate(DateTime $date)
    {
        return SchoolYear::find()
            ->where(['<=', 'start_date', $date->format('Y-m-d')])
            ->andWhere(['>=', 'end_date', $date->format('Y-m-d')])
            ->one();
    }

    /**
     * 2. Prüft, ob ein bestimmtes Datum in die Ferien fällt
     */
    public static function isDateInHoliday(DateTime $date, $schoolYearId = null)
    {
        $query = Holiday::find()
            ->where(['<=', 'start_date', $date->format('Y-m-d')])
            ->andWhere(['>=', 'end_date', $date->format('Y-m-d')]);

        if ($schoolYearId) {
            $query->andWhere(['school_year_id' => $schoolYearId]);
        }

        return $query->exists(); // Gibt true zurück, wenn es einen Treffer gibt
    }

    /**
     * 3. Berechnet die aktuelle Schulwoche (Zähler ohne Ferienwochen)
     */
    public static function getSchoolWeekNumber(DateTime $targetDate)
    {
        $schoolYear = self::getSchoolYearByDate($targetDate);

        if (!$schoolYear) {
            return null; // Das Datum liegt komplett ausserhalb eines angelegten Schuljahres
        }

        // Wir starten beim ersten Tag des Schuljahres
        $currentDate = new DateTime($schoolYear->start_date);
        
        // Um sauber in ganzen Wochen zu rechnen, setzen wir Start- und Zieldatum 
        // intern immer auf den Montag der jeweiligen Woche.
        $currentDate->modify('Monday this week');
        
        $endDate = clone $targetDate;
        $endDate->modify('Monday this week');

        $schoolWeekCounter = 0;

        // Wir "hüpfen" Woche für Woche nach vorne, bis wir beim Zieldatum ankommen
        while ($currentDate <= $endDate) {
            
            // Ein pragmatischer Trick: Wir prüfen, ob der MITTWOCH dieser Woche in den Ferien liegt.
            // Ist der Mittwoch ein Ferientag, werten wir die ganze Woche als Ferienwoche.
            $checkDate = clone $currentDate;
            $checkDate->modify('+2 days'); // Montag + 2 Tage = Mittwoch

            if (!self::isDateInHoliday($checkDate, $schoolYear->id)) {
                // Keine Ferienwoche? Dann zählen wir die Schulwoche hoch!
                $schoolWeekCounter++;
            }

            // Einen Sprung von 7 Tagen zur nächsten Woche machen
            $currentDate->modify('+1 week');
        }

        return $schoolWeekCounter;
    }
}