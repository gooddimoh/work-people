<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%invoice}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m190916_021210_create_invoice_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%invoice}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'status' => $this->integer()->notNull()->defaultValue(10),
            'pay_system' => $this->integer()->notNull(),
            'price' => $this->decimal(18,2)->notNull(),
            'price_source' => $this->decimal(18,2)->notNull(),
            'currency_code' => $this->string()->notNull(),
            'phone' => $this->string(),
            'pay_system_response' => $this->string(),
            'pay_system_i' => $this->integer(),
            'pay_date' => $this->integer(),
            'created_at' => $this->integer(),
        ]);

        $this->addCommentOnColumn('invoice', 'status', '10 - waiting, 20 - paid, 30 - rejected, 40 - unpaid(error), 50 - expired');

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-invoice-user_id}}',
            '{{%invoice}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-invoice-user_id}}',
            '{{%invoice}}',
            'user_id',
            '{{%user}}',
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
            '{{%fk-invoice-user_id}}',
            '{{%invoice}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-invoice-user_id}}',
            '{{%invoice}}'
        );

        $this->dropTable('{{%invoice}}');
    }
}
