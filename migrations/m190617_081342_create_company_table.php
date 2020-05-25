<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%company}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m190617_081342_create_company_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%company}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'status' => $this->integer()->defaultValue(20)->notNull(),
            'company_name' => $this->string()->notNull(),
            'company_phone' => $this->string()->defaultValue(null),
            'company_email' => $this->string()->defaultValue(null),
            'company_country_code' => $this->string()->notNull(),
            'country_city_id' => $this->integer(),
            'logo' => $this->string()->defaultValue(null),
            'type' => $this->integer()->defaultValue(10)->notNull(),
            'type_industry' => $this->string(),
            'number_of_employees' => $this->integer()->defaultValue(10)->notNull(),
            'site' => $this->string(),
            'description' => $this->text(),
            'document_code' => $this->string(),
        ]);

        $this->addCommentOnColumn('company','status','10 - verified; 20 - not verified');
        $this->addCommentOnColumn('company','type','10 - employer; 20 - HR agency');
        $this->addCommentOnColumn('company','number_of_employees','select list: 10 - 1-19; 20 - 20-39; 40 - 40-59; 60 - 60-99');
        $this->addCommentOnColumn('company','company_phone','company primary phone number');
        $this->addCommentOnColumn('company','company_country_code','country code like:  RU; - RUSSIAN, CN; - CHINA.. etc');

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-company-user_id}}',
            '{{%company}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-company-user_id}}',
            '{{%company}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `country_city_id`
        $this->createIndex(
            '{{%idx-company-country_city_id}}',
            '{{%company}}',
            'country_city_id'
        );

        // add foreign key for table `{{%country_city}}`
        $this->addForeignKey(
            '{{%fk-company-country_city_id}}',
            '{{%company}}',
            'country_city_id',
            '{{%country_city}}',
            'id'
        );

        //-- add foreign keys for `vacancy` table
        // creates index for column `company_id`
        $this->createIndex(
            '{{%idx-vacancy-company_id}}',
            '{{%vacancy}}',
            'company_id'
        ); 
 
        // add foreign key for table `{{%company}}`
        $this->addForeignKey(
            '{{%fk-vacancy-company_id}}',
            '{{%vacancy}}',
            'company_id',
            '{{%company}}',
            'id',
            'CASCADE'
        );
        // --
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // -- drop foreign keys for `vacancy` table
        // drops foreign key for table `{{%company}}`
        $this->dropForeignKey(
            '{{%fk-vacancy-company_id}}',
            '{{%vacancy}}'
        );

        // drops index for column `company_id`
        $this->dropIndex(
            '{{%idx-vacancy-company_id}}',
            '{{%vacancy}}'
        );
        // --

        // drops foreign key for table `{{%country_city}}`
        $this->dropForeignKey(
            '{{%fk-company-country_city_id}}',
            '{{%company}}'
        );

        // drops index for column `country_city_id`
        $this->dropIndex(
            '{{%idx-company-country_city_id}}',
            '{{%company}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-company-user_id}}',
            '{{%company}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-company-user_id}}',
            '{{%company}}'
        );

        $this->dropTable('{{%company}}');
    }
}
