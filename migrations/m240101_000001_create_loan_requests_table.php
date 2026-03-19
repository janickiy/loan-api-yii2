<?php

use app\models\LoanRequest;
use yii\db\Migration;

class m240101_000001_create_loan_requests_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%loan_requests}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'amount' => $this->integer()->notNull(),
            'term' => $this->integer()->notNull(),
            'status' => $this->string(16)->notNull()->defaultValue(LoanRequest::STATUS_PENDING),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('NOW()'),
            'processed_at' => $this->timestamp()->null(),
        ]);

        $this->createIndex('idx_loan_requests_status', '{{%loan_requests}}', 'status');
        $this->execute(
            "CREATE UNIQUE INDEX ux_loan_requests_user_approved ON loan_requests (user_id) WHERE status = 'approved'"
        );
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%loan_requests}}');
    }
}
