<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%message_room}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%user}}`
 */
class m190726_063306_create_message_room_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%message_room}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'sender_id' => $this->integer()->notNull(),
            'status' => $this->integer()->notNull()->defaultValue(10),
            'favorite' => $this->integer()->notNull()->defaultValue(20),
        ]);

        $this->addCommentOnColumn('message_room','user_id','room owner ID');
        $this->addCommentOnColumn('message_room','status','10 - active, 20 - archive');
        $this->addCommentOnColumn('message_room','favorite','10 - favorite, 20 - default');

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-message_room-user_id}}',
            '{{%message_room}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-message_room-user_id}}',
            '{{%message_room}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `sender_id`
        $this->createIndex(
            '{{%idx-message_room-sender_id}}',
            '{{%message_room}}',
            'sender_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-message_room-sender_id}}',
            '{{%message_room}}',
            'sender_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-message_room-user_id}}',
            '{{%message_room}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-message_room-user_id}}',
            '{{%message_room}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-message_room-sender_id}}',
            '{{%message_room}}'
        );

        // drops index for column `sender_id`
        $this->dropIndex(
            '{{%idx-message_room-sender_id}}',
            '{{%message_room}}'
        );

        $this->dropTable('{{%message_room}}');
    }
}
