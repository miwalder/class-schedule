<?php

namespace humhub\modules\class_schedule\models;

use humhub\components\ActiveRecord;

/**
 * Model für die Tabelle "school_year"
 */
class SchoolYear extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cs_school_year';
    }

    /**
     * @inheritdoc
     * Hier definieren wir, welche Felder Pflicht sind und welche Datentypen sie haben.
     */
    public function rules()
    {
        return [
            [['name', 'start_date', 'end_date'], 'required'],
            [['start_date', 'end_date'], 'safe'], // 'safe' bedeutet, es ist ein Datum/Zeit-Format
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     * Labels für Formulare (später im Editor wichtig)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => \Yii::t('ClassScheduleModule.base', 'School year name (e.g. 2026/2027)'),
            'start_date' => \Yii::t('ClassScheduleModule.base', 'Start date'),
            'end_date' => \Yii::t('ClassScheduleModule.base', 'End date'),
        ];
    }

    /**
     * Verknüpfung zu den Ferien
     */
    public function getHolidays()
    {
        return $this->hasMany(Holiday::class, ['school_year_id' => 'id']);
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