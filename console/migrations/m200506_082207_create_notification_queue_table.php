<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%notification_queue}}`.
 */
class m200506_082207_create_notification_queue_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%notification_queue}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'flight_id' => $this->integer(),
            'notification_type' => $this->tinyInteger()->defaultValue(3)
        ]);

        $this->createIndex(
            'idx-notification_queue-flight_id',
            'notification_queue',
            'flight_id'
        );

        $this->addForeignKey(
            'fk-notification_queue-flight_id',
            'notification_queue',
            'flight_id',
            'flight',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-notification_queue-user_id',
            'notification_queue',
            'user_id'
        );

        $this->addForeignKey(
            'fk-notification_queue-user_id',
            'notification_queue',
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
            'fk-notification_queue-flight_id',
            'notification_queue'
        );

        $this->dropIndex(
            'idx-notification_queue-flight_id',
            'notification_queue'
        );

        $this->dropForeignKey(
            'fk-notification_queue-user_id',
            'notification_queue'
        );

        $this->dropIndex(
            'idx-notification_queue-user_id',
            'notification_queue'
        );

        $this->dropTable('{{%notification_queue}}');
    }
}
