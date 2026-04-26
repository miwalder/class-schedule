<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model humhub\modules\class_schedule\models\TimetableEntry */
/* @var $lessonTime humhub\modules\class_schedule\models\LessonTime */

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
        <?= Yii::t('ClassScheduleModule.base', 'Add subject on') ?> <strong><?= $weekdays[$model->weekday] ?></strong> (<?= Html::encode($lessonTime->title) ?>)
    </div>

    <div class="panel-body">
        <?= Html::a('<i class="fa fa-arrow-left"></i> ' . Yii::t('ClassScheduleModule.base', 'Cancel'), ['timetable'], ['class' => 'btn btn-default', 'data-ui-loader' => '']) ?>
        <hr>

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-md-9">
                <?= $form->field($model, 'subject')->textInput(['placeholder' => Yii::t('ClassScheduleModule.base', 'e.g. Mathematics 3b'), 'maxlength' => true]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'color')->colorInput(['style' => 'height: 34px; cursor: pointer; padding: 0;']) ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('ClassScheduleModule.base', 'Save'), ['class' => 'btn btn-success', 'data-ui-loader' => '']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>