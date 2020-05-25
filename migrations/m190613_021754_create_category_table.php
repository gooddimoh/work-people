<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%category}}`.
 */
class m190613_021754_create_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('category', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'background_color' => $this->string(),
            'image_path' => $this->string(),
            'tree' => $this->integer(),
            'lft' => $this->integer(),
            'rgt' => $this->integer(),
            'depth' => $this->integer(),
            'meta_keywords' => $this->text(),
            'meta_description' => $this->text(),
            'status' => $this->integer()->defaultValue(10),
            'show_on_main_page' => $this->integer()->defaultValue(20)->notNull(),
            'main_page_order' => $this->integer()->defaultValue(1000)->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        // insert standart categry list
        $this->insert('category', [
            'id' => 1,
            'name' => 'root',
            'tree' => NULL,
            'lft' => 1,
            'rgt' => 28,
            'depth' => 0,
            'meta_keywords' => NULL,
            'meta_description' => NULL,
            'status' => 10,
            'show_on_main_page' => 20,
            'main_page_order' => 1000,
        ]);

        $this->insert('category', [
            'id' => 2,
            'name' => 'Автомобильная промышленность',
            'image_path' => '1.png',
            'tree' => NULL,
            'lft' => 2,
            'rgt' => 3,
            'depth' => 1,
            'meta_keywords' => '',
            'meta_description' => '',
            'status' => 10,
            'show_on_main_page' => 10,
            'main_page_order' => 1000,
        ]);

        $this->insert('category', [
            'id' => 3,
            'name' => 'Все фабрики',
            'image_path' => '2.png',
            'tree' => NULL,
            'lft' => 4,
            'rgt' => 5,
            'depth' => 1,
            'meta_keywords' => NULL,
            'meta_description' => NULL,
            'status' => 10,
            'show_on_main_page' => 10,
            'main_page_order' => 1000,
        ]);

        $this->insert('category', [
            'id' => 4,
            'name' => 'Склады и магазины',
            'image_path' => '3.png',
            'tree' => NULL,
            'lft' => 6,
            'rgt' => 7,
            'depth' => 1,
            'meta_keywords' => NULL,
            'meta_description' => NULL,
            'status' => 10,
            'show_on_main_page' => 10,
            'main_page_order' => 1000,
        ]);

        $this->insert('category', [
            'id' => 5,
            'name' => 'Мясокомбинаты',
            'image_path' => '4.png',
            'tree' => NULL,
            'lft' => 8,
            'rgt' => 9,
            'depth' => 1,
            'meta_keywords' => '',
            'meta_description' => '',
            'status' => 10,
            'show_on_main_page' => 10,
            'main_page_order' => 1000,
        ]);

        $this->insert('category', [
            'id' => 6,
            'name' => 'Строительство',
            'image_path' => '5.png',
            'tree' => NULL,
            'lft' => 10,
            'rgt' => 11,
            'depth' => 1,
            'meta_keywords' => NULL,
            'meta_description' => NULL,
            'status' => 10,
            'show_on_main_page' => 10,
            'main_page_order' => 1000,
        ]);

        $this->insert('category', [
            'id' => 7,
            'name' => 'Водители',
            'image_path' => '6.png',
            'tree' => NULL,
            'lft' => 12,
            'rgt' => 13,
            'depth' => 1,
            'meta_keywords' => NULL,
            'meta_description' => NULL,
            'status' => 10,
            'show_on_main_page' => 10,
            'main_page_order' => 1000,
        ]);

        $this->insert('category', [
            'id' => 8,
            'name' => 'Обслуживающий персонал',
            'image_path' => '7.png',
            'tree' => NULL,
            'lft' => 14,
            'rgt' => 15,
            'depth' => 1,
            'meta_keywords' => NULL,
            'meta_description' => NULL,
            'status' => 10,
            'show_on_main_page' => 10,
            'main_page_order' => 1000,
        ]);

        $this->insert('category', [
            'id' => 9,
            'name' => 'Сельское хозяйство',
            'image_path' => '8.png',
            'tree' => NULL,
            'lft' => 16,
            'rgt' => 17,
            'depth' => 1,
            'meta_keywords' => NULL,
            'meta_description' => NULL,
            'status' => 10,
            'show_on_main_page' => 10,
            'main_page_order' => 1000,
        ]);

        $this->insert('category', [
            'id' => 10,
            'name' => 'Сварочные работы',
            'image_path' => '9.png',
            'tree' => NULL,
            'lft' => 18,
            'rgt' => 19,
            'depth' => 1,
            'meta_keywords' => NULL,
            'meta_description' => NULL,
            'status' => 10,
            'show_on_main_page' => 10,
            'main_page_order' => 1000,
        ]);

        $this->insert('category', [
            'id' => 11,
            'name' => 'Электричество',
            'image_path' => '10.png',
            'tree' => NULL,
            'lft' => 20,
            'rgt' => 21,
            'depth' => 1,
            'meta_keywords' => NULL,
            'meta_description' => NULL,
            'status' => 10,
            'show_on_main_page' => 10,
            'main_page_order' => 1000,
        ]);

        $this->insert('category', [
            'id' => 12,
            'name' => 'Швейное производство',
            'image_path' => '11.png',
            'tree' => NULL,
            'lft' => 22,
            'rgt' => 23,
            'depth' => 1,
            'meta_keywords' => NULL,
            'meta_description' => NULL,
            'status' => 10,
            'show_on_main_page' => 10,
            'main_page_order' => 1000,
        ]);

        $this->insert('category', [
            'id' => 13,
            'name' => 'Металлургическая промышленность',
            'image_path' => '12.png',
            'tree' => NULL,
            'lft' => 24,
            'rgt' => 25,
            'depth' => 1,
            'meta_keywords' => NULL,
            'meta_description' => NULL,
            'status' => 10,
            'show_on_main_page' => 10,
            'main_page_order' => 1000,
        ]);

        $this->insert('category', [
            'id' => 14,
            'name' => 'Деревообрабатывающая промышленность',
            'image_path' => '13.png',
            'tree' => NULL,
            'lft' => 26,
            'rgt' => 27,
            'depth' => 1,
            'meta_keywords' => NULL,
            'meta_description' => NULL,
            'status' => 10,
            'show_on_main_page' => 10,
            'main_page_order' => 1000,
        ]);
        }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('category', ['id' => 1]);

        $this->dropTable('{{%category}}');
    }
}
