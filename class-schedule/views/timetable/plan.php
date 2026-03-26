<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

// Das Datum schön formatieren (z.B. 30.03.2026)
$formattedDate = date('d.m.Y', strtotime($date));
?>

<div class="panel panel-default">
    <div class="panel-heading" style="background-color: <?= Html::encode($entry->color) ?>; color: white;">
        <strong><i class="fa fa-graduation-cap"></i> Unterrichtsplanung: <?= Html::encode($entry->subject) ?></strong>
    </div>
    
    <div class="panel-body">
        <p class="lead">Planung für <strong><?= $formattedDate ?></strong> (<?= date('H:i', strtotime($entry->lessonTime->start_time)) ?> - <?= date('H:i', strtotime($entry->lessonTime->end_time)) ?> Uhr)</p>
        <hr>

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($plan, 'topic')->textInput([
            'maxlength' => true, 
            'placeholder' => 'z.B. Bruchrechnen einführen'
        ])->label('Thema der Lektion') ?>

        <?= $form->field($plan, 'description')->textarea([
            'rows' => 6, 
            'placeholder' => 'Ablauf, benötigtes Material, Notizen...'
        ])->label('Ablauf & Notizen') ?>

        <?= $form->field($plan, 'homework')->textarea([
            'rows' => 3, 
            'placeholder' => 'Hausaufgaben für die Schüler...'
        ])->label('Hausaufgaben') ?>

        <hr>
        
        <?= Html::submitButton('Planung speichern', ['class' => 'btn btn-primary', 'data-ui-loader' => '']) ?>
        <a href="<?= $space->createUrl('/calendar/view/index') ?>" class="btn btn-default">Abbrechen</a>

        <?php ActiveForm::end(); ?>
    </div>
</div>