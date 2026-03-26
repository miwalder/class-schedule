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
            'title' => 'Bezeichnung (z.B. 1. Lektion oder Grosse Pause)',
            'start_time' => 'Startzeit',
            'end_time' => 'Endzeit',
            'sort_order' => 'Sortierung',
        ];
    }
}