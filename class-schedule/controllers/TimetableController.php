<?php

namespace humhub\modules\class_schedule\controllers;

use Yii;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\class_schedule\models\LessonTime;
use humhub\modules\class_schedule\models\TimetableEntry;
use humhub\modules\class_schedule\models\SchoolYear; // NEU: Modell für das Schuljahr laden

class TimetableController extends ContentContainerController
{
/**
     * NEU: Akzeptiert jetzt die school_year_id als Parameter (Yii2 Standard)
     */
    public function actionIndex($school_year_id = null)
    {
        // Wir nehmen den Parameter aus der URL, falls er da ist
        $school_year_id = \Yii::$app->request->get('school_year_id', $school_year_id);
        
        $lessonTimes = LessonTime::find()->orderBy('start_time')->all();
        $schoolYears = SchoolYear::find()->all(); 

        // Fallback: Wenn wirklich keine ID in der URL steht, nimm das erste Schuljahr
        if (empty($school_year_id) && !empty($schoolYears)) {
            $school_year_id = $schoolYears[0]->id;
        }

        // Fächer holen: Streng nach Space UND Schuljahr filtern
        $entries = TimetableEntry::find()
            ->where([
                'contentcontainer_id' => $this->contentContainer->id,
                'school_year_id' => $school_year_id
            ])
            ->all();

        $schedule = [];
        foreach ($entries as $entry) {
            $schedule[$entry->lesson_time_id][$entry->weekday] = $entry;
        }

        return $this->render('index', [
            'space' => $this->contentContainer,
            'lessonTimes' => $lessonTimes,
            'schedule' => $schedule,
            'schoolYears' => $schoolYears,
            'selectedSchoolYearId' => $school_year_id,
        ]);
    }
    /**
     * NEU: Die Funktion zum Bearbeiten oder Neu-Anlegen eines Faches (verlangt school_year_id)
     */
    public function actionEdit($lesson_time_id, $weekday)
    {
        // Auch hier holen wir das Schuljahr direkt aus der URL
        $school_year_id = \Yii::$app->request->get('school_year_id');

        $model = TimetableEntry::findOne([
            'contentcontainer_id' => $this->contentContainer->id,
            'lesson_time_id' => $lesson_time_id,
            'weekday' => $weekday,
            'school_year_id' => $school_year_id, 
        ]);

        if ($model === null) {
            $model = new TimetableEntry();
            $model->contentcontainer_id = $this->contentContainer->id;
            $model->lesson_time_id = $lesson_time_id;
            $model->weekday = $weekday;
            $model->school_year_id = $school_year_id; 
            $model->color = '#3498db'; 
        }

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect($this->contentContainer->createUrl('/class-schedule/timetable/index', ['school_year_id' => $school_year_id]));
        }

        return $this->render('edit', [
            'model' => $model,
            'space' => $this->contentContainer,
            'school_year_id' => $school_year_id,
        ]);
    }
    /**
     * NEU: Die Funktion zum Löschen eines Faches (mit Rückkehr ins selbe Schuljahr)
     */
    public function actionDelete($id)
    {
        // Fach suchen und sicherstellen, dass es wirklich zu diesem Space gehört!
        $model = TimetableEntry::findOne([
            'id' => $id,
            'contentcontainer_id' => $this->contentContainer->id
        ]);
        
        $school_year_id = null;

        if ($model) {
            $school_year_id = $model->school_year_id; // Schuljahr merken, bevor es gelöscht wird
            $model->delete();
        }
        
        // Wieder zurück ins richtige Schuljahr leiten
        return $this->redirect($this->contentContainer->createUrl('/class-schedule/timetable/index', ['school_year_id' => $school_year_id]));
    }

    /**
     * Die Unterrichtsplanung für eine spezifische Lektion an einem bestimmten Datum
     */
    public function actionPlan($entry_id, $date)
    {
        // 1. Das Fach im Raster finden und prüfen, ob es zu dieser Klasse gehört
        $entry = \humhub\modules\class_schedule\models\TimetableEntry::findOne([
            'id' => $entry_id,
            'contentcontainer_id' => $this->contentContainer->id
        ]);

        if (!$entry) {
            throw new \yii\web\HttpException(404, 'Fach nicht gefunden.');
        }

        // 2. Den Plan für genau dieses Datum suchen... oder einen neuen, leeren erstellen
        $plan = \humhub\modules\class_schedule\models\LessonPlan::findOne([
            'timetable_entry_id' => $entry->id, 
            'date' => $date
        ]);
        
        if ($plan === null) {
            $plan = new \humhub\modules\class_schedule\models\LessonPlan();
            $plan->timetable_entry_id = $entry->id;
            $plan->date = $date;
        }

        // 3. Speichern, wenn das Formular abgeschickt wurde
        if ($plan->load(\Yii::$app->request->post()) && $plan->save()) {
            // Nach dem Speichern schicken wir den User elegant zurück zum Kalender
            return $this->redirect($this->contentContainer->createUrl('/calendar/view/index'));
        }

        // 4. Formular anzeigen
        return $this->render('plan', [
            'plan' => $plan,
            'entry' => $entry,
            'space' => $this->contentContainer,
            'date' => $date
        ]);
    }
    public function actionConfig() {
    $model = new \humhub\modules\class_schedule\models\SpaceSettingsForm();
    $module = \Yii::$app->getModule('class-schedule');
    $model->teacher_group_id = $module->settings->space($this->contentContainer)->get('teacher_group_id');

    if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
        $module->settings->space($this->contentContainer)->set('teacher_group_id', $model->teacher_group_id);
        $this->view->saved();
        return $this->redirect($this->contentContainer->createUrl('/class-schedule/timetable/index'));
    }

    return $this->render('config', ['model' => $model, 'space' => $this->contentContainer]);
    }
    public function actionToggleBeamer()
    {
     $session = \Yii::$app->session;
     $currentState = $session->get('cs_beamer_mode', false);
     $session->set('cs_beamer_mode', !$currentState);

     return $this->asJson(['success' => true, 'beamerMode' => !$currentState]);
    }
}