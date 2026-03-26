<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model humhub\modules\class_schedule\models\SchoolYear */
/* @var $schoolYears humhub\modules\class_schedule\models\SchoolYear[] */
?>

<div class="panel panel-default">
    <div class="panel-heading">
        Unterrichtsplaner <strong>Konfiguration</strong>
    </div>

    <div class="panel-body">
        <div class="pull-right">
            <?= Html::a('<i class="fa fa-clock-o"></i> Zeitraster', ['lesson-times'], ['class' => 'btn btn-info', 'data-ui-loader' => '']) ?>
            
        </div>
        <div class="clearfix"></div>
        <br>
        <h4>Neues Schuljahr anlegen</h4>
        <hr>

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput([
            'maxlength' => true, 
            'placeholder' => 'z.B. Schuljahr 2026/2027'
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
            <?= Html::submitButton('Speichern', ['class' => 'btn btn-primary', 'data-ui-loader' => '']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <br>
        <h4>Vorhandene Schuljahre</h4>
        <hr>
        
<?php if (empty($schoolYears)): ?>
            <p>Bisher wurden keine Schuljahre angelegt.</p>
        <?php else: ?>
            <ul class="list-group">
                <?php foreach ($schoolYears as $sy): ?>
                    <li class="list-group-item">
                        <div class="pull-right">
                            <?= Html::a('<i class="fa fa-calendar"></i> Ferien verwalten', ['holidays', 'id' => $sy->id], ['class' => 'btn btn-sm btn-primary', 'data-ui-loader' => '']) ?>
                        </div>
                        
                        <strong><?= Html::encode($sy->name) ?></strong> 
                        <br>
                        <small><?= Yii::$app->formatter->asDate($sy->start_date) ?> bis <?= Yii::$app->formatter->asDate($sy->end_date) ?></small>
                        
                        <div class="clearfix"></div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </div>
</div>