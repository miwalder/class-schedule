<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<div class="panel panel-default">
    <div class="panel-heading"><strong>Stundenplan</strong> Berechtigungen für <?= Html::encode($space->name) ?></div>
    <div class="panel-body">
        <div class="alert alert-info">Ferien und Schulwochen bleiben für alle Space-Mitglieder sichtbar. Diese Einstellung betrifft nur die eigentlichen Unterrichtsfächer.</div>
        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'teacher_group_id')->dropDownList($model->getGroupOptions()) ?>
        <hr>
        <?= Html::submitButton('Speichern', ['class' => 'btn btn-primary', 'data-ui-loader' => '']) ?>
        <a class="btn btn-default" href="<?= $space->createUrl('/class-schedule/timetable/index') ?>">Zurück</a>
        <?php ActiveForm::end(); ?>
    </div>
</div>