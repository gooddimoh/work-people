<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%notification}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m190809_072221_create_notification_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%notification}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'status' => $this->integer()->notNull()->defaultValue(20),
            'title' => $this->string()->notNull(),
            'title_html' => $this->string()->notNull(),
            'text' => $this->text()->notNull(),
            'created_at' => $this->integer(),
        ]);

        $this->addCommentOnColumn('notification', 'status', '10 - readed, 20 - unreaded');

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-notification-user_id}}',
            '{{%notification}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-notification-user_id}}',
            '{{%notification}}',
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
            '{{%fk-notification-user_id}}',
            '{{%notification}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-notification-user_id}}',
            '{{%notification}}'
        );

        $this->dropTable('{{%notification}}');
    }
}
