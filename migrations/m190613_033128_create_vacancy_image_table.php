<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%vacancy_image}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%vacancy}}`
 */
class m190613_033128_create_vacancy_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%vacancy_image}}', [
            'id' => $this->primaryKey(),
            'vacancy_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'path_name' => $this->string()->notNull(),
        ]);

        // creates index for column `vacancy_id`
        $this->createIndex(
            '{{%idx-vacancy_image-vacancy_id}}',
            '{{%vacancy_image}}',
            'vacancy_id'
        );

        // add foreign key for table `{{%vacancy}}`
        $this->addForeignKey(
            '{{%fk-vacancy_image-vacancy_id}}',
            '{{%vacancy_image}}',
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
            '{{%fk-vacancy_image-vacancy_id}}',
            '{{%vacancy_image}}'
        );

        // drops index for column `vacancy_id`
        $this->dropIndex(
            '{{%idx-vacancy_image-vacancy_id}}',
            '{{%vacancy_image}}'
        );

        $this->dropTable('{{%vacancy_image}}');
    }
}
