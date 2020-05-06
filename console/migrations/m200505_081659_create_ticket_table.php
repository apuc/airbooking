<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ticket}}`.
 */
class m200505_081659_create_ticket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ticket}}', [
            'id' => $this->primaryKey(),
            'place' => $this->tinyInteger()->unsigned(),
            'price' => $this->decimal(),
            'status' => $this->tinyInteger(),
            'purchase_datetime' => $this->timestamp(),
            'flight_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-ticket-flight_id',
            'ticket',
            'flight_id'
        );

        $this->addForeignKey(
            'fk-ticket-flight_id',
            'ticket',
            'flight_id',
            'flight',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-ticket-user_id',
            'ticket',
            'user_id'
        );

        $this->addForeignKey(
            'fk-ticket-user_id',
            'ticket',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-ticket-flight_id',
            'ticket'
        );

        $this->dropIndex(
            'idx-ticket-flight_id',
            'ticket'
        );

        $this->dropForeignKey(
            'fk-ticket-user_id',
            'ticket'
        );

        $this->dropIndex(
            'idx-ticket-user_id',
            'ticket'
        );

        $this->dropTable('{{%ticket}}');
    }
}
