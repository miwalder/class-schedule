<?php

use yii\db\Migration;

class uninstall extends Migration
{
    public function up()
    {
        $this->dropTable('cs_lesson_plan');
        $this->dropTable('cs_timetable_entry');
        $this->dropTable('cs_lesson_time');
        $this->dropTable('cs_holiday');
        $this->dropTable('cs_school_year');
    }

    public function down()
    {
        echo "uninstall does not support migration down.\n";
        return false;
    }
}
