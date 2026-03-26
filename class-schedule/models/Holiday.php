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
            'school_year_id' => 'Schuljahr',
            'name' => 'Name der Ferien (z.B. Herbstferien)',
            'start_date' => 'Startdatum',
            'end_date' => 'Enddatum',
        ];
    }

    /**
     * Verknüpfung zurück zum Schuljahr
     */
    public function getSchoolYear()
    {
        return $this->hasOne(SchoolYear::class, ['id' => 'school_year_id']);
    }
}