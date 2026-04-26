<?php

namespace humhub\modules\class_schedule\models;

use humhub\components\ActiveRecord;

/**
 * Model für die Tabelle "holiday"
 */
class Holiday extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cs_holiday';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['school_year_id', 'name', 'start_date', 'end_date'], 'required'],
            [['school_year_id'], 'integer'],
            [['start_date', 'end_date'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'school_year_id' => \Yii::t('ClassScheduleModule.base', 'School year'),
            'name' => \Yii::t('ClassScheduleModule.base', 'Holiday name (e.g. Autumn holidays)'),
            'start_date' => \Yii::t('ClassScheduleModule.base', 'Start date'),
            'end_date' => \Yii::t('ClassScheduleModule.base', 'End date'),
        ];
    }

    /**
     * Verknüpfung zurück zum Schuljahr
     */
    public function getSchoolYear()
    {
        return $this->hasOne(SchoolYear::class, ['id' => 'school_year_id']);
    }

    /**
     * Parsing local date formats to Y-m-d before validation
     */
    public function beforeValidate()
    {
        $parseLocal = function($dateStr) {
            if (!empty($dateStr) && !preg_match('/^\d{4}-\d{2}-\d{2}/', $dateStr)) {
                $format = \Yii::$app->formatter->dateInputFormat;
                if (strncmp($format, 'php:', 4) === 0) {
                    $format = substr($format, 4);
                } else {
                    $format = \yii\helpers\FormatConverter::convertDateIcuToPhp($format);
                }
                
                $date = \DateTime::createFromFormat($format, $dateStr);
                if ($date !== false) {
                    return $date->format('Y-m-d');
                }
            }
            return $dateStr;
        };

        $this->start_date = $parseLocal($this->start_date);
        $this->end_date = $parseLocal($this->end_date);

        return parent::beforeValidate();
    }
}