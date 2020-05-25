<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%resume_language}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%resume}}`
 */
class m190617_035203_create_resume_language_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%resume_language}}', [
            'id' => $this->primaryKey(),
            'resume_id' => $this->integer()->notNull(),
            'country_code' => $this->string()->notNull(),
            'level' => $this->integer()->defaultValue(10)->notNull(),
            'can_be_interviewed' => $this->integer()->defaultValue(20)->notNull(),
        ]);

        $this->addCommentOnColumn('resume_language','country_code','RU - RUSSIAN, CN - CHINA.. etc');
        $this->addCommentOnColumn('resume_language','level','10 - newbie, 20 - middle, 30 - hight');
        $this->addCommentOnColumn('resume_language','can_be_interviewed','10 - yes, 20 - no');

        // creates index for column `resume_id`
        $this->createIndex(
            '{{%idx-resume_language-resume_id}}',
            '{{%resume_language}}',
            'resume_id'
        );

        // add foreign key for table `{{%resume}}`
        $this->addForeignKey(
            '{{%fk-resume_language-resume_id}}',
            '{{%resume_language}}',
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
            '{{%fk-resume_language-resume_id}}',
            '{{%resume_language}}'
        );

        // drops index for column `resume_id`
        $this->dropIndex(
            '{{%idx-resume_language-resume_id}}',
            '{{%resume_language}}'
        );

        $this->dropTable('{{%resume_language}}');
    }
}
