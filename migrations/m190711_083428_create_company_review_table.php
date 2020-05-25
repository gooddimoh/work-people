<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%company_review}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%company}}`
 */
class m190711_083428_create_company_review_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%company_review}}', [
            'id' => $this->primaryKey(),
            'status' => $this->integer()->defaultValue(20)->notNull(),
            'company_id' => $this->integer()->notNull(),
            'position' => $this->string()->notNull(),
            'worker_status' => $this->integer()->defaultValue(20)->notNull(),
            'department' => $this->string(),
            'date_end' => $this->date(),
            'rating_total' => $this->integer()->notNull(),
            'rating_salary' => $this->integer()->notNull(),
            'rating_opportunities' => $this->integer()->notNull(),
            'rating_bosses' => $this->integer()->notNull(),
            'general_impression' => $this->string()->notNull(),
            'pluses_impression' => $this->string()->notNull(),
            'minuses_impression' => $this->string()->notNull(),
            'tips_for_bosses' => $this->string()->notNull(),
            'worker_recommendation' => $this->integer()->defaultValue(10)->notNull(),
        ]);

        $this->addCommentOnColumn('company_review','status','10 - show; 20 - hide');
        $this->addCommentOnColumn('company_review','worker_status','10 - working; 20 - fired');
        $this->addCommentOnColumn('company_review','worker_recommendation','10 - recomended; 20 - not recomended');

        // creates index for column `company_id`
        $this->createIndex(
            '{{%idx-company_review-company_id}}',
            '{{%company_review}}',
            'company_id'
        );

        // add foreign key for table `{{%company}}`
        $this->addForeignKey(
            '{{%fk-company_review-company_id}}',
            '{{%company_review}}',
            'company_id',
            '{{%company}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%company}}`
        $this->dropForeignKey(
            '{{%fk-company_review-company_id}}',
            '{{%company_review}}'
        );

        // drops index for column `company_id`
        $this->dropIndex(
            '{{%idx-company_review-company_id}}',
            '{{%company_review}}'
        );

        $this->dropTable('{{%company_review}}');
    }
}
