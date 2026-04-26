<?php

namespace humhub\modules\class_schedule;

use Yii;
use DateTime;
use DateInterval;
use DatePeriod;
use humhub\modules\class_schedule\services\ScheduleService;
use humhub\modules\class_schedule\models\TimetableEntry;
use humhub\modules\class_schedule\models\LessonCalendarItem;

class Events
{
    /**
     * Wird vom HumHub-Kalender aufgerufen, wenn er Termine braucht.
     */
    public static function onGetCalendarItems($event)
    {
        $start = clone $event->start;
        $end = clone $event->end;
        $container = $event->contentContainer;

        // --- NEU: Der Türsteher ---
        // 1. Wenn der Container ein Benutzerprofil ist, haben wir hier nichts zu suchen!
        if ($container instanceof \humhub\modules\user\models\User) {
            return;
        }

        // 2. Wenn es ein Space ist, prüfen wir sauber, ob das Modul aktiviert ist
        if ($container instanceof \humhub\modules\space\models\Space && !$container->isModuleEnabled('class-schedule')) {
            return;
        }
        // --------------------------

        if ($container === null || !$container->isModuleEnabled('class-schedule')) {
            return; 
        }

        $query = \humhub\modules\class_schedule\models\TimetableEntry::find()->with('lessonTime');
        $query->andWhere(['contentcontainer_id' => $container->id]);

        $timetableEntries = $query->all();

        // NEU: Wir laden alle Schuljahre, um ihre Start- und Enddaten zu kennen
        $schoolYears = \humhub\modules\class_schedule\models\SchoolYear::find()->all();
        
        // Berechtigungen prüfen
        $module = \Yii::$app->getModule('class-schedule');
        $teacherGroupId = $module->settings->space($container)->get('teacher_group_id');
        $canSeeLessons = true;

        if ($teacherGroupId && !\Yii::$app->user->isGuest) {
            $userId = \Yii::$app->user->id;
            $isTeacher = \humhub\modules\user\models\GroupUser::find()->where(['user_id' => $userId, 'group_id' => $teacherGroupId])->exists();
            $isSpaceAdmin = $container->isAdmin($userId);
            $isGlobalAdmin = \Yii::$app->user->isAdmin();

            if (!$isTeacher && !$isSpaceAdmin && !$isGlobalAdmin) {
                $canSeeLessons = false;
            }
        } elseif ($teacherGroupId && \Yii::$app->user->isGuest) {
            $canSeeLessons = false;
        }
        // NEU: Fächer nach Schuljahr UND Wochentag gruppieren
        $entriesByYearAndDay = [];
        foreach ($timetableEntries as $entry) {
            if ($entry->school_year_id) {
                $entriesByYearAndDay[$entry->school_year_id][$entry->weekday][] = $entry;
            }
        }

        $calendarItems = [];
        $addedWeeks = [];
        $holidayStart = null;
        $holidayEnd = null;
        $currentHolidayName = null;

        $closeHolidayBlock = function($hStart, $hEnd, $hName) use (&$calendarItems) {
            if ($hStart !== null) {
                $item = new \humhub\modules\class_schedule\models\LessonCalendarItem();
                $item->id = 'holiday_' . $hStart->format('Y-m-d');
                $item->title = $hName ? '🏖️ ' . $hName : Yii::t('ClassScheduleModule.base', '🏖️ Holidays');
                $item->color = '#2ecc71'; 
                
                $item->start = clone $hStart;
                $item->start->setTime(0, 0, 0);
                
                $item->end = clone $hEnd;
                $item->end->modify('+1 day')->setTime(0, 0, 0); 
                
                $item->allDay = true;
                $calendarItems[] = $item;
            }
        };

        $period = new \DatePeriod($start, new \DateInterval('P1D'), clone $end->modify('+1 day'));

        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');
            $weekday = (int)$date->format('N');

            // --- NEU: Welches Schuljahr ist an diesem Tag aktiv? ---
            $activeSchoolYearId = null;
            foreach ($schoolYears as $sy) {
                // HIER ANPASSEN, falls deine Datenbank-Spalten anders heißen!
                if ($dateString >= $sy->start_date && $dateString <= $sy->end_date) {
                    $activeSchoolYearId = $sy->id;
                    break;
                }
            }

            // 1. Ferien-Logik
            $holiday = ScheduleService::getHolidayByDate($date);
            if ($holiday !== null) {
                if ($holidayStart === null) {
                    $holidayStart = clone $date;
                    $currentHolidayName = $holiday->name;
                } elseif ($currentHolidayName !== $holiday->name) {
                    $closeHolidayBlock($holidayStart, $holidayEnd, $currentHolidayName);
                    $holidayStart = clone $date;
                    $currentHolidayName = $holiday->name;
                }
                $holidayEnd = clone $date;
                continue; 
            } else {
                if ($holidayStart !== null) {
                    $closeHolidayBlock($holidayStart, $holidayEnd, $currentHolidayName);
                    $holidayStart = null;
                    $currentHolidayName = null;
                }
            }

            // 2. Schulwochen-Logik
            $weekNumber = ScheduleService::getSchoolWeekNumber($date);
            if ($weekNumber !== null && !isset($addedWeeks[$weekNumber])) {
                $addedWeeks[$weekNumber] = true;
                
                $monday = clone $date;
                $monday->modify('Monday this week')->setTime(0, 0, 0);
                
                $friday = clone $date;
                $friday->modify('Friday this week')->setTime(0, 0, 0);

                $weekItem = new \humhub\modules\class_schedule\models\LessonCalendarItem();
                $weekItem->id = 'week_' . $weekNumber . '_' . $monday->format('Y');
                $weekItem->title = Yii::t('ClassScheduleModule.base', 'SW') . ': ' . $weekNumber . ' / ' . Yii::t('ClassScheduleModule.base', 'CW') . ': ' . $date->format('W');
                $weekItem->color = '#95a5a6'; 
                
                $weekItem->start = clone $monday;
                $weekItem->end = clone $friday;
                $weekItem->end->modify('+1 day')->setTime(0, 0, 0);
                
                $weekItem->allDay = true;
                $calendarItems[] = $weekItem;
            }

            // 3. Wochenende überspringen
            if ($weekday > 5) continue;

            // 4. Gibt es Fächer an diesem Tag für das AKTIVE SCHULJAHR?
            // Wenn der Tag in keinem definierten Schuljahr liegt, zeichnen wir keinen Unterricht.
            if (!$canSeeLessons) {
                continue;
            }

            // NEU: Serverseitiger Beamer-Modus Filter (Nur Fächer/Lektionen überspringen)
            if (\Yii::$app->session->get('cs_beamer_mode', false)) {
                continue;
            }

            if (!$activeSchoolYearId || !isset($entriesByYearAndDay[$activeSchoolYearId][$weekday])) {
                continue;
            }

            // 5. Die einzelnen Lektionen des richtigen Schuljahres eintragen
            foreach ($entriesByYearAndDay[$activeSchoolYearId][$weekday] as $entry) {
                $item = new \humhub\modules\class_schedule\models\LessonCalendarItem();
                $item->id = 'lesson_' . $dateString . '_' . $entry->id;
                $item->title = $entry->subject; 
                $item->color = $entry->color;

                $item->start = new \DateTime($dateString . ' ' . $entry->lessonTime->start_time);
                $item->end = new \DateTime($dateString . ' ' . $entry->lessonTime->end_time);

                $item->timetable_entry_id = $entry->id;
                $item->dateString = $dateString;
                $item->space = $container;

                $calendarItems[] = $item;
            }
        }

        if ($holidayStart !== null) {
            $closeHolidayBlock($holidayStart, $holidayEnd ?? $holidayStart, $currentHolidayName);
        }

        $event->addItems('class-schedule', $calendarItems);
    }

    /**
     * Fügt den Button in das Space-Menü ein
     */
    public static function onSpaceAdminMenuInit($event)
    {
        // Wir holen uns den Space direkt über den Controller, das funktioniert immer!
        $space = \Yii::$app->controller->contentContainer;

        if (!$space instanceof \humhub\modules\space\models\Space) {
            return;
        }

        if ($space->isModuleEnabled('class-schedule')) {
            $event->sender->addItem([
                'label' => Yii::t('ClassScheduleModule.base', 'Class Schedule'),
                'url' => $space->createUrl('/class-schedule/timetable/index'),
                'icon' => '<i class="fa fa-chalkboard-user"></i>',
                'isActive' => (\Yii::$app->controller->module && \Yii::$app->controller->module->id == 'class-schedule'),
                'sortOrder' => 300,
            ]);
        }
    }

    /**
     * Fügt den schwebenden Beamer-Button auf Kalender-Seiten hinzu
     */
    public static function onSpaceHeaderInit($event)
    {
        $space = $event->sender->space;

        if (!$space || !$space->isModuleEnabled('class-schedule')) {
            return;
        }

        // Berechtigungen prüfen: Nur definierte Lehrpersonen (und Admins) dürfen den Beamer-Modus sehen
        $module = \Yii::$app->getModule('class-schedule');
        $teacherGroupId = $module->settings->space($space)->get('teacher_group_id');
        $canSeeButton = true;

        if ($teacherGroupId && !\Yii::$app->user->isGuest) {
            $userId = \Yii::$app->user->id;
            $isTeacher = \humhub\modules\user\models\GroupUser::find()->where(['user_id' => $userId, 'group_id' => $teacherGroupId])->exists();
            $isSpaceAdmin = $space->isAdmin($userId);
            $isGlobalAdmin = \Yii::$app->user->isAdmin();

            if (!$isTeacher && !$isSpaceAdmin && !$isGlobalAdmin) {
                $canSeeButton = false;
            }
        } elseif ($teacherGroupId && \Yii::$app->user->isGuest) {
            $canSeeButton = false;
        }

        if (!$canSeeButton) {
            return;
        }

        $currentState = \Yii::$app->session->get('cs_beamer_mode', false);
        $btnBg = $currentState ? '#e74c3c' : '#2c3e50';
        $btnIcon = $currentState ? 'fa-eye' : 'fa-video-camera';
        $btnText = $currentState ? Yii::t('ClassScheduleModule.base', 'Show lessons') : Yii::t('ClassScheduleModule.base', 'Beamer Mode');
        $toggleUrl = $space->createUrl('/class-schedule/timetable/toggle-beamer');

        $js = <<<JS
        $(document).on('humhub:ready', function() {
            // Den Button nur anzeigen, wenn das Wort "calendar" in der URL vorkommt
            if (window.location.href.indexOf('calendar') > -1) {
                if ($('#beamer-mode-btn').length === 0) {
                    var btn = $('<div id="beamer-mode-btn" style="position:fixed; bottom:20px; left:20px; z-index:9999; background:{$btnBg}; color:white; padding:12px 20px; border-radius:30px; cursor:pointer; box-shadow:0 4px 6px rgba(0,0,0,0.3); font-weight:bold; transition:all 0.3s;"><i class="fa {$btnIcon}"></i> {$btnText}</div>');
                    $('body').append(btn);

                    btn.on('click', function() {
                        $.ajax({
                            url: '{$toggleUrl}',
                            type: 'POST',
                            success: function(response) {
                                if (response.success) {
                                    // Seite neu laden, damit der Kalender die Termine serverseitig neu abruft
                                    location.reload();
                                }
                            }
                        });
                    });
                }
            } else {
                // Außerhalb des Kalenders aufräumen
                $('#beamer-mode-btn').remove();
            }
        });
JS;
        \Yii::$app->view->registerJs($js, \yii\web\View::POS_END, 'beamer-modus-js');
    }

    /**
     * NEU: Filter-Checkboxen in der Seitenleiste des Kalenders
     */
    public static function onGetCalendarItemTypes($event)
    {
        $event->addType('class-schedule', [
            'title' => Yii::t('ClassScheduleModule.base', 'Class Schedule'),
            'color' => '#3498db', // Muss zur Farbe in der DB passen
        ]);
    }

    public static function onLayoutAddonsInit($event)
    {
        // This fully mutes the HumHub AM/PM native time text appended onto our specifically targeted custom items!
        \Yii::$app->view->registerCss('.cs-hide-time .fc-time { display: none !important; }');
    }
}