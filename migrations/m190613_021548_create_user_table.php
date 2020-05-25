<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m190613_021548_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'login' => $this->string()->notNull()->unique(),
            'status' => $this->integer()->defaultValue(10),
            'username' => $this->string(),
            'email' => $this->string()->notNull()->unique(),
            'verified_email' => $this->integer()->defaultValue(20),
            'photo_path' => $this->string()->defaultValue(null),
            'role' => $this->string()->notNull()->defaultValue('user'),
            'auth_key' => $this->string(32),
            'password_hash' => $this->string(),
            'password_reset_token' => $this->string(),
            'email_confirm_token' => $this->string(),
            'created_at' => $this->datetime(),
            'updated_at' => $this->datetime(),
            'balance' => $this->decimal(18,2)->defaultValue(0),
        ]);

        $this->addCommentOnColumn('user','verified_email','10 - verified, 20 - not verified');

        $this->insert('user', [
            'id' => '1',
            'login' => 'admin',
            'status' => '10',
            'username' => 'Administrator',
            'email' => 'admin@admin.ad',
            'role' => 'administrator',
            'password_hash' => '$2y$13$G94rB0eE2v4gNzHFLMwGTurYD9weJ0qOZJ/5CimfnwKUrbwbiyU.q',
        ]);

        $this->insert('user', [
            'id' => '100',
            'login' => 'parser',
            'status' => '10',
            'username' => 'Parser',
            'email' => 'parser@parser.pa',
            'role' => 'user',
            'password_hash' => '$2y$13$4UwL/ZiWMfHeep5haFDa5u8caGH8aaqyAjqG0gwlgMTF.lL228oga',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
