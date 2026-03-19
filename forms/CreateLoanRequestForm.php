<?php

namespace app\forms;

use yii\base\Model;

class CreateLoanRequestForm extends Model
{
    public ?int $user_id = null;
    public ?int $amount = null;
    public ?int $term = null;

    public function rules(): array
    {
        return [
            [['user_id', 'amount', 'term'], 'required'],
            [['user_id', 'amount', 'term'], 'integer'],
            [['user_id', 'amount', 'term'], 'compare', 'operator' => '>', 'compareValue' => 0],
        ];
    }
}
