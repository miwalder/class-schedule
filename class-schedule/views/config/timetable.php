<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $lessonTimes humhub\modules\class_schedule\models\LessonTime[] */
/* @var $grid array */

$weekdays = [
    1 => Yii::t('ClassScheduleModule.base', 'Monday'),
    2 => Yii::t('ClassScheduleModule.base', 'Tuesday'),
    3 => Yii::t('ClassScheduleModule.base', 'Wednesday'),
    4 => Yii::t('ClassScheduleModule.base', 'Thursday'),
    5 => Yii::t('ClassScheduleModule.base', 'Friday')
];
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <?= Yii::t('ClassScheduleModule.base', 'Lesson planner') ?> <strong><?= Yii::t('ClassScheduleModule.base', 'Standard week (Template)') ?></strong>
    </div>

    <div class="panel-body">
        <?= Html::a('<i class="fa fa-arrow-left"></i> ' . Yii::t('ClassScheduleModule.base', 'Back to overview'), ['index'], ['class' => 'btn btn-default', 'data-ui-loader' => '']) ?>
        <hr>

        <?php if (empty($lessonTimes)): ?>
            <div class="alert alert-warning"><?= Yii::t('ClassScheduleModule.base', 'Please create your lessons first under "Configure time grid"!') ?></div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr class="bg-info">
                            <th class="text-center"><?= Yii::t('ClassScheduleModule.base', 'Time') ?></th>
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
                                                        'data-confirm' => Yii::t('ClassScheduleModule.base', 'Delete entry?')
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