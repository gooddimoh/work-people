<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%vacancy_counter}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%vacancy}}`
 */
class m190801_054310_create_vacancy_counter_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%vacancy_counter}}', [
            'id' => $this->primaryKey(),
            'vacancy_id' => $this->integer()->unique()->notNull()->defaultValue(0),
            'view_count' => $this->integer()->notNull()->defaultValue(0),
            'open_count' => $this->integer()->notNull()->defaultValue(0),
            'vip_count' => $this->integer()->notNull()->defaultValue(0),
            'top_count' => $this->integer()->notNull()->defaultValue(0),
            'main_page_count' => $this->integer()->notNull()->defaultValue(0),
            'favorite_count' => $this->integer()->notNull()->defaultValue(0),
        ]);

        // creates index for column `vacancy_id`
        $this->createIndex(
            '{{%idx-vacancy_counter-vacancy_id}}',
            '{{%vacancy_counter}}',
            'vacancy_id'
        );

        // add foreign key for table `{{%vacancy}}`
        $this->addForeignKey(
            '{{%fk-vacancy_counter-vacancy_id}}',
            '{{%vacancy_counter}}',
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
        // drops foreign key for table `{{%vacancy}}`
        $this->dropForeignKey(
            '{{%fk-vacancy_counter-vacancy_id}}',
            '{{%vacancy_counter}}'
        );

        // drops index for column `vacancy_id`
        $this->dropIndex(
            '{{%idx-vacancy_counter-vacancy_id}}',
            '{{%vacancy_counter}}'
        );

        $this->dropTable('{{%vacancy_counter}}');
    }
}
