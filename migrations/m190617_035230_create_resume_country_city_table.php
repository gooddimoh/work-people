<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%resume_country_city}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%resume}}`
 * - `{{%country_city}}`
 */
class m190617_035230_create_resume_country_city_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%resume_country_city}}', [
            'id' => $this->primaryKey(),
            'resume_id' => $this->integer()->notNull(),
            'country_city_id' => $this->integer()->notNull(),
        ]);

        // creates index for column `resume_id`
        $this->createIndex(
            '{{%idx-resume_country_city-resume_id}}',
            '{{%resume_country_city}}',
            'resume_id'
        );

        // add foreign key for table `{{%resume}}`
        $this->addForeignKey(
            '{{%fk-resume_country_city-resume_id}}',
            '{{%resume_country_city}}',
            'resume_id',
            '{{%resume}}',
            'id',
            'CASCADE'
        );

        // creates index for column `country_city_id`
        $this->createIndex(
            '{{%idx-resume_country_city-country_city_id}}',
            '{{%resume_country_city}}',
            'country_city_id'
        );

        // add foreign key for table `{{%country_city}}`
        $this->addForeignKey(
            '{{%fk-resume_country_city-country_city_id}}',
            '{{%resume_country_city}}',
            'country_city_id',
            '{{%country_city}}',
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
            '{{%fk-resume_country_city-resume_id}}',
            '{{%resume_country_city}}'
        );

        // drops index for column `resume_id`
        $this->dropIndex(
            '{{%idx-resume_country_city-resume_id}}',
            '{{%resume_country_city}}'
        );

        // drops foreign key for table `{{%country_city}}`
        $this->dropForeignKey(
            '{{%fk-resume_country_city-country_city_id}}',
            '{{%resume_country_city}}'
        );

        // drops index for column `country_city_id`
        $this->dropIndex(
            '{{%idx-resume_country_city-country_city_id}}',
            '{{%resume_country_city}}'
        );

        $this->dropTable('{{%resume_country_city}}');
    }
}
