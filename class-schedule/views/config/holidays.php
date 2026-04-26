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
        <?= Yii::t('ClassScheduleModule.base', 'Manage holidays for:') ?> <strong><?= Html::encode($schoolYear->name) ?></strong>
    </div>

    <div class="panel-body">
        <?= Html::a('<i class="fa fa-arrow-left"></i> ' . Yii::t('ClassScheduleModule.base', 'Back to overview'), ['index'], ['class' => 'btn btn-default', 'data-ui-loader' => '']) ?>
        <hr>

        <h4><?= Yii::t('ClassScheduleModule.base', 'Add new holiday') ?></h4>
        
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'school_year_id')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'name')->textInput([
            'maxlength' => true, 
            'placeholder' => Yii::t('ClassScheduleModule.base', 'e.g. Autumn holidays')
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
            <?= Html::submitButton(Yii::t('ClassScheduleModule.base', 'Add holiday'), ['class' => 'btn btn-success', 'data-ui-loader' => '']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <br>
        <h4><?= Yii::t('ClassScheduleModule.base', 'Registered holidays') ?></h4>
        <hr>
        
        <?php if (empty($holidays)): ?>
            <p><?= Yii::t('ClassScheduleModule.base', 'No holidays have been created for this school year yet.') ?></p>
        <?php else: ?>
            <ul class="list-group">
                <?php foreach ($holidays as $holiday): ?>
                    <li class="list-group-item">
                        <div class="pull-right">
                            <?= Html::a('<i class="fa fa-pencil"></i>', ['edit-holiday', 'id' => $holiday->id], [
                                'class' => 'btn btn-sm btn-default',
                                'data-ui-loader' => '',
                                'title' => Yii::t('ClassScheduleModule.base', 'Edit')
                            ]) ?>
                            <?= Html::a('<i class="fa fa-trash"></i>', ['delete-holiday', 'id' => $holiday->id], [
                                'class' => 'btn btn-sm btn-danger',
                                'data-method' => 'POST',
                                'data-confirm' => Yii::t('ClassScheduleModule.base', 'Really delete this holiday?')
                            ]) ?>
                        </div>
                        
                        <strong><?= Html::encode($holiday->name) ?></strong> 
                        <br>
                        <small><?= Yii::$app->formatter->asDate($holiday->start_date) ?> <?= Yii::t('ClassScheduleModule.base', 'to') ?> <?= Yii::$app->formatter->asDate($holiday->end_date) ?></small>
                        
                        <div class="clearfix"></div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </div>
</div>