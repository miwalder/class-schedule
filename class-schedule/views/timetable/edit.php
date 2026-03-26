<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

// Ein kleines Helfer-Array, damit oben "Montag" statt "1" steht
$weekdays = [1 => 'Montag', 2 => 'Dienstag', 3 => 'Mittwoch', 4 => 'Donnerstag', 5 => 'Freitag'];
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <strong>Fach eintragen</strong> für <?= $weekdays[$model->weekday] ?>
    </div>
    
    <div class="panel-body">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'subject')->textInput([
            'maxlength' => true, 
            'placeholder' => 'z.B. Mathematik, Deutsch, Sport...'
        ])->label('Name des Fachs') ?>

        <?= $form->field($model, 'color')->textInput(['type' => 'color', 'style' => 'width: 100px; padding: 0; cursor: pointer;'])->label('Farbe im Stundenplan') ?>

        <hr>
        
        <?= Html::submitButton('Speichern', ['class' => 'btn btn-primary', 'data-ui-loader' => '']) ?>
        
        <a href="<?= $space->createUrl('/class-schedule/timetable/index') ?>" class="btn btn-default">Abbrechen</a>

        <?php if (!$model->isNewRecord): ?>
            <a href="<?= $space->createUrl('/class-schedule/timetable/delete', ['id' => $model->id]) ?>" 
               class="btn btn-danger pull-right" 
               data-method="POST" 
               data-confirm="Möchtest du dieses Fach wirklich aus dem Stundenplan löschen?">
               <i class="fa fa-trash"></i> Löschen
            </a>
        <?php endif; ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>