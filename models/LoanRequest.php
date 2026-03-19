<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $user_id
 * @property int $amount
 * @property int $term
 * @property string $status
 * @property string $created_at
 * @property string|null $processed_at
 */
class LoanRequest extends ActiveRecord
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_DECLINED = 'declined';

    public static function tableName(): string
    {
        return '{{%loan_requests}}';
    }
}
