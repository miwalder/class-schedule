<?php

namespace humhub\modules\class_schedule\models;

use humhub\components\ActiveRecord;

/**
 * Model für die Tabelle "timetable_entry"
 */
class TimetableEntry extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cs_timetable_entry';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
 return [
            // NEU: contentcontainer_id ist jetzt auch Pflicht!
            [['weekday', 'lesson_time_id', 'subject', 'contentcontainer_id'], 'required'],
            [['weekday', 'lesson_time_id', 'contentcontainer_id'], 'integer'],
            [['subject'], 'string', 'max' => 255],
            [['color'], 'string', 'max' => 7],
            [['contentcontainer_id', 'lesson_time_id', 'weekday', 'subject', 'color'], 'required'],
            [['contentcontainer_id', 'lesson_time_id', 'weekday', 'school_year_id'], 'integer'], // <-- school_year_id als integer hinzugefügt
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'weekday' => \Yii::t('ClassScheduleModule.base', 'Weekday'),
            'lesson_time_id' => \Yii::t('ClassScheduleModule.base', 'Lesson'),
            'subject' => \Yii::t('ClassScheduleModule.base', 'Subject / Class (e.g. Math 3b)'),
            'color' => \Yii::t('ClassScheduleModule.base', 'Color'),
        ];
    }

    /**
     * Verknüpfung zur Lektionszeit
     */
    public function getLessonTime()
    {
        return $this->hasOne(LessonTime::class, ['id' => 'lesson_time_id']);
    }
}