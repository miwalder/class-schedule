<?php

namespace humhub\modules\class_schedule\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $timetable_entry_id
 * @property string $date
 * @property string $topic
 * @property string $description
 * @property string $homework
 */
class LessonPlan extends ActiveRecord
{
    public static function tableName()
    {
        return 'cs_lesson_plan';
    }

    public function rules()
    {
        return [
            [['timetable_entry_id', 'date'], 'required'],
            [['timetable_entry_id'], 'integer'],
            [['date'], 'date', 'format' => 'php:Y-m-d'],
            [['description', 'homework'], 'string'],
            [['topic'], 'string', 'max' => 255],
            // Verhindert doppelte Einträge am selben Tag
            [['timetable_entry_id', 'date'], 'unique', 'targetAttribute' => ['timetable_entry_id', 'date']],
        ];
    }
    
    public function getTimetableEntry()
    {
        return $this->hasOne(TimetableEntry::class, ['id' => 'timetable_entry_id']);
    }
}