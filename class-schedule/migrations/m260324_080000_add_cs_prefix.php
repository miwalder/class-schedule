<?php

use yii\db\Migration;

class m260324_080000_add_cs_prefix extends Migration
{
    public function safeUp()
    {
        // 1. Zuerst müssen wir die alten Verbindungen (Foreign Keys) kappen
        $this->dropForeignKey('fk-holiday-school_year_id', 'holiday');
        $this->dropForeignKey('fk-timetable-lesson_time_id', 'timetable_entry');

        // 2. Tabellen umbenennen. 
        // Das erstellt quasi die neue Tabelle, verschiebt alle Daten und löscht die alte.
        $this->renameTable('school_year', 'cs_school_year');
        $this->renameTable('holiday', 'cs_holiday');
        $this->renameTable('lesson_time', 'cs_lesson_time');
        $this->renameTable('timetable_entry', 'cs_timetable_entry');

        // 3. Neue Verbindungen (Foreign Keys) für die neuen cs_ Tabellen setzen
        $this->addForeignKey(
            'fk-cs_holiday-school_year_id',
            'cs_holiday',
            'school_year_id',
            'cs_school_year',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-cs_timetable-lesson_time_id',
            'cs_timetable_entry',
            'lesson_time_id',
            'cs_lesson_time',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        // Der Rückwärtsgang, falls du das Update jemals rückgängig machen willst
        $this->dropForeignKey('fk-cs_holiday-school_year_id', 'cs_holiday');
        $this->dropForeignKey('fk-cs_timetable-lesson_time_id', 'cs_timetable_entry');

        $this->renameTable('cs_school_year', 'school_year');
        $this->renameTable('cs_holiday', 'holiday');
        $this->renameTable('cs_lesson_time', 'lesson_time');
        $this->renameTable('cs_timetable_entry', 'timetable_entry');

        $this->addForeignKey('fk-holiday-school_year_id', 'holiday', 'school_year_id', 'school_year', 'id', 'CASCADE');
        $this->addForeignKey('fk-timetable-lesson_time_id', 'timetable_entry', 'lesson_time_id', 'lesson_time', 'id', 'CASCADE');
    }
}