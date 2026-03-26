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
            'name' => 'Name des Schuljahres (z.B. 2026/2027)',
            'start_date' => 'Startdatum',
            'end_date' => 'Enddatum',
        ];
    }

    /**
     * Verknüpfung zu den Ferien
     */
    public function getHolidays()
    {
        return $this->hasMany(Holiday::class, ['school_year_id' => 'id']);
    }
}