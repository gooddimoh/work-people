<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%message}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%message_room}}`
 * - `{{%user}}`
 */
class m190726_063311_create_message_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%message}}', [
            'id' => $this->primaryKey(),
            'message_room_id' => $this->integer()->notNull(),
            'owner_id' => $this->integer()->notNull(),
            'for_user_id' => $this->integer()->notNull(),
            'status' => $this->integer()->notNull()->defaultValue(20),
            'device_type' => $this->integer()->defaultValue(10),
            'message_text' => $this->text()->notNull(),
            'created_at' => $this->integer(),
        ]);

        $this->addCommentOnColumn('message','status','10 - readed, 20 - unreaded');
        $this->addCommentOnColumn('message','owner_id','message creator ID');
        $this->addCommentOnColumn('message','for_user_id','field need for optimization join request to count new messages');
        $this->addCommentOnColumn('message','device_type','10 - default, 20 - send from mobile');

        // creates index for column `message_room_id`
        $this->createIndex(
            '{{%idx-message-message_room_id}}',
            '{{%message}}',
            'message_room_id'
        );

        // add foreign key for table `{{%message_room}}`
        $this->addForeignKey(
            '{{%fk-message-message_room_id}}',
            '{{%message}}',
            'message_room_id',
            '{{%message_room}}',
            'id',
            'CASCADE'
        );

        // creates index for column `owner_id`
        $this->createIndex(
            '{{%idx-message-owner_id}}',
            '{{%message}}',
            'owner_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-message-owner_id}}',
            '{{%message}}',
            'owner_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-message-for_user_id}}',
            '{{%message}}',
            'for_user_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%message_room}}`
        $this->dropForeignKey(
            '{{%fk-message-message_room_id}}',
            '{{%message}}'
        );

        // drops index for column `message_room_id`
        $this->dropIndex(
            '{{%idx-message-message_room_id}}',
            '{{%message}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-message-owner_id}}',
            '{{%message}}'
        );

        // drops index for column `owner_id`
        $this->dropIndex(
            '{{%idx-message-owner_id}}',
            '{{%message}}'
        );

        // drops index for column `for_user_id`
        $this->dropIndex(
            '{{%idx-message-for_user_id}}',
            '{{%message}}'
        );

        $this->dropTable('{{%message}}');
    }
}
