<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%message_user_attachment}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%message}}`
 * - `{{%user_attachment}}`
 */
class m190730_041323_create_message_user_attachment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%message_user_attachment}}', [
            'id' => $this->primaryKey(),
            'message_id' => $this->integer()->notNull(),
            'user_attachment_id' => $this->integer()->notNull(),
        ]);

        // creates index for column `message_id`
        $this->createIndex(
            '{{%idx-message_user_attachment-message_id}}',
            '{{%message_user_attachment}}',
            'message_id'
        );

        // add foreign key for table `{{%message}}`
        $this->addForeignKey(
            '{{%fk-message_user_attachment-message_id}}',
            '{{%message_user_attachment}}',
            'message_id',
            '{{%message}}',
            'id',
            'CASCADE'
        );

        // creates index for column `user_attachment_id`
        $this->createIndex(
            '{{%idx-message_user_attachment-user_attachment_id}}',
            '{{%message_user_attachment}}',
            'user_attachment_id'
        );

        // add foreign key for table `{{%user_attachment}}`
        $this->addForeignKey(
            '{{%fk-message_user_attachment-user_attachment_id}}',
            '{{%message_user_attachment}}',
            'user_attachment_id',
            '{{%user_attachment}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%message}}`
        $this->dropForeignKey(
            '{{%fk-message_user_attachment-message_id}}',
            '{{%message_user_attachment}}'
        );

        // drops index for column `message_id`
        $this->dropIndex(
            '{{%idx-message_user_attachment-message_id}}',
            '{{%message_user_attachment}}'
        );

        // drops foreign key for table `{{%user_attachment}}`
        $this->dropForeignKey(
            '{{%fk-message_user_attachment-user_attachment_id}}',
            '{{%message_user_attachment}}'
        );

        // drops index for column `user_attachment_id`
        $this->dropIndex(
            '{{%idx-message_user_attachment-user_attachment_id}}',
            '{{%message_user_attachment}}'
        );

        $this->dropTable('{{%message_user_attachment}}');
    }
}
