<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%flight}}`.
 */
class m200505_081649_create_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%flight}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'flight_datetime' => $this->integer(),
            'amount_of_places' => $this->tinyInteger()->unsigned()->defaultValue(150),
            'status' => $this->tinyInteger()->defaultValue(0),
            'description' => $this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%flight}}');
    }
}
