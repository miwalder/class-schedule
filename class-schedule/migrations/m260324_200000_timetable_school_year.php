<?php

use yii\db\Migration;

class m260324_200000_timetable_school_year extends Migration
{
    public function safeUp()
    {
        // Wir fügen die Spalte 'school_year_id' hinzu. 
        // Vorerst 'null' erlaubt, damit alte Testdaten nicht den Migrations-Prozess crashen.
        $this->addColumn('cs_timetable_entry', 'school_year_id', $this->integer()->null());
        
        // Wenn du weißt, wie die globale Tabelle für die Schuljahre genau heißt 
        // (z.B. cs_school_year), könnten wir hier später noch einen ForeignKey setzen.
        // Für den Moment reicht die Spalte als ID-Speicher völlig aus!
    }

    public function safeDown()
    {
        $this->dropColumn('cs_timetable_entry', 'school_year_id');
    }
}