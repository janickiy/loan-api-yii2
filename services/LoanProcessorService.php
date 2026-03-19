<?php

namespace app\services;

use app\models\LoanRequest;
use Throwable;
use Yii;
use yii\db\Expression;

class LoanProcessorService
{
    public function processPending(int $delay): void
    {
        while ($request = $this->reserveNextPending()) {
            sleep($delay);
            $this->finalizeRequest($request);
        }
    }

    private function reserveNextPending(): ?array
    {
        $db = Yii::$app->db;

        return $db->transaction(function () use ($db) {
            $row = $db->createCommand(<<<'SQL'
                SELECT id, user_id
                FROM loan_requests
                WHERE status = :status
                ORDER BY id ASC
                FOR UPDATE SKIP LOCKED
                LIMIT 1
            SQL, [
                ':status' => LoanRequest::STATUS_PENDING,
            ])->queryOne();

            if ($row === false) {
                return null;
            }

            $db->createCommand()->update(
                LoanRequest::tableName(),
                ['status' => LoanRequest::STATUS_PROCESSING],
                ['id' => (int) $row['id']]
            )->execute();

            return [
                'id' => (int) $row['id'],
                'user_id' => (int) $row['user_id'],
            ];
        });
    }

    private function finalizeRequest(array $request): void
    {
        $db = Yii::$app->db;
        $shouldApprove = random_int(1, 10) === 1;

        $db->transaction(function () use ($db, $request, $shouldApprove) {
            $db->createCommand('SELECT pg_advisory_xact_lock(:userId)', [
                ':userId' => $request['user_id'],
            ])->execute();

            $status = LoanRequest::STATUS_DECLINED;

            if ($shouldApprove) {
                $alreadyApproved = (bool) $db->createCommand(<<<'SQL'
                    SELECT EXISTS (
                        SELECT 1
                        FROM loan_requests
                        WHERE user_id = :userId
                          AND status = :status
                    )
                SQL, [
                    ':userId' => $request['user_id'],
                    ':status' => LoanRequest::STATUS_APPROVED,
                ])->queryScalar();

                if (!$alreadyApproved) {
                    $status = LoanRequest::STATUS_APPROVED;
                }
            }

            try {
                $db->createCommand()->update(
                    LoanRequest::tableName(),
                    [
                        'status' => $status,
                        'processed_at' => new Expression('NOW()'),
                    ],
                    [
                        'id' => $request['id'],
                        'status' => LoanRequest::STATUS_PROCESSING,
                    ]
                )->execute();
            } catch (Throwable $exception) {
                $db->createCommand()->update(
                    LoanRequest::tableName(),
                    [
                        'status' => LoanRequest::STATUS_DECLINED,
                        'processed_at' => new Expression('NOW()'),
                    ],
                    [
                        'id' => $request['id'],
                        'status' => LoanRequest::STATUS_PROCESSING,
                    ]
                )->execute();
            }
        });
    }
}
