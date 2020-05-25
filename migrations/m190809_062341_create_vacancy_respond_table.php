<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%vacancy_respond}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%vacancy}}`
 * - `{{%resume}}`
 */
class m190809_062341_create_vacancy_respond_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%vacancy_respond}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'vacancy_id' => $this->integer()->notNull(),
            'for_user_id' => $this->integer()->notNull(),
            'resume_id' => $this->integer(),
            'status' => $this->integer()->notNull()->defaultValue(10),
            'message' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->addCommentOnColumn('vacancy_respond', 'user_id', 'worker');
        $this->addCommentOnColumn('vacancy_respond', 'for_user_id', 'vacancy user_id need for optimization join requests');
        $this->addCommentOnColumn('vacancy_respond', 'resume_id', 'user can attach resume to respond');
        $this->addCommentOnColumn('vacancy_respond', 'status', '10 - new respond, 20 - accepted respond, 30 - rejected respond');

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-vacancy_respond-user_id}}',
            '{{%vacancy_respond}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-vacancy_respond-user_id}}',
            '{{%vacancy_respond}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `vacancy_id`
        $this->createIndex(
            '{{%idx-vacancy_respond-vacancy_id}}',
            '{{%vacancy_respond}}',
            'vacancy_id'
        );

        // add foreign key for table `{{%vacancy}}`
        $this->addForeignKey(
            '{{%fk-vacancy_respond-vacancy_id}}',
            '{{%vacancy_respond}}',
            'vacancy_id',
            '{{%vacancy}}',
            'id',
            'CASCADE'
        );

        // creates index for column `for_user_id`
        $this->createIndex(
            '{{%idx-vacancy_respond-for_user_id}}',
            '{{%vacancy_respond}}',
            'for_user_id'
        );

        // creates index for column `resume_id`
        $this->createIndex(
            '{{%idx-vacancy_respond-resume_id}}',
            '{{%vacancy_respond}}',
            'resume_id'
        );

        // add foreign key for table `{{%resume}}`
        $this->addForeignKey(
            '{{%fk-vacancy_respond-resume_id}}',
            '{{%vacancy_respond}}',
            'resume_id',
            '{{%resume}}',
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
            '{{%fk-vacancy_respond-user_id}}',
            '{{%vacancy_respond}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-vacancy_respond-user_id}}',
            '{{%vacancy_respond}}'
        );

        // drops foreign key for table `{{%vacancy}}`
        $this->dropForeignKey(
            '{{%fk-vacancy_respond-vacancy_id}}',
            '{{%vacancy_respond}}'
        );

        // drops index for column `vacancy_id`
        $this->dropIndex(
            '{{%idx-vacancy_respond-vacancy_id}}',
            '{{%vacancy_respond}}'
        );

        // drops index for column `for_user_id`
        $this->dropIndex(
            '{{%idx-vacancy_respond-for_user_id}}',
            '{{%vacancy_respond}}'
        );

        // drops foreign key for table `{{%resume}}`
        $this->dropForeignKey(
            '{{%fk-vacancy_respond-resume_id}}',
            '{{%vacancy_respond}}'
        );

        // drops index for column `resume_id`
        $this->dropIndex(
            '{{%idx-vacancy_respond-resume_id}}',
            '{{%vacancy_respond}}'
        );

        $this->dropTable('{{%vacancy_respond}}');
    }
}
