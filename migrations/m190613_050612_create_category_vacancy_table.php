<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%category_vacancy}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%category}}`
 * - `{{%vacancy}}`
 */
class m190613_050612_create_category_vacancy_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%category_vacancy}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'vacancy_id' => $this->integer()->notNull(),
        ]);

        // creates index for column `category_id`
        $this->createIndex(
            '{{%idx-category_vacancy-category_id}}',
            '{{%category_vacancy}}',
            'category_id'
        );

        // add foreign key for table `{{%category}}`
        $this->addForeignKey(
            '{{%fk-category_vacancy-category_id}}',
            '{{%category_vacancy}}',
            'category_id',
            '{{%category}}',
            'id',
            'CASCADE'
        );

        // creates index for column `vacancy_id`
        $this->createIndex(
            '{{%idx-category_vacancy-vacancy_id}}',
            '{{%category_vacancy}}',
            'vacancy_id'
        );

        // add foreign key for table `{{%vacancy}}`
        $this->addForeignKey(
            '{{%fk-category_vacancy-vacancy_id}}',
            '{{%category_vacancy}}',
            'vacancy_id',
            '{{%vacancy}}',
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
            '{{%fk-category_vacancy-category_id}}',
            '{{%category_vacancy}}'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            '{{%idx-category_vacancy-category_id}}',
            '{{%category_vacancy}}'
        );

        // drops foreign key for table `{{%vacancy}}`
        $this->dropForeignKey(
            '{{%fk-category_vacancy-vacancy_id}}',
            '{{%category_vacancy}}'
        );

        // drops index for column `vacancy_id`
        $this->dropIndex(
            '{{%idx-category_vacancy-vacancy_id}}',
            '{{%category_vacancy}}'
        );

        $this->dropTable('{{%category_vacancy}}');
    }
}
