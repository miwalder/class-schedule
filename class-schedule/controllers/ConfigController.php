<?php

namespace humhub\modules\class_schedule\controllers;

use Yii;
use humhub\modules\admin\components\Controller;
use humhub\modules\class_schedule\models\SchoolYear;
use humhub\modules\class_schedule\models\Holiday; // NEU: Das Holiday Modell
use humhub\modules\class_schedule\models\LessonTime;
use humhub\modules\class_schedule\models\TimetableEntry;

class ConfigController extends Controller
{
    // ... Deine bestehende actionIndex() bleibt exakt so, wie sie war ...
    public function actionIndex()
    {
        $model = new SchoolYear();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->view->saved(); 
            return $this->redirect(['index']); 
        }

        $schoolYears = SchoolYear::find()->orderBy(['start_date' => SORT_DESC])->all();

        return $this->render('index', [
            'model' => $model,
            'schoolYears' => $schoolYears,
        ]);
    }

    /**
     * NEU: Die Ansicht für die Ferien eines bestimmten Schuljahres
     */
    public function actionHolidays($id)
    {
        // Das passende Schuljahr anhand der ID suchen
        $schoolYear = SchoolYear::findOne($id);
        if (!$schoolYear) {
            throw new \yii\web\HttpException(404, 'Schuljahr nicht gefunden!');
        }

        // Ein leeres Ferien-Modell vorbereiten und direkt mit dem Schuljahr verknüpfen
        $model = new Holiday();
        $model->school_year_id = $schoolYear->id;

        // Wenn das Formular abgeschickt wird:
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->view->saved();
            return $this->redirect(['holidays', 'id' => $schoolYear->id]);
        }

        // Alle Ferien für DIESES Schuljahr laden, chronologisch sortiert
        $holidays = Holiday::find()
            ->where(['school_year_id' => $schoolYear->id])
            ->orderBy(['start_date' => SORT_ASC])
            ->all();

        return $this->render('holidays', [
            'schoolYear' => $schoolYear,
            'model' => $model,
            'holidays' => $holidays,
        ]);
    }

    /**
     * NEU: Ferien wieder löschen (falls man sich vertippt hat)
     */
    public function actionDeleteHoliday($id)
    {
        $this->forcePostRequest(); // Sicherheitsfunktion von HumHub
        
        $holiday = Holiday::findOne($id);
        if ($holiday) {
            $schoolYearId = $holiday->school_year_id;
            $holiday->delete();
            return $this->redirect(['holidays', 'id' => $schoolYearId]);
        }
        return $this->redirect(['index']);
    }
    /**
     * NEU: Verwaltung des Zeitrasters (Lektionen)
     */
    public function actionLessonTimes()
    {
        $model = new LessonTime();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->view->saved();
            return $this->redirect(['lesson-times']);
        }

        // Alle Lektionen laden, chronologisch nach Startzeit sortiert
        $lessonTimes = LessonTime::find()->orderBy(['start_time' => SORT_ASC])->all();

        return $this->render('lesson-times', [
            'model' => $model,
            'lessonTimes' => $lessonTimes,
        ]);
    }

    /**
     * NEU: Lektion aus dem Raster löschen
     */
    public function actionDeleteLessonTime($id)
    {
        $this->forcePostRequest();
        
        $lesson = LessonTime::findOne($id);
        if ($lesson) {
            $lesson->delete();
        }
        
        return $this->redirect(['lesson-times']);
    }
    /**
     * NEU: Zeigt den visuellen Stundenplan an
     */
    public function actionTimetable()
    {
        $lessonTimes = LessonTime::find()->orderBy(['start_time' => SORT_ASC])->all();
        $entries = TimetableEntry::find()->all();

        // Wir sortieren die Einträge in ein praktisches Array, 
        // damit wir sie im HTML-Raster leicht finden: $grid[Wochentag][Lektions-ID]
        $grid = [];
        foreach ($entries as $entry) {
            $grid[$entry->weekday][$entry->lesson_time_id] = $entry;
        }

        return $this->render('timetable', [
            'lessonTimes' => $lessonTimes,
            'grid' => $grid,
        ]);
    }

    /**
     * NEU: Fach in eine Zelle des Rasters eintragen
     */
    public function actionEditEntry($weekday, $lesson_time_id)
    {
        // Prüfen, ob für diese Zelle schon ein Eintrag existiert
        $model = TimetableEntry::find()->where(['weekday' => $weekday, 'lesson_time_id' => $lesson_time_id])->one();
        
        if (!$model) {
            $model = new TimetableEntry();
            $model->weekday = $weekday;
            $model->lesson_time_id = $lesson_time_id;
            $model->color = '#3498db'; // Standardfarbe Blau
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->view->saved();
            return $this->redirect(['timetable']);
        }

        $lessonTime = LessonTime::findOne($lesson_time_id);

        return $this->render('edit-entry', [
            'model' => $model,
            'lessonTime' => $lessonTime,
        ]);
    }

    /**
     * NEU: Fach aus dem Raster löschen
     */
    public function actionDeleteEntry($id)
    {
        $this->forcePostRequest();
        
        $entry = TimetableEntry::findOne($id);
        if ($entry) {
            $entry->delete();
        }
        
        return $this->redirect(['timetable']);
    }
}