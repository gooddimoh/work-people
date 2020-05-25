<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%vacancy}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%company}}`
 */
class m190613_022426_create_vacancy_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%vacancy}}', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer()->notNull(), // FK see in > m190617_081342_create_company_table.php
            'user_phone_id' => $this->integer()->notNull(),
            'category_job_id' => $this->integer()->notNull(),
            'status' => $this->integer()->defaultValue(10)->notNull(),
            'pin_position' => $this->integer()->defaultValue(0)->notNull(),
            'special_status' => $this->integer()->defaultValue(10)->notNull(),
            'show_on_main_page' => $this->integer()->defaultValue(20)->notNull(),
            'main_page_priority' => $this->integer()->defaultValue(0)->notNull(),
            'title' => $this->string()->notNull(),
            'company_name' => $this->string()->defaultValue(null),
            'gender_list' => $this->string()->notNull(),
            'age_min' => $this->integer()->notNull()->defaultValue(18),
            'age_max' => $this->integer()->notNull()->defaultValue(90),
            'employment_type' => $this->string(),
            'worker_country_codes' => $this->string()->defaultValue(null),
            'free_places' => $this->integer()->defaultValue(1)->notNull(),
            'regular_places' => $this->integer()->defaultValue(10)->notNull(),
            'date_start' => $this->date(),
            'date_end' => $this->date(),
            'date_free' => $this->integer()->defaultValue(10)->notNull(),
            'country_name' => $this->string()->notNull(),
            'country_city_id' => $this->integer(),
            'salary_per_hour_min' => $this->float()->notNull(),
            'salary_per_hour_max' => $this->float(),
            'salary_per_hour_min_src' => $this->float()->notNull(),
            'salary_per_hour_max_src' => $this->float(),
            'currency_code' => $this->string()->notNull(),
            'hours_per_day_min' => $this->float()->notNull(),
            'hours_per_day_max' => $this->float(),
            'days_per_week_min' => $this->float()->notNull(),
            'days_per_week_max' => $this->float(),
            'prepaid_expense_min' => $this->float()->notNull(),
            'prepaid_expense_max' => $this->float(),
            'type_of_working_shift' => $this->string()->notNull(),
            'residence_provided' => $this->integer()->defaultValue(10)->notNull(),
            'residence_amount' => $this->float(),
            'residence_amount_currency_code' => $this->string(),
            'residence_people_per_room' => $this->integer(),
            'documents_provided' => $this->string(),
            'documents_required' => $this->string(),
            'source_url' => $this->string(1000)->defaultValue(null),
            'full_import_description' => $this->text(),
            'full_import_description_cleaned' => $this->text(),
            'use_full_import_description_cleaned' => $this->integer()->defaultValue(20)->notNull(),
            'job_description' => $this->text()->notNull(),
            'job_description_bonus' => $this->text(),
            'contact_name' => $this->string(),
            'contact_phone' => $this->string()->notNull(),
            'contact_email_list' => $this->string()->notNull(),
            'main_image' => $this->string(),
            'agency_accept' => $this->integer()->defaultValue(10)->notNull(),
            'agency_paid_document' => $this->integer()->defaultValue(10)->notNull(),
            'agency_paid_document_price' => $this->float(),
            'agency_paid_document_currency_code' => $this->string(),
            'agency_free_document' => $this->integer()->defaultValue(10)->notNull(),
            'agency_pay_commission' => $this->integer()->defaultValue(10)->notNull(),
            'agency_pay_commission_amount' => $this->float(),
            'agency_pay_commission_currency_code' => $this->string(),
            'secure_deal' => $this->integer()->defaultValue(10)->notNull(),
            'meta_keywords' => $this->text(),
            'meta_description' => $this->text(),
            'creation_time' => $this->integer()->notNull(),
            'upvote_time' => $this->integer()->defaultValue(null),
            'update_time' => $this->integer()->notNull(),
        ]);

        $this->addCommentOnColumn('vacancy','user_phone_id','additional information about worker who posted this job');
        $this->addCommentOnColumn('vacancy','title','for parsed vacancy, use it if not empty');
        $this->addCommentOnColumn('vacancy','status','10 - show; 20 - hide');
        $this->addCommentOnColumn('vacancy','gender_list','variants like: 10;20;30; - 10; - male, 20; - female; 30; - pair');
        $this->addCommentOnColumn('vacancy','employment_type','variants like: 10;30; - 10; - full-time, 20; - shift method, 30; - part-time, 40; - shift work');
        $this->addCommentOnColumn('vacancy','worker_country_codes','variants like: RU;CN;UZ; - RU; - RUSSIAN, CN; - CHINA.. etc, if null then for all countries');
        $this->addCommentOnColumn('vacancy','salary_per_hour_min_src','converted to main currency for better ordering and search');
        $this->addCommentOnColumn('vacancy','salary_per_hour_max_src','converted to main currency for better ordering and search');
        $this->addCommentOnColumn('vacancy','type_of_working_shift','variants like: 10;30; - 10; - day, 20; - night, 30; - evening');
        $this->addCommentOnColumn('vacancy','residence_provided','10 - provided, 20 - not provided');
        $this->addCommentOnColumn('vacancy','documents_provided','variants like: 10;30; - 10; - wokring visa, 20; - Residence');
        $this->addCommentOnColumn('vacancy','documents_required','variants like: 10;30; - 10; - biometric passport, 20; - wokring visa, 30; - residence, 40; - permanent residence, 50; - EU citizenship');
        $this->addCommentOnColumn('vacancy','agency_paid_document','10 - accept, 20 - reject');
        $this->addCommentOnColumn('vacancy','agency_free_document','10 - accept, 20 - reject');
        $this->addCommentOnColumn('vacancy','agency_pay_commission','10 - accept, 20 - reject');
        $this->addCommentOnColumn('vacancy','secure_deal','10 - secure pay, 20 - direct pay');
        $this->addCommentOnColumn('vacancy','contact_phone','additional contact phone numbers spliced by ;');
        $this->addCommentOnColumn('vacancy','upvote_time','manual up vote - refresh time');

        // creates index for column `category_job_id`
        $this->createIndex(
            '{{%idx-vacancy-category_job_id}}',
            '{{%vacancy}}',
            'category_job_id'
        );

        // add foreign key for table `{{%category_job}}`
        $this->addForeignKey(
            '{{%fk-vacancy-category_job_id}}',
            '{{%vacancy}}',
            'category_job_id',
            '{{%category_job}}',
            'id',
            'CASCADE'
        );
        
        // creates index for column `user_phone_id`
        $this->createIndex(
            '{{%idx-vacancy-user_phone_id}}',
            '{{%vacancy}}',
            'user_phone_id'
        );

        // add foreign key for table `{{%user_phone}}`
        $this->addForeignKey(
            '{{%fk-vacancy-user_phone_id}}',
            '{{%vacancy}}',
            'user_phone_id',
            '{{%user_phone}}',
            'id',
            'CASCADE'
        );

        // creates index for column `status`
        $this->createIndex(
            '{{%idx-vacancy-status}}',
            '{{%vacancy}}',
            'status'
        );

        // creates index for column `pin_position`
        $this->createIndex(
            '{{%idx-vacancy-pin_position}}',
            '{{%vacancy}}',
            'pin_position'
        );

        // creates index for column `special_status`
        $this->createIndex(
            '{{%idx-vacancy-special_status}}',
            '{{%vacancy}}',
            'special_status'
        );

        // creates index for column `show_on_main_page`
        $this->createIndex(
            '{{%idx-vacancy-show_on_main_page}}',
            '{{%vacancy}}',
            'show_on_main_page'
        );

        // creates index for column `main_page_priority`
        $this->createIndex(
            '{{%idx-vacancy-main_page_priority}}',
            '{{%vacancy}}',
            'main_page_priority'
        );

        // creates index for column `employment_type`
        $this->createIndex(
            '{{%idx-vacancy-employment_type}}',
            '{{%vacancy}}',
            'employment_type'
        );

        // creates index for column `worker_country_codes`
        $this->createIndex(
            '{{%idx-vacancy-worker_country_codes}}',
            '{{%vacancy}}',
            'worker_country_codes'
        );

        // creates index for column `gender_list`
        $this->createIndex(
            '{{%idx-vacancy-gender_list}}',
            '{{%vacancy}}',
            'gender_list'
        );

        // creates index for column `country_name`
        $this->createIndex(
            '{{%idx-vacancy-country_name}}',
            '{{%vacancy}}',
            'country_name'
        );

        // creates index for column `country_city_id`
        $this->createIndex(
            '{{%idx-vacancy-country_city_id}}',
            '{{%vacancy}}',
            'country_city_id'
        );

        // add foreign key for table `{{%country_city}}`
        $this->addForeignKey(
            '{{%fk-vacancy-country_city_id}}',
            '{{%vacancy}}',
            'country_city_id',
            '{{%country_city}}',
            'id'
        );

        // creates index for column `salary_per_hour_min_src`
        $this->createIndex(
            '{{%idx-vacancy-salary_per_hour_min_src}}',
            '{{%vacancy}}',
            'salary_per_hour_min_src'
        );

        // creates index for column `salary_per_hour_max_src`
        $this->createIndex(
            '{{%idx-vacancy-salary_per_hour_max_src}}',
            '{{%vacancy}}',
            'salary_per_hour_max_src'
        );
        
        // creates index for column `hours_per_day_min`
        $this->createIndex(
            '{{%idx-vacancy-hours_per_day_min}}',
            '{{%vacancy}}',
            'hours_per_day_min'
        );
        
        // creates index for column `hours_per_day_max`
        $this->createIndex(
            '{{%idx-vacancy-hours_per_day_max}}',
            '{{%vacancy}}',
            'hours_per_day_max'
        );

        // creates index for column `days_per_week_min`
        $this->createIndex(
            '{{%idx-vacancy-days_per_week_min}}',
            '{{%vacancy}}',
            'days_per_week_min'
        );
        
        // creates index for column `days_per_week_max`
        $this->createIndex(
            '{{%idx-vacancy-days_per_week_max}}',
            '{{%vacancy}}',
            'days_per_week_max'
        );
        
        // creates index for column `type_of_working_shift`
        $this->createIndex(
            '{{%idx-vacancy-type_of_working_shift}}',
            '{{%vacancy}}',
            'type_of_working_shift'
        );
        
        // creates index for column `residence_provided`
        $this->createIndex(
            '{{%idx-vacancy-residence_provided}}',
            '{{%vacancy}}',
            'residence_provided'
        );
        
        // creates index for column `documents_provided`
        $this->createIndex(
            '{{%idx-vacancy-documents_provided}}',
            '{{%vacancy}}',
            'documents_provided'
        );
        
        // creates index for column `documents_required`
        $this->createIndex(
            '{{%idx-vacancy-documents_required}}',
            '{{%vacancy}}',
            'documents_required'
        );
        
        // creates index for column `agency_accept`
        $this->createIndex(
            '{{%idx-vacancy-agency_accept}}',
            '{{%vacancy}}',
            'agency_accept'
        );
        
        // creates index for column `secure_deal`
        $this->createIndex(
            '{{%idx-vacancy-secure_deal}}',
            '{{%vacancy}}',
            'secure_deal'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%category_job}}`
        $this->dropForeignKey(
            '{{%fk-vacancy-category_job_id}}',
            '{{%vacancy}}'
        );

        // drops index for column `category_job_id`
        $this->dropIndex(
            '{{%idx-vacancy-category_job_id}}',
            '{{%vacancy}}'
        );

        // drops foreign key for table `{{%user_phone}}`
        $this->dropForeignKey(
            '{{%fk-vacancy-user_phone_id}}',
            '{{%vacancy}}'
        );

        // drops index for column `user_phone_id`
        $this->dropIndex(
            '{{%idx-vacancy-user_phone_id}}',
            '{{%vacancy}}'
        );
        
        // drops index for column `pin_position`
        $this->dropIndex(
            '{{%idx-vacancy-status}}',
            '{{%vacancy}}'
        );

        // drops index for column `pin_position`
        $this->dropIndex(
            '{{%idx-vacancy-pin_position}}',
            '{{%vacancy}}'
        );

        // drops index for column `special_status`
        $this->dropIndex(
            '{{%idx-vacancy-special_status}}',
            '{{%vacancy}}'
        );

        // drops index for column `show_on_main_page`
        $this->dropIndex(
            '{{%idx-vacancy-show_on_main_page}}',
            '{{%vacancy}}'
        );

        // drops index for column `main_page_priority`
        $this->dropIndex(
            '{{%idx-vacancy-main_page_priority}}',
            '{{%vacancy}}'
        );

        // drops index for column `employment_type`
        $this->dropIndex(
            '{{%idx-vacancy-employment_type}}',
            '{{%vacancy}}'
        );

        // drops index for column `worker_country_codes`
        $this->dropIndex(
            '{{%idx-vacancy-worker_country_codes}}',
            '{{%vacancy}}'
        );

        // drops index for column `gender_list`
        $this->dropIndex(
            '{{%idx-vacancy-gender_list}}',
            '{{%vacancy}}'
        );

        // drops index for column `country_name`
        $this->dropIndex(
            '{{%idx-vacancy-country_name}}',
            '{{%vacancy}}'
        );

        // drops foreign key for table `{{%country_city}}`
        $this->dropForeignKey(
            '{{%fk-vacancy-country_city_id}}',
            '{{%vacancy}}'
        );

        // drops index for column `country_city_id`
        $this->dropIndex(
            '{{%idx-vacancy-country_city_id}}',
            '{{%vacancy}}'
        );

        // drops index for column `salary_per_hour_min_src`
        $this->dropIndex(
            '{{%idx-vacancy-salary_per_hour_min_src}}',
            '{{%vacancy}}'
        );

        // drops index for column `salary_per_hour_max_src`
        $this->dropIndex(
            '{{%idx-vacancy-salary_per_hour_max_src}}',
            '{{%vacancy}}'
        );
        
        // drops index for column `hours_per_day_min`
        $this->dropIndex(
            '{{%idx-vacancy-hours_per_day_min}}',
            '{{%vacancy}}'
        );
        
        // drops index for column `hours_per_day_max`
        $this->dropIndex(
            '{{%idx-vacancy-hours_per_day_max}}',
            '{{%vacancy}}'
        );
        
        // drops index for column `days_per_week_min`
        $this->dropIndex(
            '{{%idx-vacancy-days_per_week_min}}',
            '{{%vacancy}}'
        );
        
        // drops index for column `days_per_week_max`
        $this->dropIndex(
            '{{%idx-vacancy-days_per_week_max}}',
            '{{%vacancy}}'
        );
        
        // drops index for column `type_of_working_shift`
        $this->dropIndex(
            '{{%idx-vacancy-type_of_working_shift}}',
            '{{%vacancy}}'
        );
        
        // drops index for column `residence_provided`
        $this->dropIndex(
            '{{%idx-vacancy-residence_provided}}',
            '{{%vacancy}}'
        );
        
        // drops index for column `documents_provided`
        $this->dropIndex(
            '{{%idx-vacancy-documents_provided}}',
            '{{%vacancy}}'
        );
        
        // drops index for column `documents_required`
        $this->dropIndex(
            '{{%idx-vacancy-documents_required}}',
            '{{%vacancy}}'
        );
        
        // drops index for column `agency_accept`
        $this->dropIndex(
            '{{%idx-vacancy-agency_accept}}',
            '{{%vacancy}}'
        );
        
        // drops index for column `secure_deal`
        $this->dropIndex(
            '{{%idx-vacancy-secure_deal}}',
            '{{%vacancy}}'
        );

        $this->dropTable('{{%vacancy}}');
    }
}
