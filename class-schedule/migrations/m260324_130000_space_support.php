<?php

use yii\db\Migration;

class m260324_130000_space_support extends Migration
{
    public function safeUp()
    {
        // Tabellen-Struktur auslesen (mit \Yii direkt aus dem globalen Namensraum)
        $table = \Yii::$app->db->schema->getTableSchema('cs_timetable_entry');
        
        // 1. Spalte nur hinzufügen, wenn sie noch NICHT existiert
        if (!isset($table->columns['contentcontainer_id'])) {
            $this->addColumn('cs_timetable_entry', 'contentcontainer_id', $this->integer()->null());
            echo "Spalte 'contentcontainer_id' wurde hinzugefügt.\n";
        } else {
            echo "Spalte 'contentcontainer_id' existiert bereits. Überspringe...\n";
        }

        // 2. Foreign Key hinzufügen (Wir nutzen try-catch, falls der Key durch einen Abbruch auch schon da ist)
        try {
            $this->addForeignKey(
                'fk-cs_timetable-container',
                'cs_timetable_entry',
                'contentcontainer_id',
                'contentcontainer',
                'pk',
                'CASCADE',
                'CASCADE'
            );
            echo "Foreign Key wurde erstellt.\n";
        } catch (\Exception $e) {
            echo "Hinweis: Foreign Key existiert bereits oder wurde übersprungen.\n";
        }
        
        // 3. Alte Test-Einträge ohne Space-Zuordnung löschen
        $this->delete('cs_timetable_entry', ['contentcontainer_id' => null]);
    }

    public function safeDown()
    {
        try {
            $this->dropForeignKey('fk-cs_timetable-container', 'cs_timetable_entry');
        } catch (\Exception $e) {}
        
        $table = \Yii::$app->db->schema->getTableSchema('cs_timetable_entry');
        if (isset($table->columns['contentcontainer_id'])) {
            $this->dropColumn('cs_timetable_entry', 'contentcontainer_id');
        }
    }
}