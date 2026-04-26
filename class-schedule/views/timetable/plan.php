<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use humhub\modules\content\widgets\richtext\RichTextField;

// Das Datum schön formatieren (z.B. 30.03.2026)
$formattedDate = date('d.m.Y', strtotime($date));
?>

<div class="panel panel-default">
    <div class="panel-heading" style="background-color: <?= Html::encode($entry->color) ?>; color: white;">
        <strong><i class="fa fa-graduation-cap"></i> <?= Yii::t('ClassScheduleModule.base', 'Lesson Planning:') ?> <?= Html::encode($entry->subject) ?></strong>
    </div>
    
    <div class="panel-body">
        <p class="lead"><?= Yii::t('ClassScheduleModule.base', 'Planning for') ?> <strong><?= $formattedDate ?></strong> (<?= date('H:i', strtotime($entry->lessonTime->start_time)) ?> - <?= date('H:i', strtotime($entry->lessonTime->end_time)) ?> <?= Yii::t('ClassScheduleModule.base', 'o\'clock') ?>)</p>
        <hr>

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($plan, 'topic')->textInput([
            'maxlength' => true, 
            'placeholder' => Yii::t('ClassScheduleModule.base', 'e.g. Introduce fractions')
        ])->label(Yii::t('ClassScheduleModule.base', 'Lesson topic')) ?>

        <?= $form->field($plan, 'description')->widget(RichTextField::class, [
            'placeholder' => Yii::t('ClassScheduleModule.base', 'Process, required materials, notes...')
        ])->label(Yii::t('ClassScheduleModule.base', 'Process & Notes')) ?>

        <?= $form->field($plan, 'homework')->textarea([
            'rows' => 3, 
            'placeholder' => Yii::t('ClassScheduleModule.base', 'Homework for students...')
        ])->label(Yii::t('ClassScheduleModule.base', 'Homework')) ?>

        <hr>
        
        <?= Html::submitButton(Yii::t('ClassScheduleModule.base', 'Save planning'), ['class' => 'btn btn-primary', 'data-ui-loader' => '']) ?>
        <a href="<?= $space->createUrl('/calendar/view/index') ?>" class="btn btn-default"><?= Yii::t('ClassScheduleModule.base', 'Cancel') ?></a>

        <?php ActiveForm::end(); ?>
    </div>
</div>