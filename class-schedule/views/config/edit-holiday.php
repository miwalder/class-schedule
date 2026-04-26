<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model humhub\modules\class_schedule\models\Holiday */
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <?= Yii::t('ClassScheduleModule.base', 'Edit holiday:') ?> <strong><?= Html::encode($model->name) ?></strong>
    </div>

    <div class="panel-body">
        <?= Html::a('<i class="fa fa-arrow-left"></i> ' . Yii::t('ClassScheduleModule.base', 'Back'), ['holidays', 'id' => $model->school_year_id], ['class' => 'btn btn-default', 'data-ui-loader' => '']) ?>
        <hr>

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput([
            'maxlength' => true, 
        ]) ?>

        <div class="row mt-3 mb-3">
            <div class="col-md-6">
                <?= $form->field($model, 'start_date')->widget(\humhub\modules\ui\form\widgets\DatePicker::class) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'end_date')->widget(\humhub\modules\ui\form\widgets\DatePicker::class) ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('ClassScheduleModule.base', 'Save'), ['class' => 'btn btn-primary', 'data-ui-loader' => '']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
