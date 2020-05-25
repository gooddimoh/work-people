<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_attachment}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m190730_014817_create_user_attachment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_attachment}}', [
            'id' => $this->primaryKey(),
            'status' => $this->integer()->notNull()->defaultValue(10),
            'user_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'path_name' => $this->string()->notNull(),
            'size' => $this->float(),
            'created_at' => $this->integer(),
        ]);

        $this->addCommentOnColumn('user_attachment', 'name', 'source file name');
        $this->addCommentOnColumn('user_attachment', 'path_name', 'file name in file system');
        $this->addCommentOnColumn('user_attachment', 'size', 'file size, to controll user quotas');

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_attachment-user_id}}',
            '{{%user_attachment}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-user_attachment-user_id}}',
            '{{%user_attachment}}',
            'user_id',
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
            '{{%fk-user_attachment-user_id}}',
            '{{%user_attachment}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_attachment-user_id}}',
            '{{%user_attachment}}'
        );

        $this->dropTable('{{%user_attachment}}');
    }
}
