<?php
   namespace humhub\modules\class_schedule\models;
   use yii\base\Model;
   use humhub\modules\user\models\Group;

   class SpaceSettingsForm extends Model {
       public $teacher_group_id;

       public function rules() {
           return [[['teacher_group_id'], 'integer']];
       }

       public function attributeLabels() {
           return ['teacher_group_id' => \Yii::t('ClassScheduleModule.base', 'Permission: Who can see the lessons (subjects) in the calendar?')];
       }

       public function getGroupOptions() {
           $groups = Group::find()->all();
           $options = ['' => \Yii::t('ClassScheduleModule.base', '--- No restriction (All space members) ---')];
           foreach ($groups as $group) {
               $options[$group->id] = $group->name;
           }
           return $options;
       }
   }