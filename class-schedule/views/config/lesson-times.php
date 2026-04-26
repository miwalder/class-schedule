<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model humhub\modules\class_schedule\models\LessonTime */
/* @var $lessonTimes humhub\modules\class_schedule\models\LessonTime[] */
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <?= Yii::t('ClassScheduleModule.base', 'Class Schedule <strong>Time raster (Lessons & Breaks)</strong>') ?>
    </div>

    <div class="panel-body">
        <?= Html::a('<i class="fa fa-arrow-left"></i> ' . Yii::t('ClassScheduleModule.base', 'Back to school years'), ['index'], ['class' => 'btn btn-default', 'data-ui-loader' => '']) ?>
        <hr>

        <h4><?= Yii::t('ClassScheduleModule.base', 'Add new block') ?></h4>
        
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'title')->textInput([
            'maxlength' => true, 
            'placeholder' => Yii::t('ClassScheduleModule.base', 'e.g. 1st lesson')
        ]) ?>

        <div class="row mt-3 mb-3">
            <div class="col-md-6">
                <?= $form->field($model, 'start_time')->widget(\humhub\modules\ui\form\widgets\TimePicker::class) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'end_time')->widget(\humhub\modules\ui\form\widgets\TimePicker::class) ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('ClassScheduleModule.base', 'Save block'), ['class' => 'btn btn-success', 'data-ui-loader' => '']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <br>
        <h4><?= Yii::t('ClassScheduleModule.base', 'Current time raster') ?></h4>
        <hr>
        
        <?php if (empty($lessonTimes)): ?>
            <p><?= Yii::t('ClassScheduleModule.base', 'No time raster created yet.') ?></p>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><?= Yii::t('ClassScheduleModule.base', 'Time') ?></th>
                        <th><?= Yii::t('ClassScheduleModule.base', 'Title') ?></th>
                        <th style="width: 80px;"><?= Yii::t('ClassScheduleModule.base', 'Action') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lessonTimes as $lesson): ?>
                        <tr>
                            <td>
                                <strong><?= Yii::$app->formatter->asTime($lesson->start_time, 'short') ?> - <?= Yii::$app->formatter->asTime($lesson->end_time, 'short') ?></strong>
                            </td>
                            <td><?= Html::encode($lesson->title) ?></td>
                            <td>
                                <?= Html::a('<i class="fa fa-trash"></i>', ['delete-lesson-time', 'id' => $lesson->id], [
                                    'class' => 'btn btn-sm btn-danger',
                                    'data-method' => 'POST',
                                    'data-confirm' => Yii::t('ClassScheduleModule.base', 'Really delete this block?')
                                ]) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

    </div>
</div>