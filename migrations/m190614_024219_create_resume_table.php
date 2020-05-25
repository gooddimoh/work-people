<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%resume}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m190614_024219_create_resume_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%resume}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'status' => $this->integer()->defaultValue(10),
            'title' => $this->string()->defaultValue(null),
            'use_title' => $this->integer()->defaultValue(20)->notNull(),
            'job_experience' => $this->text(),
            'use_job_experience' => $this->integer()->defaultValue(20)->notNull(),
            'language' => $this->string()->defaultValue(null),
            'use_language' => $this->integer()->defaultValue(20)->notNull(),
            'relocation_possible' => $this->integer()->defaultValue(20),
            'full_import_description' => $this->text(),
            'full_import_description_cleaned' => $this->text(),
            'use_full_import_description_cleaned' => $this->integer()->defaultValue(20)->notNull(),
            'source_url' => $this->string()->defaultValue(null),
            'first_name' => $this->string()->notNull(),
            'last_name' => $this->string()->notNull(),
            'middle_name' => $this->string()->defaultValue(null),
            'email' => $this->string()->notNull(),
            'gender_list' => $this->string()->notNull(),
            'birth_day' => $this->date()->defaultValue(null),
            'country_name' => $this->string()->notNull(),
            'country_city_id' => $this->integer()->notNull(),
            'desired_salary' => $this->float()->defaultValue(null),
            'desired_salary_per_hour' => $this->float()->defaultValue(null),
            'desired_salary_currency_code' => $this->string()->defaultValue(null),
            'desired_country_of_work' => $this->string()->defaultValue(null),
            'photo_path' => $this->string()->defaultValue(null),
            'phone' => $this->string(),
            'custom_country' => $this->string(),
            'description' => $this->text(),
            'creation_time' => $this->integer()->notNull(),
            'upvote_time' => $this->integer()->defaultValue(null),
            'update_time' => $this->integer()->notNull(),
        ]);

        $this->addCommentOnColumn('resume','title','job title for imported records');
        $this->addCommentOnColumn('resume','use_title','10 - use job_title, 20 - use relation category job');
        $this->addCommentOnColumn('resume','job_experience','worker experience for imported records');
        $this->addCommentOnColumn('resume','use_job_experience','10 - use job_experience, 20 - use relation resume_job');
        $this->addCommentOnColumn('resume','language','language for imported records');
        $this->addCommentOnColumn('resume','use_language','10 - use language, 20 - use relation resume_language');
        $this->addCommentOnColumn('resume','relocation_possible','10 - possible, 20 - unknown');
        $this->addCommentOnColumn('resume','full_import_description','Full imported description for admin');
        $this->addCommentOnColumn('resume','full_import_description_cleaned','Full imported description for admin');
        $this->addCommentOnColumn('resume','desired_country_of_work','variants like: RU;CN;UZ; - RU; - RUSSIAN, CN; - CHINA.. etc');
        $this->addCommentOnColumn('resume','phone','phone numbers siparated by;');
        $this->addCommentOnColumn('resume','status','10 - visible, 20 - hidden');
        $this->addCommentOnColumn('resume','gender_list','10; - male, 20; - female;');
        $this->addCommentOnColumn('resume','upvote_time','manual up vote - refresh time');

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-resume-user_id}}',
            '{{%resume}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-resume-user_id}}',
            '{{%resume}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `title`
        $this->createIndex(
            '{{%idx-resume-title}}',
            '{{%resume}}',
            'title'
        );

        // creates index for column `country_city_id`
        $this->createIndex(
            '{{%idx-resume-country_city_id}}',
            '{{%resume}}',
            'country_city_id'
        );

        // add foreign key for table `{{%country_city}}`
        $this->addForeignKey(
            '{{%fk-resume-country_city_id}}',
            '{{%resume}}',
            'country_city_id',
            '{{%country_city}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-resume-user_id}}',
            '{{%resume}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-resume-user_id}}',
            '{{%resume}}'
        );

        // drops index for column `title`
        $this->dropIndex(
            '{{%idx-resume-title}}',
            '{{%resume}}'
        );

        // drops foreign key for table `{{%country_city}}`
        $this->dropForeignKey(
            '{{%fk-resume-country_city_id}}',
            '{{%resume}}'
        );

        // drops index for column `country_city_id`
        $this->dropIndex(
            '{{%idx-resume-country_city_id}}',
            '{{%resume}}'
        );

        $this->dropTable('{{%resume}}');
    }
}
