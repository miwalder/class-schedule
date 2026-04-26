<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<div class="panel panel-default">
    <div class="panel-heading"><strong><?= Yii::t('ClassScheduleModule.base', 'Timetable') ?></strong> <?= Yii::t('ClassScheduleModule.base', 'permissions for') ?> <?= Html::encode($space->name) ?></div>
    <div class="panel-body">
        <div class="alert alert-info"><?= Yii::t('ClassScheduleModule.base', 'Holidays and school weeks remain visible for all space members. This setting only affects the actual subjects.') ?></div>
        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'teacher_group_id')->dropDownList($model->getGroupOptions()) ?>
        <hr>
        <?= Html::submitButton(Yii::t('ClassScheduleModule.base', 'Save'), ['class' => 'btn btn-primary', 'data-ui-loader' => '']) ?>
        <a class="btn btn-default" href="<?= $space->createUrl('/class-schedule/timetable/index') ?>"><?= Yii::t('ClassScheduleModule.base', 'Back') ?></a>
        <?php ActiveForm::end(); ?>
    </div>
</div>