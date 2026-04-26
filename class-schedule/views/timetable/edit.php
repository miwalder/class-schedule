<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

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
        <strong><?= Yii::t('ClassScheduleModule.base', 'Add subject') ?></strong> <?= Yii::t('ClassScheduleModule.base', 'for') ?> <?= $weekdays[$model->weekday] ?>
    </div>
    
    <div class="panel-body">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'subject')->textInput([
            'maxlength' => true, 
            'placeholder' => Yii::t('ClassScheduleModule.base', 'e.g. Mathematics, English, Sports...')
        ])->label(Yii::t('ClassScheduleModule.base', 'Subject name')) ?>

        <?= $form->field($model, 'color')->colorInput(['style' => 'width: 100px; padding: 0; cursor: pointer;'])->label(Yii::t('ClassScheduleModule.base', 'Color in timetable')) ?>

        <hr>
        
        <?= Html::submitButton(Yii::t('ClassScheduleModule.base', 'Save'), ['class' => 'btn btn-primary', 'data-ui-loader' => '']) ?>
        
        <a href="<?= $space->createUrl('/class-schedule/timetable/index') ?>" class="btn btn-default"><?= Yii::t('ClassScheduleModule.base', 'Cancel') ?></a>

        <?php if (!$model->isNewRecord): ?>
            <a href="<?= $space->createUrl('/class-schedule/timetable/delete', ['id' => $model->id]) ?>" 
               class="btn btn-danger pull-right" 
               data-method="POST" 
               data-confirm="<?= Yii::t('ClassScheduleModule.base', 'Do you really want to delete this subject from the timetable?') ?>">
               <i class="fa fa-trash"></i> <?= Yii::t('ClassScheduleModule.base', 'Delete') ?>
            </a>
        <?php endif; ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>