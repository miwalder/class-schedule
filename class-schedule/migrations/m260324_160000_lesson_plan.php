<?php

use yii\db\Migration;

class m260324_160000_lesson_plan extends Migration
{
    public function safeUp()
    {
        $this->createTable('cs_lesson_plan', [
            'id' => $this->primaryKey(),
            'timetable_entry_id' => $this->integer()->notNull(),
            'date' => $this->date()->notNull(),
            'topic' => $this->string(255)->null(), // Kurzes Thema (z.B. "Bruchrechnen")
            'description' => $this->text()->null(), // Ausführliche Planung
            'homework' => $this->text()->null(), // Hausaufgaben
        ]);

        // Verknüpfung zum Stundenplan-Raster herstellen
        $this->addForeignKey(
            'fk-lesson_plan-timetable_entry',
            'cs_lesson_plan',
            'timetable_entry_id',
            'cs_timetable_entry',
            'id',
            'CASCADE', // Wenn das Fach im Raster gelöscht wird, löschen wir auch die Planung
            'CASCADE'
        );

        // Sicherstellen, dass es pro Fach und Datum nur EINE Planung gibt
        $this->createIndex(
            'idx-lesson_plan-unique',
            'cs_lesson_plan',
            ['timetable_entry_id', 'date'],
            true
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-lesson_plan-timetable_entry', 'cs_lesson_plan');
        $this->dropTable('cs_lesson_plan');
    }
}