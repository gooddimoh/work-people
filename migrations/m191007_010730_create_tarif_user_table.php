<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tarif_user}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%tarif}}`
 */
class m191007_010730_create_tarif_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tarif_user}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'tarif_id' => $this->integer()->notNull(),
            'publication_count' => $this->integer()->notNull(),
            'upvote_count' => $this->integer()->notNull()->defaultValue(0),
            'vip_count' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->integer(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-tarif_user-user_id}}',
            '{{%tarif_user}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-tarif_user-user_id}}',
            '{{%tarif_user}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `tarif_id`
        $this->createIndex(
            '{{%idx-tarif_user-tarif_id}}',
            '{{%tarif_user}}',
            'tarif_id'
        );

        // add foreign key for table `{{%tarif}}`
        $this->addForeignKey(
            '{{%fk-tarif_user-tarif_id}}',
            '{{%tarif_user}}',
            'tarif_id',
            '{{%tarif}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-tarif_user-user_id}}',
            '{{%tarif_user}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-tarif_user-user_id}}',
            '{{%tarif_user}}'
        );

        // drops foreign key for table `{{%tarif}}`
        $this->dropForeignKey(
            '{{%fk-tarif_user-tarif_id}}',
            '{{%tarif_user}}'
        );

        // drops index for column `tarif_id`
        $this->dropIndex(
            '{{%idx-tarif_user-tarif_id}}',
            '{{%tarif_user}}'
        );

        $this->dropTable('{{%tarif_user}}');
    }
}
