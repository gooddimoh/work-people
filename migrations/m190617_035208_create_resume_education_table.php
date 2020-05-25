<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%resume_education}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%resume}}`
 */
class m190617_035208_create_resume_education_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%resume_education}}', [
            'id' => $this->primaryKey(),
            'resume_id' => $this->integer()->notNull(),
            'description' => $this->text()->notNull(),
        ]);

        // creates index for column `resume_id`
        $this->createIndex(
            '{{%idx-resume_education-resume_id}}',
            '{{%resume_education}}',
            'resume_id'
        );

        // add foreign key for table `{{%resume}}`
        $this->addForeignKey(
            '{{%fk-resume_education-resume_id}}',
            '{{%resume_education}}',
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
        // drops foreign key for table `{{%resume}}`
        $this->dropForeignKey(
            '{{%fk-resume_education-resume_id}}',
            '{{%resume_education}}'
        );

        // drops index for column `resume_id`
        $this->dropIndex(
            '{{%idx-resume_education-resume_id}}',
            '{{%resume_education}}'
        );

        $this->dropTable('{{%resume_education}}');
    }
}
