<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_favorite_vacancy}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%vacancy}}`
 */
class m190719_161054_create_user_favorite_vacancy_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_favorite_vacancy}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'vacancy_id' => $this->integer()->notNull(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_favorite_vacancy-user_id}}',
            '{{%user_favorite_vacancy}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-user_favorite_vacancy-user_id}}',
            '{{%user_favorite_vacancy}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `vacancy_id`
        $this->createIndex(
            '{{%idx-user_favorite_vacancy-vacancy_id}}',
            '{{%user_favorite_vacancy}}',
            'vacancy_id'
        );

        // add foreign key for table `{{%vacancy}}`
        $this->addForeignKey(
            '{{%fk-user_favorite_vacancy-vacancy_id}}',
            '{{%user_favorite_vacancy}}',
            'vacancy_id',
            '{{%vacancy}}',
            'id',
            'CASCADE'
        );

        // creates index for columns `user_id` and `vacancy_id`
        $this->createIndex(
            'idx-user_favorite_vacancy-user_id-vacancy_id',
            '{{%user_favorite_vacancy}}',
            ['user_id','vacancy_id'],
            true // one favorite vacancy per one user
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-user_favorite_vacancy-user_id}}',
            '{{%user_favorite_vacancy}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_favorite_vacancy-user_id}}',
            '{{%user_favorite_vacancy}}'
        );

        // drops foreign key for table `{{%vacancy}}`
        $this->dropForeignKey(
            '{{%fk-user_favorite_vacancy-vacancy_id}}',
            '{{%user_favorite_vacancy}}'
        );

        // drops index for column `vacancy_id`
        $this->dropIndex(
            '{{%idx-user_favorite_vacancy-vacancy_id}}',
            '{{%user_favorite_vacancy}}'
        );

        // drops index for columns `user_id` and `vacancy_id`
        $this->dropIndex(
            'idx-user_favorite_vacancy-user_id-vacancy_id',
            '{{%user_favorite_vacancy}}'
        );

        $this->dropTable('{{%user_favorite_vacancy}}');
    }
}
