<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_favorite_resume}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%resume}}`
 */
class m190719_161104_create_user_favorite_resume_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_favorite_resume}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'resume_id' => $this->integer()->notNull(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_favorite_resume-user_id}}',
            '{{%user_favorite_resume}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-user_favorite_resume-user_id}}',
            '{{%user_favorite_resume}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `resume_id`
        $this->createIndex(
            '{{%idx-user_favorite_resume-resume_id}}',
            '{{%user_favorite_resume}}',
            'resume_id'
        );

        // add foreign key for table `{{%resume}}`
        $this->addForeignKey(
            '{{%fk-user_favorite_resume-resume_id}}',
            '{{%user_favorite_resume}}',
            'resume_id',
            '{{%resume}}',
            'id',
            'CASCADE'
        );

        // creates index for columns `user_id` and `resume_id`
        $this->createIndex(
            'idx-user_favorite_resume-user_id-resume_id',
            '{{%user_favorite_resume}}',
            ['user_id','resume_id'],
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
            '{{%fk-user_favorite_resume-user_id}}',
            '{{%user_favorite_resume}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_favorite_resume-user_id}}',
            '{{%user_favorite_resume}}'
        );

        // drops foreign key for table `{{%resume}}`
        $this->dropForeignKey(
            '{{%fk-user_favorite_resume-resume_id}}',
            '{{%user_favorite_resume}}'
        );

        // drops index for column `resume_id`
        $this->dropIndex(
            '{{%idx-user_favorite_resume-resume_id}}',
            '{{%user_favorite_resume}}'
        );

        // drops index for columns `user_id` and `resume_id`
        $this->dropIndex(
            'idx-user_favorite_resume-user_id-resume_id',
            '{{%user_favorite_resume}}'
        );

        $this->dropTable('{{%user_favorite_resume}}');
    }
}
