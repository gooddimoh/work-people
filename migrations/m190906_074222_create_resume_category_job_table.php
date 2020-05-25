<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%resume_category_job}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%resume}}`
 * - `{{%category_job}}`
 */
class m190906_074222_create_resume_category_job_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%resume_category_job}}', [
            'id' => $this->primaryKey(),
            'resume_id' => $this->integer()->notNull(),
            'category_job_id' => $this->integer()->notNull(),
        ]);

        // creates index for column `resume_id`
        $this->createIndex(
            '{{%idx-resume_category_job-resume_id}}',
            '{{%resume_category_job}}',
            'resume_id'
        );

        // add foreign key for table `{{%resume}}`
        $this->addForeignKey(
            '{{%fk-resume_category_job-resume_id}}',
            '{{%resume_category_job}}',
            'resume_id',
            '{{%resume}}',
            'id',
            'CASCADE'
        );

        // creates index for column `category_job_id`
        $this->createIndex(
            '{{%idx-resume_category_job-category_job_id}}',
            '{{%resume_category_job}}',
            'category_job_id'
        );

        // add foreign key for table `{{%category_job}}`
        $this->addForeignKey(
            '{{%fk-resume_category_job-category_job_id}}',
            '{{%resume_category_job}}',
            'category_job_id',
            '{{%category_job}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%resume}}`
        $this->dropForeignKey(
            '{{%fk-resume_category_job-resume_id}}',
            '{{%resume_category_job}}'
        );

        // drops index for column `resume_id`
        $this->dropIndex(
            '{{%idx-resume_category_job-resume_id}}',
            '{{%resume_category_job}}'
        );

        // drops foreign key for table `{{%category_job}}`
        $this->dropForeignKey(
            '{{%fk-resume_category_job-category_job_id}}',
            '{{%resume_category_job}}'
        );

        // drops index for column `category_job_id`
        $this->dropIndex(
            '{{%idx-resume_category_job-category_job_id}}',
            '{{%resume_category_job}}'
        );

        $this->dropTable('{{%resume_category_job}}');
    }
}
