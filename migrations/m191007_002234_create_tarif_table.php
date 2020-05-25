<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tarif}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m191007_002234_create_tarif_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tarif}}', [
            'id' => $this->primaryKey(),
            'tarif_type' => $this->integer()->notNull()->defaultValue(10),
            'publication_count' => $this->integer()->notNull(),
            'top_days' => $this->integer()->notNull(),
            'upvote_count' => $this->integer()->notNull(),
            'upvote_period' => $this->integer()->notNull(),
            'vip_count' => $this->integer()->notNull(),
            'vip_period' => $this->integer()->notNull(),
            'price' => $this->integer()->notNull(),
            'discount_size' => $this->integer()->notNull(),
        ]);

        $this->addCommentOnColumn('tarif', 'tarif_type', '10 - tarif number 1, 20 - number 2, 30 - number 3');

        // type 1
        $this->insert('tarif', [
            'id' => '100',
            'tarif_type' => '10',
            'publication_count' => '1',
            'top_days' => '7',
            'upvote_count' => '0',
            'upvote_period' => '3',
            'vip_count' => '0',
            'vip_period' => '7',
            'price' => '200',
            'discount_size' => '0',
        ]);

        $this->insert('tarif', [
            'id' => '110',
            'tarif_type' => '10',
            'publication_count' => '3',
            'top_days' => '7',
            'upvote_count' => '0',
            'upvote_period' => '3',
            'vip_count' => '0',
            'vip_period' => '7',
            'price' => '550',
            'discount_size' => '30',
        ]);

        $this->insert('tarif', [
            'id' => '120',
            'tarif_type' => '10',
            'publication_count' => '10',
            'top_days' => '7',
            'upvote_count' => '0',
            'upvote_period' => '3',
            'vip_count' => '0',
            'vip_period' => '7',
            'price' => '1900',
            'discount_size' => '40',
        ]);

        // type 2
        $this->insert('tarif', [
            'id' => '200',
            'tarif_type' => '20',
            'publication_count' => '1',
            'top_days' => '14',
            'upvote_count' => '1',
            'upvote_period' => '3',
            'vip_count' => '0',
            'vip_period' => '7',
            'price' => '400',
            'discount_size' => '0',
        ]);

        $this->insert('tarif', [
            'id' => '210',
            'tarif_type' => '20',
            'publication_count' => '3',
            'top_days' => '14',
            'upvote_count' => '3',
            'upvote_period' => '3',
            'vip_count' => '0',
            'vip_period' => '7',
            'price' => '1200',
            'discount_size' => '43',
        ]);

        $this->insert('tarif', [
            'id' => '220',
            'tarif_type' => '20',
            'publication_count' => '10',
            'top_days' => '14',
            'upvote_count' => '7',
            'upvote_period' => '3',
            'vip_count' => '0',
            'vip_period' => '7',
            'price' => '4000',
            'discount_size' => '50',
        ]);

        // type 3
        $this->insert('tarif', [
            'id' => '300',
            'tarif_type' => '30',
            'publication_count' => '1',
            'top_days' => '30',
            'upvote_count' => '3',
            'upvote_period' => '3',
            'vip_count' => '1',
            'vip_period' => '7',
            'price' => '550',
            'discount_size' => '0',
        ]);

        $this->insert('tarif', [
            'id' => '310',
            'tarif_type' => '30',
            'publication_count' => '3',
            'top_days' => '30',
            'upvote_count' => '9',
            'upvote_period' => '3',
            'vip_count' => '1',
            'vip_period' => '7',
            'price' => '1600',
            'discount_size' => '40',
        ]);

        $this->insert('tarif', [
            'id' => '320',
            'tarif_type' => '30',
            'publication_count' => '10',
            'top_days' => '30',
            'upvote_count' => '20',
            'upvote_period' => '3',
            'vip_count' => '1',
            'vip_period' => '7',
            'price' => '5400',
            'discount_size' => '50',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tarif}}');
    }
}
