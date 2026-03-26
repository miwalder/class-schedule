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
           return ['teacher_group_id' => 'Berechtigung: Wer darf den Unterricht (Fächer) im Kalender sehen?'];
       }

       public function getGroupOptions() {
           $groups = Group::find()->all();
           $options = ['' => '--- Keine Einschränkung (Alle Space-Mitglieder) ---'];
           foreach ($groups as $group) {
               $options[$group->id] = $group->name;
           }
           return $options;
       }
   }