<?php

use yii\db\Migration;

/**
 * Class m260322_073623_initial_class_schedule
 * Erstellt die Grundtabellen für den Unterrichtsplaner.
 */
class m260322_073623_initial_class_schedule extends Migration
{
    public function safeUp()
    {
        // 1. Tabelle für die Schuljahre
        $this->createTable('school_year', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(), // z. B. "2026/2027"
            'start_date' => $this->date()->notNull(),
            'end_date' => $this->date()->notNull(),
        ]);

        // 2. Tabelle für die Ferien (verknüpft mit dem Schuljahr)
        $this->createTable('holiday', [
            'id' => $this->primaryKey(),
            'school_year_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(), // z. B. "Herbstferien"
            'start_date' => $this->date()->notNull(),
            'end_date' => $this->date()->notNull(),
        ]);

        // 3. Tabelle für das Zeitraster (Lektionen & Pausen)
        $this->createTable('lesson_time', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(), // z. B. "1. Lektion"
            'start_time' => $this->time()->notNull(),
            'end_time' => $this->time()->notNull(),
            'sort_order' => $this->integer()->defaultValue(0), // Um sie richtig zu sortieren
        ]);

        // 4. Tabelle für den Standard-Stundenplan (dein Template)
        $this->createTable('timetable_entry', [
            'id' => $this->primaryKey(),
            'weekday' => $this->integer()->notNull(), // 1 = Montag, 5 = Freitag
            'lesson_time_id' => $this->integer()->notNull(),
            'subject' => $this->string()->notNull(), // z. B. "Mathematik"
            'color' => $this->string(7)->defaultValue('#3498db'), // HEX-Farbe für die Anzeige
        ]);

        // --- Verknüpfungen (Foreign Keys) ---
        
        // Wenn ein Schuljahr gelöscht wird, lösche auch die zugehörigen Ferien (CASCADE)
        $this->addForeignKey(
            'fk-holiday-school_year_id',
            'holiday',
            'school_year_id',
            'school_year',
            'id',
            'CASCADE'
        );

        // Verknüpfe den Stundenplan-Eintrag mit der entsprechenden Lektionszeit
        $this->addForeignKey(
            'fk-timetable-lesson_time_id',
            'timetable_entry',
            'lesson_time_id',
            'lesson_time',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        // safeDown() wird aufgerufen, wenn du das Modul deinstallierst. 
        // Es macht alles wieder rückgängig.
        $this->dropForeignKey('fk-timetable-lesson_time_id', 'timetable_entry');
        $this->dropForeignKey('fk-holiday-school_year_id', 'holiday');
        
        $this->dropTable('timetable_entry');
        $this->dropTable('lesson_time');
        $this->dropTable('holiday');
        $this->dropTable('school_year');
    }
}