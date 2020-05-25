<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%safe_deal_user}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%safe_deal}}`
 * - `{{%user}}`
 */
class m190808_045554_create_safe_deal_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%safe_deal_user}}', [
            'id' => $this->primaryKey(),
            'safe_deal_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'archive' => $this->integer()->notNull()->defaultValue(10),
        ]);

        $this->addCommentOnColumn('safe_deal_user','archive','10 - default, 20 - archive');

        // creates index for column `safe_deal_id`
        $this->createIndex(
            '{{%idx-safe_deal_user-safe_deal_id}}',
            '{{%safe_deal_user}}',
            'safe_deal_id'
        );

        // add foreign key for table `{{%safe_deal}}`
        $this->addForeignKey(
            '{{%fk-safe_deal_user-safe_deal_id}}',
            '{{%safe_deal_user}}',
            'safe_deal_id',
            '{{%safe_deal}}',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-safe_deal_user-user_id}}',
            '{{%safe_deal_user}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-safe_deal_user-user_id}}',
            '{{%safe_deal_user}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for columns `safe_deal_id` and `user_id`
        $this->createIndex(
            'idx-safe_deal_user-safe_deal_id-user_id',
            '{{%safe_deal_user}}',
            ['safe_deal_id','user_id'],
            true // one unique user per one safe_deal
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%safe_deal}}`
        $this->dropForeignKey(
            '{{%fk-safe_deal_user-safe_deal_id}}',
            '{{%safe_deal_user}}'
        );

        // drops index for column `safe_deal_id`
        $this->dropIndex(
            '{{%idx-safe_deal_user-safe_deal_id}}',
            '{{%safe_deal_user}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-safe_deal_user-user_id}}',
            '{{%safe_deal_user}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-safe_deal_user-user_id}}',
            '{{%safe_deal_user}}'
        );

        // drops index for columns `safe_deal_id` and `user_id`
        $this->dropIndex(
            'idx-safe_deal_user-safe_deal_id-user_id',
            '{{%safe_deal_user}}'
        );

        $this->dropTable('{{%safe_deal_user}}');
    }
}
