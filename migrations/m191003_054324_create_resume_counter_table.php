<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%resume_counter}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%resume}}`
 */
class m191003_054324_create_resume_counter_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%resume_counter}}', [
            'id' => $this->primaryKey(),
            'resume_id' => $this->integer()->notNull(),
            'view_count' => $this->integer()->notNull(),
            'open_count' => $this->integer()->notNull()->defaultValue(0),
            'vip_count' => $this->integer()->notNull()->defaultValue(0),
            'top_count' => $this->integer()->notNull()->defaultValue(0),
            'main_page_count' => $this->integer()->notNull()->defaultValue(0),
            'favorite_count' => $this->integer()->notNull()->defaultValue(0),
        ]);

        // creates index for column `resume_id`
        $this->createIndex(
            '{{%idx-resume_counter-resume_id}}',
            '{{%resume_counter}}',
            'resume_id'
        );

        // add foreign key for table `{{%resume}}`
        $this->addForeignKey(
            '{{%fk-resume_counter-resume_id}}',
            '{{%resume_counter}}',
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
            '{{%fk-resume_counter-resume_id}}',
            '{{%resume_counter}}'
        );

        // drops index for column `resume_id`
        $this->dropIndex(
            '{{%idx-resume_counter-resume_id}}',
            '{{%resume_counter}}'
        );

        $this->dropTable('{{%resume_counter}}');
    }
}
