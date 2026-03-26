<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model humhub\modules\class_schedule\models\LessonTime */
/* @var $lessonTimes humhub\modules\class_schedule\models\LessonTime[] */
?>

<div class="panel panel-default">
    <div class="panel-heading">
        Unterrichtsplaner <strong>Zeitraster (Lektionen & Pausen)</strong>
    </div>

    <div class="panel-body">
        <?= Html::a('<i class="fa fa-arrow-left"></i> Zurück zu den Schuljahren', ['index'], ['class' => 'btn btn-default', 'data-ui-loader' => '']) ?>
        <hr>

        <h4>Neuen Block hinzufügen</h4>
        
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'title')->textInput([
            'maxlength' => true, 
            'placeholder' => 'z.B. 1. Lektion'
        ]) ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'start_time')->textInput(['type' => 'time']) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'end_time')->textInput(['type' => 'time']) ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Block speichern', ['class' => 'btn btn-success', 'data-ui-loader' => '']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <br>
        <h4>Aktuelles Zeitraster</h4>
        <hr>
        
        <?php if (empty($lessonTimes)): ?>
            <p>Noch kein Zeitraster angelegt.</p>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Zeit</th>
                        <th>Bezeichnung</th>
                        <th style="width: 80px;">Aktion</th>
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
                                    'data-confirm' => 'Diesen Block wirklich löschen?'
                                ]) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

    </div>
</div>