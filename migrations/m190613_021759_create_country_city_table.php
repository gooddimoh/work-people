<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%country_city}}`.
 */
class m190613_021759_create_country_city_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%country_city}}', [
            'id' => $this->primaryKey(),
            'priority' => $this->integer()->notNull()->defaultValue(100),
            'country_char_code' => $this->string()->notNull(),
            'city_name' => $this->string()->notNull(),
        ]);

        $this->addCommentOnColumn('country_city', 'priority', 'sort by priority, higher top');
        $this->addCommentOnColumn('country_city', 'country_char_code', 'Country char code like: UA');
        $this->addCommentOnColumn('country_city', 'city_name', 'City name in english');


        $this->insert('country_city', [
            'priority' => '10000',
            'country_char_code' => 'RU',
            'city_name' => 'Moscow',
        ]);

        $this->insert('country_city', [
            'priority' => '9000',
            'country_char_code' => 'RU',
            'city_name' => 'Saint Petersburg',
        ]);

        $this->insert('country_city', [
            'priority' => '5000',
            'country_char_code' => 'RU',
            'city_name' => 'Vladivostok',
        ]);
        
        $this->insert('country_city', [
            'priority' => '10000',
            'country_char_code' => 'UA',
            'city_name' => 'Kiev',
        ]);

        $this->insert('country_city', [
            'priority' => '9000',
            'country_char_code' => 'UA',
            'city_name' => 'Lviv',
        ]);

        $this->insert('country_city', [
            'priority' => '5000',
            'country_char_code' => 'UA',
            'city_name' => 'Zaporizhia',
        ]);
        
        $this->insert('country_city', [
            'priority' => '5000',
            'country_char_code' => 'UA',
            'city_name' => 'Kharkiv',
        ]);
        
        $this->insert('country_city', [
            'priority' => '10000',
            'country_char_code' => 'TJ',
            'city_name' => 'Dushanbe',
        ]);

        $this->insert('country_city', [
            'priority' => '10000',
            'country_char_code' => 'UZ',
            'city_name' => 'Samarqand',
        ]);

        $this->insert('country_city', [
            'priority' => '10000',
            'country_char_code' => 'BY',
            'city_name' => 'Minsk',
        ]);

        $this->insert('country_city', [
            'priority' => '10000',
            'country_char_code' => 'HU',
            'city_name' => 'Budapest',
        ]);

        $this->insert('country_city', [
            'priority' => '10000',
            'country_char_code' => 'MD',
            'city_name' => 'Kishinev',
        ]);

        $this->insert('country_city', [
            'priority' => '10000',
            'country_char_code' => 'RO',
            'city_name' => 'Bucharest',
        ]);

        $this->insert('country_city', [
            'priority' => '10000',
            'country_char_code' => 'GE',
            'city_name' => 'Tbilisi',
        ]);
        
        $this->insert('country_city', [
            'priority' => '10000',
            'country_char_code' => 'AZ',
            'city_name' => 'Baku',
        ]);

        $this->insert('country_city', [
            'priority' => '10000',
            'country_char_code' => 'KZ',
            'city_name' => 'Nur-Sultan',
        ]);

        $this->insert('country_city', [
            'priority' => '10000',
            'country_char_code' => 'PL',
            'city_name' => 'Warsaw',
        ]);

        $this->insert('country_city', [
            'priority' => '10000',
            'country_char_code' => 'CZ',
            'city_name' => 'Praha',
        ]);

        $this->insert('country_city', [
            'priority' => '10000',
            'country_char_code' => 'SK',
            'city_name' => 'Bratislava',
        ]);

        $this->insert('country_city', [
            'priority' => '10000',
            'country_char_code' => 'IL',
            'city_name' => 'Jerusalem',
        ]);

        $this->insert('country_city', [
            'priority' => '10000',
            'country_char_code' => 'HU',
            'city_name' => 'Budapest',
        ]);
        
        $this->insert('country_city', [
            'priority' => '10000',
            'country_char_code' => 'DE',
            'city_name' => 'Berlin',
        ]);

        $this->insert('country_city', [
            'priority' => '10000',
            'country_char_code' => 'NL',
            'city_name' => 'Amsterdam',
        ]);

        $this->insert('country_city', [
            'priority' => '10000',
            'country_char_code' => 'NO',
            'city_name' => 'Oslo',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%country_city}}');
    }
}
