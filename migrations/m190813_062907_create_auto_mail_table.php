<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%auto_mail}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%category}}`
 */
class m190813_062907_create_auto_mail_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%auto_mail}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'category_id' => $this->integer(),
            'status' => $this->integer()->notNull(),
            'use_messenger' => $this->integer()->notNull(),
            'request' => $this->string(),
            'country_codes' => $this->string(),
            'location' => $this->string(),
            'created_at' => $this->integer(),
        ]);

        $this->addCommentOnColumn('auto_mail', 'status', '10 - active, 20 - not active');
        $this->addCommentOnColumn('auto_mail', 'use_messenger', '10 - send to Telegram or Viber, 20 - not active');
        $this->addCommentOnColumn('auto_mail', 'country_codes', 'variants like: RU;CN;UZ; - RU; - RUSSIAN, CN; - CHINA.. etc');

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-auto_mail-user_id}}',
            '{{%auto_mail}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-auto_mail-user_id}}',
            '{{%auto_mail}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `category_id`
        $this->createIndex(
            '{{%idx-auto_mail-category_id}}',
            '{{%auto_mail}}',
            'category_id'
        );

        // add foreign key for table `{{%category}}`
        $this->addForeignKey(
            '{{%fk-auto_mail-category_id}}',
            '{{%auto_mail}}',
            'category_id',
            '{{%category}}',
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
            '{{%fk-auto_mail-user_id}}',
            '{{%auto_mail}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-auto_mail-user_id}}',
            '{{%auto_mail}}'
        );

        // drops foreign key for table `{{%category}}`
        $this->dropForeignKey(
            '{{%fk-auto_mail-category_id}}',
            '{{%auto_mail}}'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            '{{%idx-auto_mail-category_id}}',
            '{{%auto_mail}}'
        );

        $this->dropTable('{{%auto_mail}}');
    }
}
