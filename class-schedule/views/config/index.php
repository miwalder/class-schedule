<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model humhub\modules\class_schedule\models\SchoolYear */
/* @var $schoolYears humhub\modules\class_schedule\models\SchoolYear[] */
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <?= Yii::t('ClassScheduleModule.base', 'Class Schedule <strong>Configuration</strong>') ?>
    </div>

    <div class="panel-body">
        <div class="pull-right">
            <?= Html::a('<i class="fa fa-clock-o"></i> ' . Yii::t('ClassScheduleModule.base', 'Time raster'), ['lesson-times'], ['class' => 'btn btn-info', 'data-ui-loader' => '']) ?>
            
        </div>
        <div class="clearfix"></div>
        <br>
        <h4><?= Yii::t('ClassScheduleModule.base', 'Create new school year') ?></h4>
        <hr>

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput([
            'maxlength' => true, 
            'placeholder' => Yii::t('ClassScheduleModule.base', 'e.g. School year 2026/2027')
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

        <br>
        <h4><?= Yii::t('ClassScheduleModule.base', 'Existing school years') ?></h4>
        <hr>
        
<?php if (empty($schoolYears)): ?>
            <p><?= Yii::t('ClassScheduleModule.base', 'No school years have been created yet.') ?></p>
        <?php else: ?>
            <ul class="list-group">
                <?php foreach ($schoolYears as $sy): ?>
                    <li class="list-group-item">
                        <div class="pull-right">
                            <?= Html::a('<i class="fa fa-calendar"></i> ' . Yii::t('ClassScheduleModule.base', 'Manage holidays'), ['holidays', 'id' => $sy->id], ['class' => 'btn btn-sm btn-primary', 'data-ui-loader' => '']) ?>
                            <?= Html::a('<i class="fa fa-trash"></i>', ['delete-school-year', 'id' => $sy->id], [
                                'class' => 'btn btn-sm btn-danger',
                                'data-method' => 'POST',
                                'data-confirm' => Yii::t('ClassScheduleModule.base', 'Really delete this school year?')
                            ]) ?>
                        </div>
                        
                        <strong><?= Html::encode($sy->name) ?></strong> 
                        <br>
                        <small><?= Yii::$app->formatter->asDate($sy->start_date) ?> <?= Yii::t('ClassScheduleModule.base', 'to') ?> <?= Yii::$app->formatter->asDate($sy->end_date) ?></small>
                        
                        <div class="clearfix"></div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </div>
</div>