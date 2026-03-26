<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model humhub\modules\class_schedule\models\TimetableEntry */
/* @var $lessonTime humhub\modules\class_schedule\models\LessonTime */

$weekdays = [1 => 'Montag', 2 => 'Dienstag', 3 => 'Mittwoch', 4 => 'Donnerstag', 5 => 'Freitag'];
?>

<div class="panel panel-default">
    <div class="panel-heading">
        Fach eintragen am <strong><?= $weekdays[$model->weekday] ?></strong> (<?= Html::encode($lessonTime->title) ?>)
    </div>

    <div class="panel-body">
        <?= Html::a('<i class="fa fa-arrow-left"></i> Abbrechen', ['timetable'], ['class' => 'btn btn-default', 'data-ui-loader' => '']) ?>
        <hr>

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-md-9">
                <?= $form->field($model, 'subject')->textInput(['placeholder' => 'z.B. Mathematik 3b', 'maxlength' => true]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'color')->textInput(['type' => 'color', 'style' => 'padding: 0; height: 34px;']) ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Speichern', ['class' => 'btn btn-success', 'data-ui-loader' => '']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>