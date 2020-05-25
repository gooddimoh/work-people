<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%resume_job}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%resume}}`
 */
class m190617_035158_create_resume_job_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%resume_job}}', [
            'id' => $this->primaryKey(),
            'resume_id' => $this->integer()->notNull(),
            'category_job_id' => $this->integer()->notNull(),
            'company_name' => $this->string()->notNull(),
            'month' => $this->integer(),
            'years' => $this->integer()->notNull(),
            'date_start' => $this->date(),
            'for_now' => $this->integer()->defaultValue(20)->notNull(),
            'foreign_job' => $this->integer()->defaultValue(20)->notNull(),
        ]);

        $this->addCommentOnColumn('resume_job','foreign_job','10 - yes, 20 - no');
        $this->addCommentOnColumn('resume_job','for_now','10 - yes, 20 - no');

        // creates index for column `resume_id`
        $this->createIndex(
            '{{%idx-resume_job-resume_id}}',
            '{{%resume_job}}',
            'resume_id'
        );

        // add foreign key for table `{{%resume}}`
        $this->addForeignKey(
            '{{%fk-resume_job-resume_id}}',
            '{{%resume_job}}',
            'resume_id',
            '{{%resume}}',
            'id',
            'CASCADE'
        );

        // creates index for column `category_job_id`
        $this->createIndex(
            '{{%idx-resume_job-category_job_id}}',
            '{{%resume_job}}',
            'category_job_id'
        );

        // add foreign key for table `{{%category_job}}`
        $this->addForeignKey(
            '{{%fk-resume_job-category_job_id}}',
            '{{%resume_job}}',
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
        // drops foreign key for table `{{%category_job}}`
        $this->dropForeignKey(
            '{{%fk-resume_job-category_job_id}}',
            '{{%resume_job}}'
        );

        // drops index for column `category_job_id`
        $this->dropIndex(
            '{{%idx-resume_job-category_job_id}}',
            '{{%resume_job}}'
        );
        
        // drops foreign key for table `{{%resume}}`
        $this->dropForeignKey(
            '{{%fk-resume_job-resume_id}}',
            '{{%resume_job}}'
        );

        // drops index for column `resume_id`
        $this->dropIndex(
            '{{%idx-resume_job-resume_id}}',
            '{{%resume_job}}'
        );

        $this->dropTable('{{%resume_job}}');
    }
}
