<?php

namespace humhub\modules\class_schedule\models;

use humhub\components\ActiveRecord;

/**
 * Model für die Tabelle "lesson_time"
 */
class LessonTime extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cs_lesson_time';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'start_time', 'end_time'], 'required'],
            [['start_time', 'end_time'], 'safe'], // Uhrzeiten
            [['sort_order'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => \Yii::t('ClassScheduleModule.base', 'Title (e.g. 1st lesson or long break)'),
            'start_time' => \Yii::t('ClassScheduleModule.base', 'Start time'),
            'end_time' => \Yii::t('ClassScheduleModule.base', 'End time'),
            'sort_order' => \Yii::t('ClassScheduleModule.base', 'Sort order'),
        ];
    }
}