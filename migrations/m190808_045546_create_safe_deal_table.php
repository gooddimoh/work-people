<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%safe_deal}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m190808_045546_create_safe_deal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%safe_deal}}', [
            'id' => $this->primaryKey(),
            'creator_id' => $this->integer()->notNull(),
            'status' => $this->integer()->notNull()->defaultValue(10),
            'deal_type' => $this->integer()->notNull()->defaultValue(10),
            'has_prepaid' => $this->integer()->notNull()->defaultValue(10),
            'title' => $this->string()->notNull(),
            'amount_total' => $this->float()->notNull(),
            'amount_total_src' => $this->float()->notNull(),
            'amount_currency_code' => $this->string()->notNull(),
            'amount_prepaid' => $this->float(),
            'amount_prepaid_currency_code' => $this->string(),
            'condition_prepaid' => $this->text(),
            'condition_deal' => $this->text()->notNull(),
            'execution_period' => $this->integer()->notNull(),
            'execution_range' => $this->integer()->notNull(),
            'possible_delay_days' => $this->integer()->notNull()->defaultValue(10),
            'comission' => $this->integer()->notNull()->defaultValue(10),
            'started_at' => $this->datetime(),
            'finished_at' => $this->datetime(),
            'created_at' => $this->datetime(),
            'updated_at' => $this->datetime(),
        ]);

        $this->addCommentOnColumn('safe_deal','creator_id','user-initiator of safe deal');
        $this->addCommentOnColumn('safe_deal','status','10 - new, 20 - paid, 30 - complete, 40 - rejected, 50 - arbitration');
        $this->addCommentOnColumn('safe_deal','deal_type','10 - vacancy deal, 20 - agent deal');
        $this->addCommentOnColumn('safe_deal','has_prepaid','10 - yes, 20 - no');
        $this->addCommentOnColumn('safe_deal','amount_total','total deal amount, amount locked by initiator');
        $this->addCommentOnColumn('safe_deal','amount_total_src','amount in source currency, need for sorting');
        $this->addCommentOnColumn('safe_deal','amount_prepaid','guarantee deal prepaid amount');
        $this->addCommentOnColumn('safe_deal','execution_period','days, weeks, month before complete deal');
        $this->addCommentOnColumn('safe_deal','execution_range','mesure of execution_period: 10 - days, 20 - weeks,  30 - month;');
        $this->addCommentOnColumn('safe_deal','possible_delay_days','10 - 1-10 days; 20 - 10-30 days; 30 - 30-90 days');
        $this->addCommentOnColumn('safe_deal','comission','10 - type1, 20 - type2, 30 - type3');

        // creates index for column `creator_id`
        $this->createIndex(
            '{{%idx-safe_deal-creator_id}}',
            '{{%safe_deal}}',
            'creator_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-safe_deal-creator_id}}',
            '{{%safe_deal}}',
            'creator_id',
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
            '{{%fk-safe_deal-creator_id}}',
            '{{%safe_deal}}'
        );

        // drops index for column `creator_id`
        $this->dropIndex(
            '{{%idx-safe_deal-creator_id}}',
            '{{%safe_deal}}'
        );

        $this->dropTable('{{%safe_deal}}');
    }
}
