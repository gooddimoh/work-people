
<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%profile}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%category}}`
 */
class m190613_021760_create_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%profile}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'status' => $this->integer()->defaultValue(10),
            'first_name' => $this->string()->notNull(),
            'last_name' => $this->string()->notNull(),
            'middle_name' => $this->string()->defaultValue(null),
            'email' => $this->string()->notNull(),
            'gender_list' => $this->string()->notNull(),
            'birth_day' => $this->date()->notNull(),
            'country_name' => $this->string()->notNull(),
            'country_city_id' => $this->integer(),
            'photo_path' => $this->string()->defaultValue(null),
            'phone' => $this->string(),
        ]);

        $this->addCommentOnColumn('profile','status','10 - visible, 20 - hidden');
        $this->addCommentOnColumn('profile','gender_list','10; - male, 20; - female;');
        $this->addCommentOnColumn('profile','phone','additional phones siparated by;');

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-profile-user_id}}',
            '{{%profile}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-profile-user_id}}',
            '{{%profile}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `country_city_id`
        $this->createIndex(
            '{{%idx-profile-country_city_id}}',
            '{{%profile}}',
            'country_city_id'
        );

        // add foreign key for table `{{%country_city}}`
        $this->addForeignKey(
            '{{%fk-profile-country_city_id}}',
            '{{%profile}}',
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
            '{{%fk-profile-user_id}}',
            '{{%profile}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-profile-user_id}}',
            '{{%profile}}'
        );

        // drops foreign key for table `{{%country_city}}`
        $this->dropForeignKey(
            '{{%fk-profile-country_city_id}}',
            '{{%profile}}'
        );

        // drops index for column `country_city_id`
        $this->dropIndex(
            '{{%idx-profile-country_city_id}}',
            '{{%profile}}'
        );

        $this->dropTable('{{%profile}}');
    }
}
