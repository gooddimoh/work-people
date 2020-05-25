<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_phone}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m190613_021568_create_user_phone_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_phone}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'verified' => $this->integer()->defaultValue(20),
            'phone' => $this->string()->notNull()->unique(),
            'contact_phone_for_admin' => $this->string()->defaultValue(null),
            'phone_messangers' => $this->string(),
            'company_role' => $this->string()->notNull()->defaultValue("user"),
            'company_worker_name' => $this->string()->defaultValue(null),
            'company_worker_email' => $this->string()->defaultValue(null),
        ]);

        $this->addCommentOnColumn('user_phone', 'verified', '10 - registred account by sms, 20 - just inserted by user, 30 - inserted by parser, 40 - login user');
        $this->addCommentOnColumn('user_phone','phone_messangers','variants like: viber;whatsapp;telegram;');
        $this->addCommentOnColumn('user_phone','company_role','access level for company for this phone number');

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_phone-user_id}}',
            '{{%user_phone}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-user_phone-user_id}}',
            '{{%user_phone}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // insert data
        $this->insert('user_phone', [
            'id' => '1',
            'user_id' => '1',
            'verified' => '40',
            'phone' => 'admin',
        ]);

        $this->insert('user_phone', [
            'id' => '100',
            'user_id' => '100',
            'verified' => '40',
            'phone' => 'parser',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-user_phone-user_id}}',
            '{{%user_phone}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_phone-user_id}}',
            '{{%user_phone}}'
        );

        $this->dropTable('{{%user_phone}}');
    }
}
