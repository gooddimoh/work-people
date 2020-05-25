<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%category_resume}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%category}}`
 * - `{{%resume}}`
 */
class m190617_035158_create_category_resume_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%category_resume}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'resume_id' => $this->integer()->notNull(),
        ]);

        // creates index for column `category_id`
        $this->createIndex(
            '{{%idx-category_resume-category_id}}',
            '{{%category_resume}}',
            'category_id'
        );

        // add foreign key for table `{{%category}}`
        $this->addForeignKey(
            '{{%fk-category_resume-category_id}}',
            '{{%category_resume}}',
            'category_id',
            '{{%category}}',
            'id',
            'CASCADE'
        );

        // creates index for column `resume_id`
        $this->createIndex(
            '{{%idx-category_resume-resume_id}}',
            '{{%category_resume}}',
            'resume_id'
        );

        // add foreign key for table `{{%resume}}`
        $this->addForeignKey(
            '{{%fk-category_resume-resume_id}}',
            '{{%category_resume}}',
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
        // drops foreign key for table `{{%category}}`
        $this->dropForeignKey(
            '{{%fk-category_resume-category_id}}',
            '{{%category_resume}}'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            '{{%idx-category_resume-category_id}}',
            '{{%category_resume}}'
        );

        // drops foreign key for table `{{%resume}}`
        $this->dropForeignKey(
            '{{%fk-category_resume-resume_id}}',
            '{{%category_resume}}'
        );

        // drops index for column `resume_id`
        $this->dropIndex(
            '{{%idx-category_resume-resume_id}}',
            '{{%category_resume}}'
        );

        $this->dropTable('{{%category_resume}}');
    }
}
