<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $schoolYear humhub\modules\class_schedule\models\SchoolYear */
/* @var $model humhub\modules\class_schedule\models\Holiday */
/* @var $holidays humhub\modules\class_schedule\models\Holiday[] */
?>

<div class="panel panel-default">
    <div class="panel-heading">
        Ferien verwalten für: <strong><?= Html::encode($schoolYear->name) ?></strong>
    </div>

    <div class="panel-body">
        <?= Html::a('<i class="fa fa-arrow-left"></i> Zurück zur Übersicht', ['index'], ['class' => 'btn btn-default', 'data-ui-loader' => '']) ?>
        <hr>

        <h4>Neue Ferien eintragen</h4>
        
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'school_year_id')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'name')->textInput([
            'maxlength' => true, 
            'placeholder' => 'z.B. Herbstferien'
        ]) ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'start_date')->textInput(['type' => 'date']) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'end_date')->textInput(['type' => 'date']) ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Ferien hinzufügen', ['class' => 'btn btn-success', 'data-ui-loader' => '']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <br>
        <h4>Eingetragene Ferien</h4>
        <hr>
        
        <?php if (empty($holidays)): ?>
            <p>Für dieses Schuljahr wurden noch keine Ferien eingetragen.</p>
        <?php else: ?>
            <ul class="list-group">
                <?php foreach ($holidays as $holiday): ?>
                    <li class="list-group-item">
                        <div class="pull-right">
                            <?= Html::a('<i class="fa fa-trash"></i>', ['delete-holiday', 'id' => $holiday->id], [
                                'class' => 'btn btn-sm btn-danger',
                                'data-method' => 'POST',
                                'data-confirm' => 'Diese Ferien wirklich löschen?'
                            ]) ?>
                        </div>
                        
                        <strong><?= Html::encode($holiday->name) ?></strong> 
                        <br>
                        <small><?= Yii::$app->formatter->asDate($holiday->start_date) ?> bis <?= Yii::$app->formatter->asDate($holiday->end_date) ?></small>
                        
                        <div class="clearfix"></div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </div>
</div>