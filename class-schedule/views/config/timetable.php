<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $lessonTimes humhub\modules\class_schedule\models\LessonTime[] */
/* @var $grid array */

$weekdays = [
    1 => 'Montag',
    2 => 'Dienstag',
    3 => 'Mittwoch',
    4 => 'Donnerstag',
    5 => 'Freitag'
];
?>

<div class="panel panel-default">
    <div class="panel-heading">
        Unterrichtsplaner <strong>Standardwoche (Template)</strong>
    </div>

    <div class="panel-body">
        <?= Html::a('<i class="fa fa-arrow-left"></i> Zurück zur Übersicht', ['index'], ['class' => 'btn btn-default', 'data-ui-loader' => '']) ?>
        <hr>

        <?php if (empty($lessonTimes)): ?>
            <div class="alert alert-warning">Bitte lege zuerst unter "Zeitraster konfigurieren" deine Lektionen an!</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr class="bg-info">
                            <th class="text-center">Zeit</th>
                            <?php foreach ($weekdays as $dayName): ?>
                                <th class="text-center"><?= $dayName ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lessonTimes as $lesson): ?>
                            <tr>
                                <td style="vertical-align: middle;">
                                    <strong><?= Html::encode($lesson->title) ?></strong><br>
                                    <small class="text-muted">
                                        <?= Yii::$app->formatter->asTime($lesson->start_time, 'short') ?> - 
                                        <?= Yii::$app->formatter->asTime($lesson->end_time, 'short') ?>
                                    </small>
                                </td>
                                
                                <?php for ($day = 1; $day <= 5; $day++): ?>
                                    <td style="vertical-align: middle; height: 80px; width: 16%;">
                                        <?php if (isset($grid[$day][$lesson->id])): ?>
                                            <?php $entry = $grid[$day][$lesson->id]; ?>
                                            <div style="background-color: <?= Html::encode($entry->color) ?>; color: #fff; padding: 10px; border-radius: 4px; position: relative;">
                                                <strong><?= Html::encode($entry->subject) ?></strong>
                                                
                                                <div style="margin-top: 5px;">
                                                    <?= Html::a('<i class="fa fa-pencil"></i>', ['edit-entry', 'weekday' => $day, 'lesson_time_id' => $lesson->id], ['class' => 'btn btn-xs btn-default', 'data-ui-loader' => '']) ?>
                                                    <?= Html::a('<i class="fa fa-trash"></i>', ['delete-entry', 'id' => $entry->id], [
                                                        'class' => 'btn btn-xs btn-danger',
                                                        'data-method' => 'POST',
                                                        'data-confirm' => 'Eintrag löschen?'
                                                    ]) ?>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <?= Html::a('<i class="fa fa-plus text-muted"></i>', ['edit-entry', 'weekday' => $day, 'lesson_time_id' => $lesson->id], [
                                                'class' => 'btn btn-default btn-sm', 
                                                'style' => 'width: 100%; height: 100%; border: 1px dashed #ccc; background: transparent;',
                                                'data-ui-loader' => ''
                                            ]) ?>
                                        <?php endif; ?>
                                    </td>
                                <?php endfor; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>